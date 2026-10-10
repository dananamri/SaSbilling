<?php

namespace Tests\Feature;

use App\Enums\CustomerStatus;
use App\Models\Customer;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerRegistrationPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_and_gets_pending_status_with_unpaid_invoice(): void
    {
        $package = Package::create([
            'name' => 'Home Pro 50M',
            'type' => 'internet',
            'speed' => '50 Mbps',
            'price' => 250000,
            'is_active' => true,
        ]);

        $response = $this->post(route('customer.register.post'), [
            'name' => 'Budi Santoso',
            'username' => 'budisantoso',
            'email' => 'budi@example.com',
            'phone' => '081298765432',
            'address' => 'Jl. Merdeka No 123',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'package_id' => $package->id,
            'payment_method' => 'transfer_bca',
        ]);

        $customer = Customer::where('username', 'budisantoso')->first();
        $this->assertNotNull($customer);
        $this->assertEquals(CustomerStatus::Pending, $customer->status);

        $response->assertRedirect(route('customer.pending-approval', ['customer' => $customer->id]));

        // Cek invoice dan payment pending terbuat
        $this->assertDatabaseHas('invoices', [
            'customer_id' => $customer->id,
            'amount' => 250000,
            'status' => 'unpaid',
        ]);

        $this->assertDatabaseHas('payments', [
            'amount' => 250000,
            'status' => 'pending',
            'method' => 'transfer_bca',
        ]);

        // Cek halaman pending approval menampilkan tombol WA dan rincian
        $pendingPage = $this->get(route('customer.pending-approval', ['customer' => $customer->id]));
        $pendingPage->assertStatus(200);
        $pendingPage->assertSee('Budi Santoso');
        $pendingPage->assertSee('Rp 250.000');
        $pendingPage->assertSee('Konfirmasi ke WhatsApp Admin');
    }

    public function test_pending_customer_cannot_access_dashboard_until_approved(): void
    {
        $package = Package::create([
            'name' => 'Home Pro',
            'speed' => '50 Mbps',
            'price' => 250000,
            'is_active' => true,
        ]);

        $customer = Customer::create([
            'name' => 'Pending User',
            'username' => 'pendinguser',
            'email' => 'pending@example.com',
            'phone' => '08111222333',
            'address' => 'Alamat',
            'password' => bcrypt('password123'),
            'package_id' => $package->id,
            'status' => CustomerStatus::Pending,
            'billing_day' => 1,
        ]);

        // Coba login sebelum diapprove
        $loginResponse = $this->post(route('customer.login.post'), [
            'login' => 'pendinguser',
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect(route('customer.pending-approval', ['customer' => $customer->id]));
        $this->assertGuest('customer');

        // Admin approve customer
        $admin = User::factory()->create();
        $approveResponse = $this->actingAs($admin)->post(route('admin.customers.approve', $customer));
        $approveResponse->assertRedirect();

        $customer->refresh();
        $this->assertEquals(CustomerStatus::Active, $customer->status);

        // Setelah diapprove sekarang bisa login dan akses dashboard
        $this->post(route('customer.login.post'), [
            'login' => 'pendinguser',
            'password' => 'password123',
        ])->assertRedirect(route('customer.dashboard'));

        $this->assertAuthenticatedAs($customer, 'customer');
    }
}
