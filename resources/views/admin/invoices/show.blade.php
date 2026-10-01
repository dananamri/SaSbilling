@extends('layouts.admin')
    @section('title', 'Detail Invoice')
    @section('header', 'Detail Invoice')

    @section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold mb-4">Informasi Invoice</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">No. Invoice</dt><dd class="font-medium">{{ $invoice->invoice_number }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Pelanggan</dt><dd>{{ $invoice->customer->name }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Periode</dt><dd>{{ $invoice->period }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Jumlah</dt><dd class="font-medium">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Jatuh Tempo</dt><dd>{{ $invoice->due_date->format('d M Y') }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Status</dt>
                    <dd>
                        <span class="px-2 py-1 text-xs rounded-full {{ $invoice->status->value == 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status->value == 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($invoice->status->value) }}
                        </span>
                    </dd>
                </div>
                @if($invoice->paid_at)
                    <div class="flex justify-between"><dt class="text-gray-500">Dibayar pada</dt><dd>{{ $invoice->paid_at->format('d M Y H:i') }}</dd></div>
                @endif
            </dl>
            <div class="mt-4">
                <a href="{{ route('admin.invoices.index') }}" class="bg-gray-300 text-gray-700 px-3 py-1 rounded text-xs hover:bg-gray-400">Kembali</a>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold mb-4">Riwayat Pembayaran</h3>
            @if($invoice->payments->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead><tr class="border-b"><th class="text-left py-2">Tanggal</th><th class="text-left py-2 hidden sm:table-cell">Metode</th><th class="text-left py-2 hidden sm:table-cell">Pembayar</th><th class="text-right py-2">Jumlah</th></tr></thead>
                        <tbody>
                            @foreach($invoice->payments as $payment)
                                <tr class="border-b">
                                    <td class="py-2">{{ $payment->created_at->format('d M Y') }}</td>
                                    <td class="py-2 hidden sm:table-cell">{{ $payment->method->label() }}</td>
                                    <td class="py-2 hidden sm:table-cell">{{ $payment->paid_by ?? '-' }}</td>
                                    <td class="py-2 text-right font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 p-4 bg-gray-50 rounded">
                    <div class="flex justify-between text-sm">
                        <span>Total Dibayar:</span>
                        <span class="font-medium">Rp {{ number_format($invoice->totalPaid(), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span>Sisa:</span>
                        <span class="font-medium {{ $invoice->remaining() > 0 ? 'text-red-600' : 'text-green-600' }}">Rp {{ number_format($invoice->remaining(), 0, ',', '.') }}</span>
                    </div>
                </div>
            @else
                <p class="text-gray-500">Belum ada pembayaran</p>
            @endif
        </div>
    </div>
    @endsection
