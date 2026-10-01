@extends('layouts.admin')
    @section('title', 'Tambah Paket')
    @section('header', 'Tambah Paket')

    @section('content')
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.packages.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Paket *</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2 text-sm @error('name') border-red-500 @endif">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe *</label>
                    <select name="type" class="w-full border rounded px-3 py-2 text-sm @error('type') border-red-500 @endif">
                        <option value="PPoE" {{ old('type') == 'PPoE' ? 'selected' : '' }}>PPoE</option>
                        <option value="Hotspot" {{ old('type') == 'Hotspot' ? 'selected' : '' }}>Hotspot</option>
                    </select>
                    @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kecepatan</label>
                    <input type="text" name="speed" value="{{ old('speed') }}" placeholder="contoh: 50 Mbps" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" class="w-full border rounded px-3 py-2 text-sm @error('price') border-red-500 @endif">
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 text-sm">{{ old('description') }}</textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <label class="text-sm text-gray-700">Aktif</label>
                </div>
            </div>
            <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-center">Simpan</button>
                <a href="{{ route('admin.packages.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400 text-center">Batal</a>
            </div>
        </form>
    </div>
    @endsection
