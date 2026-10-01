@extends('layouts.admin')
    @section('title', 'Catat Pembayaran')
    @section('header', 'Catat Pembayaran')

    @section('content')
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.payments.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice *</label>
                    <select name="invoice_id" class="w-full border rounded px-3 py-2 text-sm @error('invoice_id') border-red-500 @endif">
                        <option value="">Pilih Invoice</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                {{ $invoice->invoice_number }} - {{ $invoice->customer->name }} - Sisa: Rp {{ number_format($invoice->remaining(), 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp) *</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" min="1" class="w-full border rounded px-3 py-2 text-sm @error('amount') border-red-500 @endif">
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran *</label>
                    <select name="method" class="w-full border rounded px-3 py-2 text-sm @error('method') border-red-500 @endif">
                        <option value="">Pilih Metode</option>
                        @foreach($methods as $method)
                            <option value="{{ $method->value }}" {{ old('method') == $method->value ? 'selected' : '' }}>{{ $method->label() }}</option>
                        @endforeach
                    </select>
                    @error('method')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pembayar</label>
                    <input type="text" name="paid_by" value="{{ old('paid_by') }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Referensi</label>
                    <input type="text" name="reference" value="{{ old('reference') }}" placeholder="No. referensi transfer/transaksi" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-center">Simpan</button>
                <a href="{{ route('admin.payments.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400 text-center">Batal</a>
            </div>
        </form>
    </div>
    @endsection
