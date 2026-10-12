@extends('layouts.admin')
@section('title', 'Buat Setoran Kasir')
@section('header', 'Buat Setoran Kasir')

@section('content')
<div class="max-w-xl bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.cash-deposits.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah Setoran (Rp) *</label>
                <input type="number" name="amount" min="1" step="any" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Setoran *</label>
                <input type="date" name="deposit_date" value="{{ date('Y-m-d') }}" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan / Keterangan</label>
                <textarea name="notes" rows="3" placeholder="Dari tagihan pelanggan A, B, C ..." class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Bukti Transfer / Foto Fisik Uang (opsional)</label>
                <input type="file" name="proof" accept="image/*" class="mt-1 w-full text-sm">
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.cash-deposits.index') }}" class="px-4 py-2 text-sm border rounded text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Kirim Setoran</button>
        </div>
    </form>
</div>
@endsection
