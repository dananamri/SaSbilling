<?php

namespace Tests\Feature;

use App\Enums\CustomerStatus;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Ticket;
use App\Services\WhatsAppGatewayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTicketTest extends TestCase
{
    use RefreshDatabase;

    private function makeCustomer(): Customer
    {
        $package = Package::create([
            'name' => 'Home Pro 50M',
            'speed' => '50 Mbps',
            'price' => 250000,
            'is_active' => true,
        ]);

        return Customer::create([
            'name' => 'Pelanggan Pengaduan',
            'username' => 'pelangganaduan',
            'email' => 'aduan@example.com',
            'phone' => '081177788899',
            'address' => 'Alamat Test',
            'password' => bcrypt('password123'),
            'package_id' => $package->id,
            'status' => CustomerStatus::Active,
            'billing_day' => 1,
        ]);
    }

    public function test_create_form_uses_category_slug_and_has_no_title_field(): void
    {
        $this->actingAs($this->makeCustomer(), 'customer');

        $this->get(route('customer.tickets.create'))
            ->assertOk()
            ->assertSee('value="internet_mati"', false)
            ->assertDontSee('name="title"', false);
    }

    public function test_customer_can_submit_complaint_without_title(): void
    {
        $this->mock(WhatsAppGatewayService::class, fn ($mock) => $mock
            ->shouldReceive('sendToAdmin')
            ->once()
            ->shouldReceive('getAdminPhone')
            ->andReturn('+628111111111')
            ->shouldReceive('generateWaLink')
            ->andReturn('https://wa.me/628111111111'));

        $customer = $this->makeCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->post(route('customer.tickets.store'), [
            'category' => 'internet_mati',
            'description' => 'Internet mati total sejak pagi hari.',
            'priority' => 'high',
        ]);

        $ticket = Ticket::first();
        $this->assertNotNull($ticket);

        $response->assertRedirect(route('customer.tickets.show', $ticket));

        $this->assertDatabaseHas('tickets', [
            'customer_id' => $customer->id,
            'category' => 'internet_mati',
            'title' => 'Internet Mati',
            'priority' => 'high',
            'status' => 'open',
        ]);

        // Halaman detail tiket harus tampil (sebelum fix trait authorize hilang -> 500).
        $this->get(route('customer.tickets.show', $ticket))
            ->assertOk()
            ->assertSee('Internet Mati')
            ->assertSee($ticket->ticket_number);
    }

    public function test_label_category_value_is_rejected(): void
    {
        $this->mock(WhatsAppGatewayService::class, fn ($mock) => $mock
            ->shouldReceive('sendToAdmin')
            ->never());

        $this->actingAs($this->makeCustomer(), 'customer');

        $this->post(route('customer.tickets.store'), [
            'category' => 'Internet Mati',
            'description' => 'Deskripsi.',
            'priority' => 'low',
        ])->assertSessionHasErrors('category');

        $this->assertDatabaseCount('tickets', 0);
    }
}
