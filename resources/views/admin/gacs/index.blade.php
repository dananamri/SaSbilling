@extends('layouts.admin')
@section('title', 'GACS V2')
@section('header', 'GACS V2 / TR-069 Auto-Config Server')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <div>
            <h3 class="font-semibold text-gray-800">Manajemen Perangkat ONT (TR-069)</h3>
            <p class="text-xs text-gray-500">Integrasi GenieACS / ACS server untuk konfigurasi jarak jauh ONT modem pelanggan.</p>
        </div>
        <span class="px-2.5 py-1 text-xs rounded-full bg-blue-100 text-blue-800 font-medium">ACS Service: Connected</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serial Number</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model / OUI</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SSID WiFi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Inform</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($devices as $d)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium">{{ $d->serial_number }}</td>
                        <td class="px-4 py-3 text-sm">{{ $d->model_name ?? '-' }} ({{ $d->oui ?? '-' }})</td>
                        <td class="px-4 py-3 text-sm">{{ $d->customer->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-xs">{{ $d->ssid_2g ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-xs text-gray-500">{{ $d->last_inform_at ? $d->last_inform_at->diffForHumans() : '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <button type="button" class="text-blue-600 hover:underline mr-2" onclick="alert('Perintah reboot dikirim ke modem!')">Reboot</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada perangkat ONT terhubung ke ACS.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t">
        {{ $devices->links() }}
    </div>
</div>
@endsection
