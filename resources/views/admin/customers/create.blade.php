@extends('layouts.admin')
    @section('title', 'Tambah Pelanggan')
    @section('header', 'Tambah Pelanggan')

    @section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.customers.store') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2 text-sm @error('name') border-red-500 @endif">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                    <input type="text" name="username" value="{{ old('username') }}" class="w-full border rounded px-3 py-2 text-sm @error('username') border-red-500 @endif">
                    @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded px-3 py-2 text-sm @error('phone') border-red-500 @endif">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                    <input type="password" name="password" value="{{ old('password') }}" class="w-full border rounded px-3 py-2 text-sm @error('password') border-red-500 @endif">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Paket *</label>
                    <select name="package_id" class="w-full border rounded px-3 py-2 text-sm @error('package_id') border-red-500 @endif">
                        <option value="">Pilih Paket</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>{{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                    @error('package_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hari Billing *</label>
                    <input type="number" name="billing_day" value="{{ old('billing_day', 1) }}" min="1" max="28" class="w-full border rounded px-3 py-2 text-sm @error('billing_day') border-red-500 @endif">
                    @error('billing_day')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bergabung</label>
                    <input type="date" name="joined_at" value="{{ old('joined_at') }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="address" rows="3" class="w-full border rounded px-3 py-2 text-sm">{{ old('address') }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Simpan</button>
                <a href="{{ route('admin.customers.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">Batal</a>
            </div>
        </form>
    </div>
    @endsection
