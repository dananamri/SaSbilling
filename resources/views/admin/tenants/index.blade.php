@extends('layouts.admin')
@section('title', 'Kelola Tenant')
@section('header', 'Kelola Tenant / Cabang ISP')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <div>
            <h3 class="font-semibold text-gray-800">Daftar Cabang / Tenant</h3>
            <p class="text-xs text-gray-500">Master data cabang atau unit operasional ISP multi-layanan.</p>
        </div>
        <a href="{{ route('admin.tenants.create') }}" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-700">+ Tambah Tenant</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama / Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($tenants as $t)
                    <tr>
                        <td class="px-4 py-3 text-sm">
                            <div class="font-medium">{{ $t->name }}</div>
                            <div class="text-xs text-gray-400 font-mono">{{ $t->code }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $t->company_name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $t->phone ?? '-' }}<div class="text-xs text-gray-400">{{ $t->email ?? '' }}</div></td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-0.5 text-xs rounded {{ $t->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $t->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <form method="POST" action="{{ route('admin.tenants.destroy', $t) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Hapus tenant ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada cabang/tenant didaftarkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t">{{ $tenants->links() }}</div>
</div>
@endsection
