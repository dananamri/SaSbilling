<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $payments = Payment::with('invoice.customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalRevenue = $payments->sum('amount');
        $paymentCount = $payments->count();

        $revenueByMethod = $payments->groupBy('method')
            ->map(fn ($group) => $group->sum('amount'));

        $outstanding = Invoice::whereIn('status', [InvoiceStatus::Unpaid, InvoiceStatus::Partial, InvoiceStatus::Overdue])
            ->get()
            ->sum(fn (Invoice $invoice) => $invoice->remaining());

        $overdueInvoices = Invoice::with('customer')
            ->whereIn('status', [InvoiceStatus::Unpaid, InvoiceStatus::Overdue, InvoiceStatus::Partial])
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->get();

        $dailyRevenue = Payment::selectRaw('date(created_at) as date, SUM(amount) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'paymentCount',
            'revenueByMethod',
            'outstanding',
            'overdueInvoices',
            'dailyRevenue',
        ));
    }

    public function downloadPdf(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $payments = Payment::with('invoice.customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalRevenue = $payments->sum('amount');
        $paymentCount = $payments->count();

        $revenueByMethod = $payments->groupBy('method')
            ->map(fn ($group) => $group->sum('amount'));

        $outstanding = Invoice::whereIn('status', [InvoiceStatus::Unpaid, InvoiceStatus::Partial, InvoiceStatus::Overdue])
            ->get()
            ->sum(fn (Invoice $invoice) => $invoice->remaining());

        $overdueInvoices = Invoice::with('customer')
            ->whereIn('status', [InvoiceStatus::Unpaid, InvoiceStatus::Overdue, InvoiceStatus::Partial])
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->get();

        $pdf = new Dompdf();
        $pdf->loadHtml(view('admin.reports.pdf', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'paymentCount',
            'revenueByMethod',
            'outstanding',
            'overdueInvoices',
        ))->render());
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->stream('laporan-satak-'.now()->format('Y-m-d').'.pdf');
    }
}
