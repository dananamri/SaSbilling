@extends('layouts.admin')
@section('title', 'Kelola Tenant')
@section('header', 'Kelola Tenant / Cabang ISP')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-4 sm:p-5 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="font-bold text-gray-900">Daftar Cabang / Tenant</h3>
            <p class="text-xs text-gray-500 mt-0.5">Master data cabang atau unit operasional ISP multi-layanan.</p>
        </div>
        <a href="{{ route('admin.tenants.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition shadow-sm">+ Tambah Tenant</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama & Kode</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Perusahaan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($tenants as $t)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-5 py-4 text-sm">
                            <div class="font-bold text-gray-900">{{ $t->name }}</div>
                            <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $t->code }}</div>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600">{{ $t->company_name ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm text-gray-600">
                            <div>{{ $t->phone ?? '-' }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $t->email ?? '' }}</div>
                        </td>
                        <td class="px-5 py-4 text-sm">
                            <span class="px-2.5 py-1 text-xs rounded-full font-semibold {{ $t->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                {{ $t->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-right space-x-2">
                            <a href="{{ route('admin.tenants.edit', $t) }}" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 px-2.5 py-1 rounded-lg transition">Edit</a>
                            <form method="POST" action="{{ route('admin.tenants.destroy', $t) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center text-xs font-semibold text-red-600 hover:text-red-800 bg-red-50 px-2.5 py-1 rounded-lg transition" onclick="return confirm('Hapus tenant ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500">Belum ada cabang/tenant didaftarkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-200 bg-gray-50">{{ $tenants->links() }}</div>
</div>
@endsection
