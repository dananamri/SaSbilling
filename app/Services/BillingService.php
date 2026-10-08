<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BillingService
{
    public function generateMonthlyInvoices(Carbon $date): int
    {
        $period = $date->format('Y-m');
        $billingDay = (int) $date->format('d');
        $dueDate = $date->copy()->addDays(7);

        $customers = Customer::where('billing_day', $billingDay)
            ->where('status', '!=', 'inactive')
            ->whereDoesntHave('invoices', fn ($q) => $q->where('period', $period))
            ->get();

        $count = 0;

        foreach ($customers as $customer) {
            DB::transaction(function () use ($customer, $period, $dueDate, &$count) {
                $package = $customer->package;

                if (! $package) {
                    return;
                }

                $lastInvoice = $customer->invoices()->orderByDesc('due_date')->first();
                $amount = $lastInvoice?->amount ?? $package->price;

                Invoice::create([
                    'invoice_number' => $this->generateInvoiceNumber(),
                    'customer_id' => $customer->id,
                    'period' => $period,
                    'amount' => $amount,
                    'status' => InvoiceStatus::Unpaid,
                    'due_date' => $dueDate,
                ]);

                $count++;
            });
        }

        return $count;
    }

    public function generateInvoiceNumber(): string
    {
        $prefix = 'INV-'.now()->format('Ymd');
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix.'%')
            ->orderByDesc('invoice_number')
            ->first();

        $next = $lastInvoice
            ? (int) substr($lastInvoice->invoice_number, -4) + 1
            : 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function markOverdueInvoices(): int
    {
        return Invoice::whereIn('status', [InvoiceStatus::Unpaid, InvoiceStatus::Partial])
            ->where('due_date', '<', now())
            ->update(['status' => InvoiceStatus::Overdue]);
    }

    public function updateInvoiceStatus(Invoice $invoice): void
    {
        $totalPaid = $invoice->totalPaid();
        $totalDue = $invoice->totalDue();

        if ($totalPaid <= 0) {
            $invoice->status = $invoice->due_date->isPast()
                ? InvoiceStatus::Overdue
                : InvoiceStatus::Unpaid;
        } elseif ($totalPaid < $totalDue) {
            $invoice->status = InvoiceStatus::Partial;
        } else {
            $invoice->status = InvoiceStatus::Paid;
            $invoice->paid_at = now();
        }

        $invoice->save();
    }
}
