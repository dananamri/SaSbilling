<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): void
    {
        $user = User::create([
            'name' => 'Admin Test',
            'username' => 'adminSaS',
            'email' => 'admin@test.com',
            'password' => Hash::make('adminSaS26'),
        ]);

        $this->actingAs($user);
    }

    public function test_admin_dashboard_accessible(): void
    {
        $this->authenticate();
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_admin_customers_index_accessible(): void
    {
        $this->authenticate();
        $response = $this->get('/admin/customers');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_customer(): void
    {
        $this->authenticate();
        $package = Package::create(['name' => 'Test', 'price' => 100000]);

        $response = $this->post('/admin/customers', [
            'name' => 'Test Customer',
            'username' => 'testcustomer',
            'phone' => '081234567890',
            'package_id' => $package->id,
            'billing_day' => 15,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/admin/customers');
        $this->assertDatabaseHas('customers', ['phone' => '081234567890']);
    }

    public function test_admin_packages_index_accessible(): void
    {
        $this->authenticate();
        $response = $this->get('/admin/packages');
        $response->assertStatus(200);
    }

    public function test_admin_invoices_index_accessible(): void
    {
        $this->authenticate();
        $response = $this->get('/admin/invoices');
        $response->assertStatus(200);
    }

    public function test_admin_payments_index_accessible(): void
    {
        $this->authenticate();
        $response = $this->get('/admin/payments');
        $response->assertStatus(200);
    }

    public function test_admin_isolir_index_accessible(): void
    {
        $this->authenticate();
        $response = $this->get('/admin/isolir');
        $response->assertStatus(200);
    }

    public function test_admin_notifications_index_accessible(): void
    {
        $this->authenticate();
        $response = $this->get('/admin/notifications');
        $response->assertStatus(200);
    }

    public function test_admin_reports_index_accessible(): void
    {
        $this->authenticate();
        $response = $this->get('/admin/reports');
        $response->assertStatus(200);
    }

    public function test_admin_settings_index_accessible(): void
    {
        $this->authenticate();
        $response = $this->get('/admin/settings');
        $response->assertStatus(200);
    }

    public function test_login_page_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_login_with_valid_credentials(): void
    {
        User::create([
            'name' => 'Admin Test',
            'username' => 'adminSaS',
            'email' => 'admin@test.com',
            'password' => Hash::make('adminSaS26'),
        ]);

        $response = $this->post('/login', [
            'username' => 'adminSaS',
            'password' => 'adminSaS26',
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'wrong',
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrors('username');
    }
}
