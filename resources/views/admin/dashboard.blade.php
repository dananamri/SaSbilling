@extends('layouts.admin')
    @section('title', 'Dashboard')
    @section('header', 'Dashboard')

    @section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Total Pelanggan</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-800">{{ $totalCustomers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Pelanggan Aktif</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-800">{{ $activeCustomers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Diisolir</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-800">{{ $isolatedCustomers }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Total Tunggakan</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-800">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue & Overdue -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Pendapatan Bulan Ini</h3>
            <p class="text-2xl sm:text-3xl font-bold text-green-600">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Tunggakan Terlama</h3>
            @if($overdueInvoices->isNotEmpty())
                <div class="space-y-2">
                    @foreach($overdueInvoices->take(5) as $invoice)
                        <div class="flex justify-between items-center p-2 bg-red-50 rounded">
                            <div>
                                <p class="font-medium text-sm">{{ $invoice->customer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $invoice->invoice_number }} - {{ $invoice->due_date->format('d M Y') }}</p>
                            </div>
                            <p class="text-sm font-semibold text-red-600">Rp {{ number_format($invoice->remaining(), 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Tidak ada tunggakan</p>
            @endif
        </div>
    </div>

    <!-- Traffic Bulanan (chart SVG murni, tanpa library eksternal) -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Traffic Bulanan</h3>
                <p class="text-xs text-gray-500">Total download + upload 6 bulan terakhir</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Bulan Ini</p>
                <p class="text-lg sm:text-xl font-bold text-blue-600">{{ $trafficMonths->last()['display'] }}</p>
            </div>
        </div>

        @if($trafficMonths->sum('bytes') === 0)
            <p class="text-gray-500 text-sm py-10 text-center">
                Belum ada data traffic. Grafik akan terisi otomatis ketika monitoring bandwidth router mulai mencatat.
            </p>
        @else
            @php
                $chartWidth = 600;
                $chartTop = 14;
                $chartBaseline = 138;
                $paddingX = 14;
                $maxBytes = max($trafficMonths->max('bytes'), 1);
                $step = ($chartWidth - $paddingX * 2) / max($trafficMonths->count() - 1, 1);

                $points = $trafficMonths->map(fn (array $month, int $index) => [
                    'x' => $paddingX + $index * $step,
                    'y' => $chartTop + (1 - $month['bytes'] / $maxBytes) * ($chartBaseline - $chartTop),
                ])->values();

                $linePath = sprintf('M %.1f %.1f', $points[0]['x'], $points[0]['y']);
                for ($i = 1; $i < $points->count(); $i++) {
                    $previous = $points[$i - 1];
                    $current = $points[$i];
                    $midX = ($previous['x'] + $current['x']) / 2;
                    $linePath .= sprintf(' C %.1f %.1f, %.1f %.1f, %.1f %.1f', $midX, $previous['y'], $midX, $current['y'], $current['x'], $current['y']);
                }
                $areaPath = $linePath.sprintf(' L %.1f %.1f L %.1f %.1f Z', $points->last()['x'], $chartBaseline, $points[0]['x'], $chartBaseline);
            @endphp

            <svg viewBox="0 0 600 170" class="w-full h-auto" role="img" aria-label="Grafik traffic bulanan 6 bulan terakhir">
                <defs>
                    <linearGradient id="trafficAreaFill" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#0066FF" stop-opacity="0.25"></stop>
                        <stop offset="100%" stop-color="#0066FF" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>

                @foreach([$chartTop, ($chartTop + $chartBaseline) / 2, $chartBaseline] as $gridY)
                    <line x1="0" y1="{{ $gridY }}" x2="600" y2="{{ $gridY }}" stroke="#E5E7EB" stroke-width="1"></line>
                @endforeach

                <path d="{{ $areaPath }}" fill="url(#trafficAreaFill)"></path>
                <path d="{{ $linePath }}" fill="none" stroke="#0066FF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>

                @foreach($points as $index => $point)
                    <circle cx="{{ round($point['x'], 1) }}" cy="{{ round($point['y'], 1) }}" r="4" fill="#FFFFFF" stroke="#0066FF" stroke-width="2.5">
                        <title>{{ $trafficMonths[$index]['label'] }}: {{ $trafficMonths[$index]['display'] }}</title>
                    </circle>
                    <text x="{{ round($point['x'], 1) }}" y="160" text-anchor="middle" font-size="12" fill="#6B7280">{{ $trafficMonths[$index]['label'] }}</text>
                @endforeach
            </svg>
        @endif
    </div>

    <!-- Recent Payments -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Pembayaran Terbaru</h3>
        @if($recentPayments->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 text-sm font-medium text-gray-500">Tanggal</th>
                            <th class="text-left py-2 text-sm font-medium text-gray-500">Pelanggan</th>
                            <th class="text-left py-2 text-sm font-medium text-gray-500 hidden sm:table-cell">Invoice</th>
                            <th class="text-left py-2 text-sm font-medium text-gray-500 hidden sm:table-cell">Metode</th>
                            <th class="text-right py-2 text-sm font-medium text-gray-500">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPayments as $payment)
                            <tr class="border-b">
                                <td class="py-2 text-sm">{{ $payment->created_at->format('d M Y') }}</td>
                                <td class="py-2 text-sm">{{ $payment->invoice->customer->name }}</td>
                                <td class="py-2 text-sm hidden sm:table-cell">{{ $payment->invoice->invoice_number }}</td>
                                <td class="py-2 text-sm hidden sm:table-cell">{{ $payment->method->label() }}</td>
                                <td class="py-2 text-sm text-right font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">Belum ada pembayaran</p>
        @endif
    </div>
    @endsection
