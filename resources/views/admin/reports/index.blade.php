@extends('layouts.admin')
    @section('title', 'Laporan')
    @section('header', 'Laporan')

    @section('content')
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row sm:items-end gap-3">
            <div class="flex-1">
                <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div class="flex-1">
                <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none bg-slate-600 text-white px-4 py-2 rounded text-sm hover:bg-slate-700 text-center">Tampilkan</button>
                <a href="{{ route('admin.reports.pdf', request()->query()) }}" class="flex-1 sm:flex-none bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700 flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <p class="text-sm text-gray-500">Total Pendapatan</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <p class="text-sm text-gray-500">Jumlah Transaksi</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ $paymentCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <p class="text-sm text-gray-500">Total Tunggakan</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600">Rp {{ number_format($outstanding, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold mb-4">Pendapatan per Metode</h3>
            @if($revenueByMethod->isNotEmpty())
                <div class="space-y-2">
                    @foreach($revenueByMethod as $method => $total)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">{{ ucfirst(str_replace('_', ' ', $method)) }}</span>
                            <span class="text-sm font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">Tidak ada data</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-semibold mb-4">Pendapatan Harian</h3>
            @if($dailyRevenue->isNotEmpty())
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @foreach($dailyRevenue as $date => $total)
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span>
                            <span class="text-sm font-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">Tidak ada data</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h3 class="text-lg font-semibold mb-4">Daftar Tunggakan</h3>
        @if($overdueInvoices->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="border-b"><th class="text-left py-2">Invoice</th><th class="text-left py-2">Pelanggan</th><th class="text-left py-2">Jatuh Tempo</th><th class="text-right py-2">Sisa</th></tr></thead>
                    <tbody>
                        @foreach($overdueInvoices as $invoice)
                            <tr class="border-b">
                                <td class="py-2">{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->customer->name }}</td>
                                <td>{{ $invoice->due_date->format('d M Y') }}</td>
                                <td class="text-right text-red-600 font-medium">Rp {{ number_format($invoice->remaining(), 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-sm">Tidak ada tunggakan</p>
        @endif
    </div>
    @endsection
