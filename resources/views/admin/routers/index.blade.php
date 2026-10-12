@extends('layouts.admin')
@section('title', 'Monitoring MikroTik')
@section('header', 'Monitoring Router MikroTik')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <div>
            <h3 class="font-semibold text-gray-800">Daftar Router MikroTik</h3>
            <p class="text-xs text-gray-500">Koneksi RouterOS API untuk memonitoring resource, PPPoE, dan queue bandwidth.</p>
        </div>
        <a href="{{ route('admin.routers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-center whitespace-nowrap">+ Tambah Router</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Router</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Host / Port API</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status & Resource</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($routers as $r)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                            {{ $r->name }}
                            <div class="text-xs text-gray-400">{{ $r->description ?? 'Router Gateway' }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $r->host }}:{{ $r->port }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $r->username }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($r->last_status && ($r->last_status['online'] ?? false))
                                <span class="px-2 py-0.5 text-xs rounded bg-green-100 text-green-800">Online</span>
                                <div class="text-xs text-gray-500 mt-1">CPU: {{ $r->last_status['cpu_load'] ?? '-' }} | Free: {{ $r->last_status['free_memory'] ?? '-' }}</div>
                            @else
                                <span class="px-2 py-0.5 text-xs rounded bg-gray-100 text-gray-600">Belum Terhubung</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <form method="POST" action="{{ route('admin.routers.ping', $r) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:underline mr-3">Tes Koneksi</button>
                            </form>
                            <a href="{{ route('admin.routers.edit', $r) }}" class="text-green-600 hover:underline mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.routers.destroy', $r) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Hapus router?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500 text-sm">Belum ada router MikroTik didaftarkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t">
        {{ $routers->links() }}
    </div>
</div>
@endsection
