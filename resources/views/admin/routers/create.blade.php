@extends('layouts.admin')
@section('title', 'Tambah Router')
@section('header', 'Tambah Router MikroTik')

@section('content')
<div class="max-w-xl bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.routers.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Router</label>
                <input type="text" name="name" placeholder="e.g. CCR-Core-Pusat" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">IP Host / Domain</label>
                    <input type="text" name="host" placeholder="192.168.88.1 / vpn.myisp.net" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">API Port</label>
                    <input type="number" name="port" value="8728" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">API Username</label>
                    <input type="text" name="username" value="admin" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">API Password</label>
                    <input type="password" name="password" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi / Lokasi</label>
                <input type="text" name="description" placeholder="e.g. POP Tower Barat" class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.routers.index') }}" class="px-4 py-2 text-sm border rounded text-gray-700">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Router</button>
        </div>
    </form>
</div>
@endsection
