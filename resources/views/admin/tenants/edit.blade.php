@extends('layouts.admin')
@section('title', 'Edit Tenant')
@section('header', 'Edit Cabang / Tenant')

@section('content')
<div class="max-w-xl bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Nama Cabang *</label>
                    <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required class="mt-1 w-full border rounded px-3 py-2 text-sm @error('name') border-red-500 @endif">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium">Kode Unik *</label>
                    <input type="text" name="code" value="{{ old('code', $tenant->code) }}" required class="mt-1 w-full border rounded px-3 py-2 text-sm font-mono @error('code') border-red-500 @endif">
                    @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">Nama Perusahaan / Legalitas</label>
                <input type="text" name="company_name" value="{{ old('company_name', $tenant->company_name) }}" class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email', $tenant->email) }}" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">Alamat Lengkap</label>
                <textarea name="address" rows="3" class="mt-1 w-full border rounded px-3 py-2 text-sm">{{ old('address', $tenant->address) }}</textarea>
            </div>
            <div>
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="is_active" {{ old('is_active', $tenant->is_active) ? 'checked' : '' }} class="w-4 h-4 accent-blue-600">
                    Aktif
                </label>
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.tenants.index') }}" class="px-4 py-2 text-sm border rounded">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection