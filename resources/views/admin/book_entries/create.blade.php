@extends('layouts.admin')
@section('title', 'Tambah Transaksi')
@section('header', 'Catat Kas Masuk / Keluar')

@section('content')
<div class="max-w-xl bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.book-entries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
                <div class="mt-2 flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="income" checked class="text-green-600">
                        <span class="ml-2 text-sm text-gray-700">Pemasukan (Income)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="expense" class="text-red-600">
                        <span class="ml-2 text-sm text-gray-700">Pengeluaran (Expense)</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <input type="text" name="category" list="categories" placeholder="e.g. Pembelian Router, Sewa Tiang, Bensin, Gaji" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                <datalist id="categories">
                    <option value="Operasional Kantor">
                    <option value="Biaya Bandwidth / Backbone">
                    <option value="Maintenance & Alat Lapangan">
                    <option value="Gaji & Bonus">
                    <option value="Pendapatan Tambahan">
                </datalist>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nominal (Rp)</label>
                    <input type="number" name="amount" min="1" step="any" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                <textarea name="description" rows="3" class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Foto Bukti / Struk (opsional)</label>
                <input type="file" name="receipt" accept="image/*" class="mt-1 w-full text-sm">
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.book-entries.index') }}" class="px-4 py-2 text-sm border rounded text-gray-700">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Transaksi</button>
        </div>
    </form>
</div>
@endsection
