@extends('layouts.admin')
@section('title', 'Aktivitas Terbaru')
@section('header', 'Log Aktivitas Terbaru (Audit Trail)')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b">
        <h3 class="font-semibold text-gray-800">Jejak Aktivitas Pengguna Sistem</h3>
        <p class="text-xs text-gray-500">Rekam jejak setiap perubahan penting yang dibuat oleh staf (pembuatan faktur, aktivasi user, penambahan pelanggan, dsb).</p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pengguna</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modul</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($logs as $log)
                    <tr>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $log->user_name }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-0.5 text-xs rounded bg-gray-100 text-gray-700 font-mono">{{ $log->module }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="capitalize font-semibold text-gray-800">{{ $log->action }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $log->description }}</td>
                        <td class="px-4 py-3 text-xs text-gray-400 font-mono">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada aktivitas tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t">{{ $logs->links() }}</div>
</div>
@endsection
