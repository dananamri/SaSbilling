<x-customer-layout>
    @section('title', 'Pembayaran')
    @section('header', 'Riwayat Pembayaran')

    @section('content')
    <div class="bg-white rounded-2xl border border-gray-200">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Semua Pembayaran</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Metode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Pembayar</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($payments as $payment)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $payment->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $payment->invoice->invoice_number }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $payment->method->label() }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $payment->paid_by ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>
    @endsection
</x-customer-layout>
