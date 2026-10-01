@extends('layouts.admin')
    @section('title', 'Edit Pelanggan')
    @section('header', 'Edit Pelanggan')

    @section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="w-full border rounded px-3 py-2 text-sm @error('name') border-red-500 @endif">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon *</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full border rounded px-3 py-2 text-sm @error('phone') border-red-500 @endif">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Paket *</label>
                    <select name="package_id" class="w-full border rounded px-3 py-2 text-sm @error('package_id') border-red-500 @endif">
                        <option value="">Pilih Paket</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_id', $customer->package_id) == $package->id ? 'selected' : '' }}>{{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                    @error('package_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hari Billing *</label>
                    <input type="number" name="billing_day" value="{{ old('billing_day', $customer->billing_day) }}" min="1" max="28" class="w-full border rounded px-3 py-2 text-sm @error('billing_day') border-red-500 @endif">
                    @error('billing_day')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bergabung</label>
                    <input type="date" name="joined_at" value="{{ old('joined_at', $customer->joined_at?->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="address" rows="3" class="w-full border rounded px-3 py-2 text-sm">{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Simpan</button>
                <a href="{{ route('admin.customers.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">Batal</a>
            </div>
        </form>
    </div>
    @endsection
