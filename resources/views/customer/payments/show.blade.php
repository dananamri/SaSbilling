<x-customer-layout>
    @section('title', 'Detail Pembayaran')
    @section('header', 'Detail Pembayaran')

    @section('content')
    <div class="space-y-6">
        <!-- Info Pembayaran -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Pembayaran #{{ $payment->id }}</h3>
                    <p class="text-sm text-gray-500">Invoice: {{ $payment->invoice->invoice_number }}</p>
                </div>
                <a href="{{ route('customer.payments.receipt', $payment) }}" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download Bukti
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Tanggal Pembayaran</p>
                    <p class="font-medium text-gray-900">{{ $payment->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Metode</p>
                    <p class="font-medium text-gray-900">{{ $payment->method->label() }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Jumlah</p>
                    <p class="font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    <span class="px-2 py-1 text-xs rounded-full {{ $payment->status == 'success' ? 'bg-green-100 text-green-800' : ($payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Invoice -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Info Invoice</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Nomor Invoice</p>
                    <p class="font-medium text-gray-900">{{ $payment->invoice->invoice_number }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Periode</p>
                    <p class="font-medium text-gray-900">{{ $payment->invoice->period }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Paket</p>
                    <p class="font-medium text-gray-900">{{ $payment->invoice->customer->package->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Total Tagihan</p>
                    <p class="font-medium text-gray-900">Rp {{ number_format($payment->invoice->amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-customer-layout>
