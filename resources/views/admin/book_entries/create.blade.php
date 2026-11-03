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

            <div x-data="{
                open: false,
                selected: '{{ old('category') }}',
                categories: [
                    'Operasional Kantor',
                    'Biaya Bandwidth / Backbone',
                    'Maintenance & Alat Lapangan',
                    'Gaji & Bonus',
                    'Pendapatan Tambahan'
                ]
            }" class="relative">
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <div class="relative mt-1">
                    <input type="text"
                           name="category"
                           x-model="selected"
                           @focus="open = true"
                           @click.outside="open = false"
                           placeholder="Pilih atau ketik kategori..."
                           required
                           autocomplete="off"
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button"
                            @click="open = !open"
                            class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
                <div x-show="open"
                     x-transition
                     class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded shadow-lg max-h-48 overflow-y-auto"
                     style="display: none;">
                    <template x-for="cat in categories" :key="cat">
                        <div @click="selected = cat; open = false"
                             class="px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 cursor-pointer border-b border-gray-50 last:border-0"
                             x-text="cat">
                        </div>
                    </template>
                </div>
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
