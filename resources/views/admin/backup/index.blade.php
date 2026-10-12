@extends('layouts.admin')
@section('title', 'Backup Sistem')
@section('header', 'Backup Database & Konfigurasi')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-4xl">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 mb-6 border-b gap-3">
        <div>
            <h3 class="font-semibold text-gray-800">Cadangan Database & Konfigurasi Router</h3>
            <p class="text-xs text-gray-500">Buat cadangan database secara instan dan unduh arsip untuk keamanan pemulihan bencana.</p>
        </div>
        <form method="POST" action="{{ route('admin.backup.run') }}">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Buat Backup Sekarang
            </button>
        </form>
    </div>

    <h4 class="font-semibold text-gray-700 text-sm mb-3">Arsip File Backup Tersedia</h4>
    <div class="overflow-x-auto border rounded-lg">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama File</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ukuran</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Dibuat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($files as $f)
                    <tr>
                        <td class="px-4 py-3 text-sm font-mono text-gray-800">{{ $f['name'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $f['size'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $f['created_at'] }}</td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin.backup.download', $f['name']) }}" class="text-blue-600 hover:underline">Unduh File</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada file backup tersimpan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
