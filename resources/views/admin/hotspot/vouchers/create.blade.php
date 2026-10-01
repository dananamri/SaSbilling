@extends('layouts.admin')
    @section('title', 'Buat Voucher')
    @section('header', 'Buat Voucher Hotspot')

    @section('content')
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.hotspot.vouchers.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profil *</label>
                    <select name="profile_id" class="w-full border rounded px-3 py-2 text-sm @error('profile_id') border-red-500 @endif">
                        <option value="">Pilih Profil</option>
                        @foreach($profiles as $profile)
                            <option value="{{ $profile->id }}" {{ old('profile_id') == $profile->id ? 'selected' : '' }}>{{ $profile->name }} - Rp {{ number_format($profile->price, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                    @error('profile_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Voucher *</label>
                    <input type="number" name="quantity" value="{{ old('quantity', 10) }}" min="1" max="1000" class="w-full border rounded px-3 py-2 text-sm @error('quantity') border-red-500 @endif">
                    @error('quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prefix Kode</label>
                    <input type="text" name="prefix" value="{{ old('prefix') }}" placeholder="contoh: SAS" class="w-full border rounded px-3 py-2 text-sm">
                    <p class="text-xs text-gray-500 mt-1">Opsional, akan ditambahkan di depan kode voucher</p>
                </div>
            </div>
            <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-center">Buat Voucher</button>
                <a href="{{ route('admin.hotspot.vouchers.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400 text-center">Batal</a>
            </div>
        </form>
    </div>
    @endsection
