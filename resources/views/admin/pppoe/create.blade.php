@extends('layouts.admin')
@section('title', 'Tambah PPPoE')
@section('header', 'Tambah Akun PPPoE')

@section('content')
<div class="max-w-xl bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.pppoe.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Router MikroTik *</label>
                <select name="router_id" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm bg-white">
                    <option value="">-- Pilih Router --</option>
                    @foreach($routers as $r)
                        <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->host }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username PPPoE *</label>
                    <input type="text" name="username" required class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password *</label>
                    <input type="text" name="password" required class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Profile PPPoE</label>
                <select name="pppoe_profile_id" class="mt-1 w-full border rounded px-3 py-2 text-sm bg-white">
                    <option value="">-- Default --</option>
                    @foreach($profiles as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->router->name ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Pelanggan (opsional)</label>
                <select name="customer_id" class="mt-1 w-full border rounded px-3 py-2 text-sm bg-white">
                    <option value="">-- Tanpa Pelanggan --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->phone }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Local Address</label>
                    <input type="text" name="local_address" placeholder="10.0.0.1" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Remote Address</label>
                    <input type="text" name="remote_address" placeholder="pool-pppoe" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Komentar</label>
                <input type="text" name="comment" class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.pppoe.index') }}" class="px-4 py-2 text-sm border rounded">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Simpan Secret</button>
        </div>
    </form>
</div>
@endsection
