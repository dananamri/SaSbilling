@extends('layouts.admin')
    @section('title', 'Edit Member Hotspot')
    @section('header', 'Edit Member Hotspot')

    @section('content')
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.hotspot.members.update', $member) }}">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                    <input type="text" name="username" value="{{ old('username', $member->username) }}" class="w-full border rounded px-3 py-2 text-sm @error('username') border-red-500 @endif">
                    @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2 text-sm">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="fullname" value="{{ old('fullname', $member->fullname) }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profil</label>
                    <select name="profile_id" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="">Pilih Profil</option>
                        @foreach($profiles as $profile)
                            <option value="{{ $profile->id }}" {{ old('profile_id', $member->profile_id) == $profile->id ? 'selected' : '' }}>{{ $profile->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="active" {{ old('status', $member->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="suspended" {{ old('status', $member->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="expired" {{ old('status', $member->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kedaluwarsa</label>
                    <input type="date" name="expired_at" value="{{ old('expired_at', $member->expired_at?->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>
            <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-center">Simpan</button>
                <a href="{{ route('admin.hotspot.members.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400 text-center">Batal</a>
            </div>
        </form>
    </div>
    @endsection
