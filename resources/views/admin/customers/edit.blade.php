@extends('layouts.admin')
@section('title', 'Edit Pelanggan')
@section('header', 'Edit Pelanggan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 max-w-3xl" x-data="{ photoPreview: null }">
    <form method="POST" action="{{ route('admin.customers.update', $customer) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Foto Profil -->
        <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Profil</label>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-white border-2 border-gray-200 flex items-center justify-center overflow-hidden shrink-0 shadow-inner">
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!photoPreview">
                        @if($customer->photo)
                            <img src="{{ asset('storage/' . $customer->photo) }}" alt="Foto Profil" class="w-full h-full object-cover">
                        @else
                            <span class="text-xl text-gray-400 font-bold">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                        @endif
                    </template>
                </div>
                <div class="flex-1">
                    <input type="file" name="photo" accept="image/*"
                        @change="
                            const file = $event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = (e) => { photoPreview = e.target.result; };
                                reader.readAsDataURL(file);
                            }
                        "
                        class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border rounded-lg bg-white">
                    @error('photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG • Maksimal 2MB</p>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                <input type="text" name="username" value="{{ old('username', $customer->username) }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('username') border-red-500 @enderror">
                @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon *</label>
                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('password') border-red-500 @enderror">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Paket Layanan *</label>
                <select name="package_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('package_id') border-red-500 @enderror">
                    <option value="">Pilih Paket</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ old('package_id', $customer->package_id) == $package->id ? 'selected' : '' }}>{{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
                @error('package_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hari Siklus Billing (1-28) *</label>
                <input type="number" name="billing_day" value="{{ old('billing_day', $customer->billing_day) }}" min="1" max="28" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('billing_day') border-red-500 @enderror">
                @error('billing_day')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bergabung</label>
                <input type="date" name="joined_at" value="{{ old('joined_at', $customer->joined_at?->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
            </div>
            @if(auth()->user()->isSuperAdmin())
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cabang (Tenant) *</label>
                <select name="tenant_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('tenant_id') border-red-500 @enderror" required>
                    <option value="">Pilih Cabang</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ old('tenant_id', $customer->tenant_id) == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }} ({{ $tenant->code }})</option>
                    @endforeach
                </select>
                @error('tenant_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            @endif
            <div class="col-span-1 sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pemasangan</label>
                <textarea name="address" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">{{ old('address', $customer->address) }}</textarea>
            </div>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-700 shadow-sm transition">Simpan Perubahan</button>
            <a href="{{ route('admin.customers.index') }}" class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Batal</a>
        </div>
    </form>
</div>
@endsection
