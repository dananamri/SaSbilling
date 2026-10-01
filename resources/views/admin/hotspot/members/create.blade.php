@extends('layouts.admin')
    @section('title', 'Tambah Member Hotspot')
    @section('header', 'Tambah Member Hotspot')

    @section('content')
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.hotspot.members.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                    <input type="text" name="username" value="{{ old('username') }}" class="w-full border rounded px-3 py-2 text-sm @error('username') border-red-500 @endif">
                    @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2 text-sm @error('password') border-red-500 @endif">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="fullname" value="{{ old('fullname') }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profil</label>
                    <select name="profile_id" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="">Pilih Profil</option>
                        @foreach($profiles as $profile)
                            <option value="{{ $profile->id }}" {{ old('profile_id') == $profile->id ? 'selected' : '' }}>{{ $profile->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kedaluwarsa</label>
                    <input type="date" name="expired_at" value="{{ old('expired_at') }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>
            <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-center">Simpan</button>
                <a href="{{ route('admin.hotspot.members.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400 text-center">Batal</a>
            </div>
        </form>
    </div>
    @endsection
