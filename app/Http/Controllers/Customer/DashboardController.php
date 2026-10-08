<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $customer = auth('customer')->user();

        $totalUnpaid = Invoice::where('customer_id', $customer->id)
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->get()
            ->sum(fn (Invoice $invoice) => $invoice->remaining());

        $totalPaid = Payment::where('status', 'success')
            ->whereHas('invoice', fn ($q) => $q->where('customer_id', $customer->id))
            ->sum('amount');

        $currentInvoice = Invoice::where('customer_id', $customer->id)
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->orderByDesc('created_at')
            ->first();

        $lastPayment = Payment::whereHas('invoice', fn ($q) => $q->where('customer_id', $customer->id))
            ->orderByDesc('created_at')
            ->first();

        $recentInvoices = Invoice::where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $recentPayments = Payment::whereHas('invoice', fn ($q) => $q->where('customer_id', $customer->id))
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $connectionStatus = $customer->status->value === 'active' ? 'online' : 'offline';

        return view('customer.dashboard', compact(
            'customer',
            'totalUnpaid',
            'totalPaid',
            'currentInvoice',
            'lastPayment',
            'recentInvoices',
            'recentPayments',
            'connectionStatus',
        ));
    }
}
