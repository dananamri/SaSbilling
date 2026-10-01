@extends('layouts.admin')
    @section('title', 'Pembayaran')
    @section('header', 'Data Pembayaran')

    @section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b flex justify-between items-center">
            <form method="GET" class="flex space-x-2">
                <select name="method" class="border rounded px-3 py-2 text-sm">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                    <option value="transfer_bca" {{ request('method') == 'transfer_bca' ? 'selected' : '' }}>Transfer BCA</option>
                    <option value="transfer_mandiri" {{ request('method') == 'transfer_mandiri' ? 'selected' : '' }}>Transfer Mandiri</option>
                    <option value="transfer_bni" {{ request('method') == 'transfer_bni' ? 'selected' : '' }}>Transfer BNI</option>
                    <option value="transfer_bri" {{ request('method') == 'transfer_bri' ? 'selected' : '' }}>Transfer BRI</option>
                    <option value="qris" {{ request('method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="dana" {{ request('method') == 'dana' ? 'selected' : '' }}>DANA</option>
                    <option value="gopay" {{ request('method') == 'gopay' ? 'selected' : '' }}>GoPay</option>
                    <option value="ovo" {{ request('method') == 'ovo' ? 'selected' : '' }}>OVO</option>
                </select>
                <button type="submit" class="bg-slate-600 text-white px-4 py-2 rounded text-sm">Filter</button>
            </form>
            <a href="{{ route('admin.payments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">+ Catat Pembayaran</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Metode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Pembayar</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($payments as $payment)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $payment->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $payment->invoice->invoice_number }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $payment->invoice->customer->name }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $payment->method->label() }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $payment->paid_by ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-right font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $payments->withQueryString()->links() }}
        </div>
    </div>
    @endsection
