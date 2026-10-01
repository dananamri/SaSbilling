<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Payment;
use App\Services\BillingService;
use App\Services\IsolirService;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_package(): void
    {
        $package = Package::create([
            'name' => 'Test Package',
            'speed' => '50 Mbps',
            'price' => 250000,
        ]);

        $this->assertDatabaseHas('packages', ['name' => 'Test Package']);
        $this->assertEquals(250000, $package->price);
    }

    public function test_can_create_customer(): void
    {
        $package = Package::create(['name' => 'Test', 'price' => 100000]);

        $customer = Customer::create([
            'name' => 'John Doe',
            'phone' => '081234567890',
            'package_id' => $package->id,
            'billing_day' => 15,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('customers', ['phone' => '081234567890']);
        $this->assertEquals('active', $customer->status->value);
    }

    public function test_can_create_invoice(): void
    {
        $customer = Customer::factory()->create();

        $invoice = Invoice::create([
            'invoice_number' => 'INV-TEST-001',
            'customer_id' => $customer->id,
            'period' => '2026-09',
            'amount' => 150000,
            'status' => InvoiceStatus::Unpaid,
            'due_date' => now()->addDays(7),
        ]);

        $this->assertDatabaseHas('invoices', ['invoice_number' => 'INV-TEST-001']);
        $this->assertEquals(150000, $invoice->amount);
    }

    public function test_can_record_payment(): void
    {
        $invoice = Invoice::factory()->create();

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => 150000,
            'method' => 'cash',
            'paid_by' => 'John',
        ]);

        $this->assertDatabaseHas('payments', ['amount' => 150000]);
        $this->assertEquals(150000, $invoice->totalPaid());
    }

    public function test_invoice_status_updates_after_full_payment(): void
    {
        $service = new BillingService;
        $invoice = Invoice::factory()->create(['amount' => 150000]);

        Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => 150000,
            'method' => 'cash',
        ]);

        $service->updateInvoiceStatus($invoice);

        $this->assertEquals(InvoiceStatus::Paid, $invoice->fresh()->status);
    }

    public function test_invoice_status_partial_after_partial_payment(): void
    {
        $service = new BillingService;
        $invoice = Invoice::factory()->create(['amount' => 150000]);

        Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => 50000,
            'method' => 'cash',
        ]);

        $service->updateInvoiceStatus($invoice);

        $this->assertEquals(InvoiceStatus::Partial, $invoice->fresh()->status);
    }

    public function test_isolir_service_isolates_customer(): void
    {
        $service = new IsolirService;
        $customer = Customer::factory()->create(['status' => 'active']);

        $service->isolate($customer, 'Test isolir');

        $this->assertEquals('isolated', $customer->fresh()->status->value);
        $this->assertDatabaseHas('isolir_logs', [
            'customer_id' => $customer->id,
            'action' => 'isolate',
        ]);
    }

    public function test_isolir_service_reopens_customer(): void
    {
        $service = new IsolirService;
        $customer = Customer::factory()->create(['status' => 'isolated']);

        $service->reopen($customer, 'Lunas');

        $this->assertEquals('active', $customer->fresh()->status->value);
    }

    public function test_notification_service_creates_notification(): void
    {
        $service = new NotificationService;
        $customer = Customer::factory()->create();
        $invoice = Invoice::factory()->create(['customer_id' => $customer->id]);

        $notification = $service->sendReminder($customer, $invoice);

        $this->assertDatabaseHas('notifications', [
            'customer_id' => $customer->id,
            'type' => 'reminder',
        ]);
        $this->assertStringContainsString('pengingat', strtolower($notification->message));
    }

    public function test_customer_total_outstanding(): void
    {
        $customer = Customer::factory()->create();
        $invoice = Invoice::factory()->create([
            'customer_id' => $customer->id,
            'amount' => 150000,
            'status' => 'unpaid',
        ]);

        Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => 50000,
            'method' => 'cash',
        ]);

        $this->assertEquals(100000, $customer->totalOutstanding());
    }

    public function test_billing_service_generates_invoice_number(): void
    {
        $service = new BillingService;
        $number = $service->generateInvoiceNumber();

        $this->assertStringStartsWith('INV-', $number);
    }
}
