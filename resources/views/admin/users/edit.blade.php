@extends('layouts.admin')
@section('title', 'Edit User')
@section('header', 'Edit Data Staf')

@section('content')
<div class="max-w-2xl bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role / Hak Akses</label>
                    <select name="role" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm bg-white">
                        @foreach($roles as $role)
                            <option value="{{ $role->value }}" @selected($user->role->value === $role->value)>{{ $role->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password Baru (kosongkan jika tidak diganti)</label>
                    <input type="password" name="password" class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" @checked($user->is_active) class="rounded border-gray-300 text-blue-600">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Akun Aktif</label>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Perbarui User</button>
        </div>
    </form>
</div>
@endsection
