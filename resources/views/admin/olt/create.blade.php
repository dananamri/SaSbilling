@extends('layouts.admin')
@section('title', 'Tambah OLT')
@section('header', 'Tambah OLT Baru')

@section('content')
<div class="max-w-xl bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.olts.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Nama OLT *</label>
                <input type="text" name="name" placeholder="e.g. OLT-Pusat-ZTE" required class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Brand *</label>
                    <select name="brand" required class="mt-1 w-full border rounded px-3 py-2 text-sm bg-white">
                        <option value="zte">ZTE</option>
                        <option value="huawei">Huawei</option>
                        <option value="vsol">VSOL</option>
                        <option value="hioso">HIOSO</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">IP Management *</label>
                    <input type="text" name="host" placeholder="192.168.10.1" required class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">SNMP Community</label>
                <input type="text" name="snmp_community" value="public" class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">Lokasi Fisik</label>
                <input type="text" name="location" placeholder="e.g. Gedung Kantor Pusat" class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.olt.index') }}" class="px-4 py-2 text-sm border rounded">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Simpan OLT</button>
        </div>
    </form>
</div>
@endsection
