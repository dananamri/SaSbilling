<x-customer-layout>
    @section('title', 'Dashboard')
    @section('header', 'Dashboard Pelanggan')

    @section('content')
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <p class="text-sm text-gray-500 mb-1">Total Tagihan</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <p class="text-sm text-gray-500 mb-1">Total Dibayar</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <p class="text-sm text-gray-500 mb-1">Status Layanan</p>
            <p class="text-xl sm:text-2xl font-bold {{ $customer->status->value == 'active' ? 'text-green-600' : 'text-red-600' }}">
                {{ ucfirst($customer->status->value) }}
            </p>
        </div>
    </div>

    <!-- Paket Internet -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Paket Internet</h3>
        @if($customer->package)
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $customer->package->name }}</p>
                    <p class="text-sm text-gray-500">{{ $customer->package->speed }}</p>
                    <p class="text-sm font-medium text-gray-900 mt-1">Rp {{ number_format($customer->package->price, 0, ',', '.') }}/bulan</p>
                </div>
                <div class="flex flex-col sm:items-end gap-2">
                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                    <a href="{{ route('customer.packages') }}" class="text-sm text-blue-600 hover:underline">Lihat Detail</a>
                </div>
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada paket</p>
        @endif
    </div>

    <!-- Tagihan Berjalan -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Tagihan Berjalan</h3>
        @if($currentInvoice)
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500">Tagihan {{ $currentInvoice->period }}</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">Rp {{ number_format($currentInvoice->remaining(), 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500 mt-1">Jatuh Tempo: {{ $currentInvoice->due_date->format('d M Y') }}</p>
                </div>
                <div class="flex flex-col sm:items-end gap-2">
                    <span class="px-3 py-1 text-xs rounded-full {{ $currentInvoice->status->value == 'paid' ? 'bg-green-100 text-green-800' : ($currentInvoice->status->value == 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($currentInvoice->status->value) }}
                    </span>
                    <div class="flex gap-2">
                        <a href="{{ route('customer.invoices.show', $currentInvoice) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Lihat Tagihan</a>
                        @if($currentInvoice->status !== 'paid')
                            <a href="{{ route('customer.payments') }}" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">Bayar Sekarang</a>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <p class="text-gray-500 text-sm">Tidak ada tagihan berjalan</p>
        @endif
    </div>

    <!-- Status Koneksi -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Status Koneksi</h3>
        <div class="flex items-center gap-3">
            <span class="relative flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $connectionStatus == 'online' ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 {{ $connectionStatus == 'online' ? 'bg-green-500' : 'bg-red-500' }}"></span>
            </span>
            <span class="text-lg font-bold {{ $connectionStatus == 'online' ? 'text-green-600' : 'text-red-600' }}">
                {{ ucfirst($connectionStatus) }}
            </span>
        </div>
        <p class="text-sm text-gray-500 mt-2">
            {{ $connectionStatus == 'online' ? 'Koneksi internet Anda aktif' : 'Koneksi internet Anda tidak aktif' }}
        </p>
    </div>

    <!-- Tagihan Terbaru -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Tagihan Terbaru</h3>
            <a href="{{ route('customer.invoices') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        @if($recentInvoices->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Invoice</th>
                            <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Periode</th>
                            <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentInvoices as $invoice)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</td>
                                <td class="py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $invoice->period }}</td>
                                <td class="py-3 text-sm text-gray-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $invoice->status->value == 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status->value == 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($invoice->status->value) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada tagihan</p>
        @endif
    </div>

    <!-- Pembayaran Terbaru -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Pembayaran Terbaru</h3>
            <a href="{{ route('customer.payments') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        @if($recentPayments->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Invoice</th>
                            <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Metode</th>
                            <th class="text-right py-3 text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPayments as $payment)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-sm text-gray-600">{{ $payment->created_at->format('d M Y') }}</td>
                                <td class="py-3 text-sm font-medium text-gray-900">{{ $payment->invoice->invoice_number }}</td>
                                <td class="py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $payment->method->label() }}</td>
                                <td class="py-3 text-sm text-gray-900 text-right font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada pembayaran</p>
        @endif
    </div>
    @endsection
</x-customer-layout>
