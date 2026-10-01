<?php

namespace App\Console\Commands;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Services\BillingService;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendBillingReminders extends Command
{
    protected $signature = 'billing:remind';

    protected $description = 'Send billing reminders for overdue invoices';

    public function handle(NotificationService $notificationService, BillingService $billingService): int
    {
        $billingService->markOverdueInvoices();

        $invoices = Invoice::with('customer')
            ->whereIn('status', [InvoiceStatus::Unpaid, InvoiceStatus::Overdue, InvoiceStatus::Partial])
            ->where('due_date', '<=', now()->addDays(3))
            ->get();

        $count = 0;

        foreach ($invoices as $invoice) {
            $notificationService->sendReminder($invoice->customer, $invoice);
            $count++;
        }

        $this->info("Sent {$count} billing reminders.");

        return self::SUCCESS;
    }
}
