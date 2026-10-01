<x-customer-layout>
    @section('title', 'Detail Tagihan')
    @section('header', 'Detail Tagihan')

    @section('content')
    <div class="space-y-6">
        <!-- Info Tagihan -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $invoice->invoice_number }}</h3>
                    <p class="text-sm text-gray-500">Periode: {{ $invoice->period }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('customer.invoices.download', $invoice) }}" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download
                    </a>
                    <a href="{{ route('customer.invoices.print', $invoice) }}" target="_blank" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Paket</p>
                    <p class="font-medium text-gray-900">{{ $invoice->customer->package->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Jatuh Tempo</p>
                    <p class="font-medium text-gray-900">{{ $invoice->due_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    <span class="px-2 py-1 text-xs rounded-full {{ $invoice->status->value == 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status->value == 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($invoice->status->value) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Rincian Tagihan -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Rincian Tagihan</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">{{ $invoice->customer->package->name ?? 'Paket Internet' }}</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                </div>
                @if($invoice->late_fee > 0)
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Denda Keterlambatan</span>
                    <span class="font-medium text-red-600">Rp {{ number_format($invoice->late_fee, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($invoice->discount > 0)
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Diskon</span>
                    <span class="font-medium text-green-600">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between py-2">
                    <span class="font-semibold text-gray-900">Total</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($invoice->amount + $invoice->late_fee - $invoice->discount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Riwayat Pembayaran</h3>
            @if($invoice->payments->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="border-b border-gray-100"><th class="text-left py-2 text-gray-500">Tanggal</th><th class="text-left py-2 text-gray-500">Metode</th><th class="text-right py-2 text-gray-500">Jumlah</th></tr></thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                                <tr class="border-b border-gray-50">
                                    <td class="py-2 text-gray-600">{{ $payment->created_at->format('d M Y') }}</td>
                                    <td class="text-gray-600">{{ $payment->method->label() }}</td>
                                    <td class="text-right font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-sm">Belum ada pembayaran</p>
            @endif
        </div>
    </div>
    @endsection
</x-customer-layout>
