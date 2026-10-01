@extends('layouts.admin')
    @section('title', 'Detail Pembayaran')
    @section('header', 'Detail Pembayaran')

    @section('content')
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-2xl">
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Invoice</dt><dd class="font-medium">{{ $payment->invoice->invoice_number }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Pelanggan</dt><dd>{{ $payment->invoice->customer->name }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Jumlah</dt><dd class="font-medium text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Metode</dt><dd>{{ $payment->method->label() }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Pembayar</dt><dd>{{ $payment->paid_by ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Referensi</dt><dd>{{ $payment->reference ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Catatan</dt><dd>{{ $payment->notes ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Tanggal</dt><dd>{{ $payment->created_at->format('d M Y H:i') }}</dd></div>
        </dl>
        <div class="mt-6">
            <a href="{{ route('admin.payments.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">Kembali</a>
        </div>
    </div>
    @endsection
