@extends('layouts.admin')
@section('title', 'Tambah Tenant')
@section('header', 'Tambah Cabang / Tenant Baru')

@section('content')
<div class="max-w-xl bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form action="{{ route('admin.tenants.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Cabang *</label>
                    <input type="text" name="name" placeholder="e.g. SATAK Cabang Barat" value="{{ old('name') }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Unik *</label>
                    <input type="text" name="code" placeholder="e.g. CBG-BARAT-01" value="{{ old('code') }}" required class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-100 focus:border-blue-500 @error('code') border-red-500 @enderror">
                    @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Perusahaan / Legalitas</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</label>
                <textarea name="address" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-500">{{ old('address') }}</textarea>
            </div>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-700 shadow-sm transition">Simpan Tenant</button>
            <a href="{{ route('admin.tenants.index') }}" class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Batal</a>
        </div>
    </form>
</div>
@endsection
