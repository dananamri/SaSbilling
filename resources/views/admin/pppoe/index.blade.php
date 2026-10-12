@extends('layouts.admin')
@section('title', 'PPPoE Management')
@section('header', 'PPPoE Secrets & Akun Pelanggan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <div>
            <h3 class="font-semibold text-gray-800">Daftar Akun PPPoE</h3>
            <p class="text-xs text-gray-500">Manajemen secret PPPoE yang tersambung ke router MikroTik.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pppoe.profiles') }}" class="bg-gray-100 text-gray-700 px-3 py-2 rounded text-sm hover:bg-gray-200">Profil PPPoE</a>
            <a href="{{ route('admin.pppoe.create') }}" class="bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700">+ Tambah Secret</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Router</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profil</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($secrets as $s)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $s->username }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $s->router->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-0.5 text-xs rounded bg-purple-100 text-purple-800">{{ $s->profile->name ?? 'default' }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $s->customer->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-0.5 text-xs rounded {{ $s->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $s->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <form method="POST" action="{{ route('admin.pppoe.destroy', $s) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Hapus secret ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500 text-sm">Belum ada secret PPPoE.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t">
        {{ $secrets->links() }}
    </div>
</div>
@endsection
