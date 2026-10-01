<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CustomerStatus;
use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', CustomerStatus::Active)->count();
        $isolatedCustomers = Customer::where('status', CustomerStatus::Isolated)->count();

        $totalOutstanding = Invoice::whereIn('status', [InvoiceStatus::Unpaid, InvoiceStatus::Partial, InvoiceStatus::Overdue])
            ->get()
            ->sum(fn (Invoice $invoice) => $invoice->remaining());

        $monthlyRevenue = Payment::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $overdueInvoices = Invoice::with('customer')
            ->whereIn('status', [InvoiceStatus::Unpaid, InvoiceStatus::Overdue, InvoiceStatus::Partial])
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        $recentPayments = Payment::with('invoice.customer')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $revenueByMonth = Payment::selectRaw('strftime("%Y-%m", created_at) as month, SUM(amount) as total')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('admin.dashboard', compact(
            'totalCustomers',
            'activeCustomers',
            'isolatedCustomers',
            'totalOutstanding',
            'monthlyRevenue',
            'overdueInvoices',
            'recentPayments',
            'revenueByMonth',
        ));
    }
}
