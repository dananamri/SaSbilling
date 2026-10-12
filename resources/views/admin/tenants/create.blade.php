@extends('layouts.admin')
@section('title', 'Tambah Tenant')
@section('header', 'Tambah Cabang / Tenant Baru')

@section('content')
<div class="max-w-xl bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.tenants.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Nama Cabang *</label>
                    <input type="text" name="name" placeholder="e.g. SATAK-Cabang Barat" required class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium">Kode Unik *</label>
                    <input type="text" name="code" placeholder="e.g. CBG-BARAT-01" required class="mt-1 w-full border rounded px-3 py-2 text-sm font-mono">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">Nama Perusahaan / Legalitas</label>
                <input type="text" name="company_name" class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">No. Telepon</label>
                    <input type="text" name="phone" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">Alamat Lengkap</label>
                <textarea name="address" rows="3" class="mt-1 w-full border rounded px-3 py-2 text-sm"></textarea>
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.tenants.index') }}" class="px-4 py-2 text-sm border rounded">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Tenant</button>
        </div>
    </form>
</div>
@endsection
