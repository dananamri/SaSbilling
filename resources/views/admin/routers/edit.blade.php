@extends('layouts.admin')
@section('title', 'Edit Router')
@section('header', 'Edit Router MikroTik')

@section('content')
<div class="max-w-xl bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.routers.update', $router) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Router</label>
                <input type="text" name="name" value="{{ old('name', $router->name) }}" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">IP Host / Domain</label>
                    <input type="text" name="host" value="{{ old('host', $router->host) }}" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">API Port</label>
                    <input type="number" name="port" value="{{ old('port', $router->port) }}" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">API Username</label>
                    <input type="text" name="username" value="{{ old('username', $router->username) }}" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password Baru (opsional)</label>
                    <input type="password" name="password" placeholder="Biarkan kosong jika tetap" class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi / Lokasi</label>
                <input type="text" name="description" value="{{ old('description', $router->description) }}" class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" @checked($router->is_active) class="rounded border-gray-300 text-blue-600">
                <label for="is_active" class="ml-2 text-sm text-gray-700">Router Aktif</label>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.routers.index') }}" class="px-4 py-2 text-sm border rounded text-gray-700">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Perbarui Router</button>
        </div>
    </form>
</div>
@endsection
