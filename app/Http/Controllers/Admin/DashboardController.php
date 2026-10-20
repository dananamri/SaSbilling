<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CustomerStatus;
use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\BandwidthSnapshot;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Support\TrafficDemo;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Bersihkan data demo trafik sementara begitu masa berlakunya habis.
        TrafficDemo::cleanup();

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

        $dateFormat = DB::getDriverName() === 'sqlite'
            ? 'strftime("%Y-%m", created_at)'
            : 'DATE_FORMAT(created_at, "%Y-%m")';

        $revenueByMonth = Payment::selectRaw("{$dateFormat} as month, SUM(amount) as total")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $trafficByMonth = BandwidthSnapshot::selectRaw("{$dateFormat} as month, SUM(rx_bytes + tx_bytes) as total")
            ->where('recorded_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $trafficMonths = collect(range(5, 0))->map(function (int $offset) use ($trafficByMonth): array {
            $month = now()->subMonths($offset);
            $bytes = (int) $trafficByMonth->get($month->format('Y-%m'), 0);

            return [
                'label' => $month->format('M'),
                'bytes' => $bytes,
                'display' => $this->formatBytes($bytes),
            ];
        })->values();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'activeCustomers',
            'isolatedCustomers',
            'totalOutstanding',
            'monthlyRevenue',
            'overdueInvoices',
            'recentPayments',
            'revenueByMonth',
            'trafficMonths',
        ));
    }

    /**
     * Format jumlah byte ke satuan yang mudah dibaca (KB/MB/GB/...).
     */
    private function formatBytes(int|float $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $index = 0;

        while ($bytes >= 1024 && $index < count($units) - 1) {
            $bytes /= 1024;
            $index++;
        }

        return number_format($bytes, $index === 0 ? 0 : ($bytes >= 100 ? 0 : 1), ',', '.').' '.$units[$index];
    }
}
