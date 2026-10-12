@extends('layouts.admin')
@section('title', 'RADIUS Mobile')
@section('header', 'Server RADIUS / FreeRADIUS')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6 h-fit">
        <h3 class="font-semibold text-gray-800 mb-4">Tambah Server RADIUS</h3>
        <form action="{{ route('admin.radius.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium">Nama Server *</label>
                <input type="text" name="name" placeholder="e.g. Radius-Primary" required class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">Host / IP *</label>
                <input type="text" name="host" placeholder="127.0.0.1" required class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium">Auth Port</label>
                    <input type="number" name="auth_port" value="1812" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium">Acct Port</label>
                    <input type="number" name="acct_port" value="1813" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium">Shared Secret *</label>
                <input type="text" name="secret" required class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded text-sm hover:bg-blue-700">Simpan Server</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-800">Daftar Server RADIUS</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Host</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ports</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($servers as $s)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $s->name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $s->host }}</td>
                            <td class="px-4 py-3 text-sm text-xs">{{ $s->auth_port }} / {{ $s->acct_port }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-0.5 text-xs rounded bg-green-100 text-green-800">Aktif</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada server.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
