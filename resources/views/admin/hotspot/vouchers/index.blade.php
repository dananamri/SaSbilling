@extends('layouts.admin')
    @section('title', 'Voucher Hotspot')
    @section('header', 'Daftar Voucher Hotspot')

    @section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b flex justify-between items-center">
            <form method="GET" class="flex space-x-2">
                <select name="status" class="border rounded px-3 py-2 text-sm">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Terpakai</option>
                </select>
                <select name="profile_id" class="border rounded px-3 py-2 text-sm">
                    <option value="">Semua Profil</option>
                    @foreach($profiles as $profile)
                        <option value="{{ $profile->id }}" {{ request('profile_id') == $profile->id ? 'selected' : '' }}>{{ $profile->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-slate-600 text-white px-4 py-2 rounded text-sm">Filter</button>
            </form>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.hotspot.vouchers.print-all', request()->query()) }}" target="_blank" class="bg-emerald-600 text-white px-4 py-2 rounded text-sm hover:bg-emerald-700 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Semua Voucher
                </a>
                <a href="{{ route('admin.hotspot.vouchers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">+ Buat Voucher</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Profil</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Terpakai Oleh</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($vouchers as $voucher)
                        <tr>
                            <td class="px-4 py-3 text-sm font-mono font-medium">{{ $voucher->code }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $voucher->profile->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $voucher->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $voucher->status == 'available' ? 'Tersedia' : 'Terpakai' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $voucher->member->username ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $voucher->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($voucher->status == 'available')
                                    <a href="{{ route('admin.hotspot.vouchers.print', $voucher) }}" class="text-blue-600 hover:underline mr-2">Cetak</a>
                                    <form method="POST" action="{{ route('admin.hotspot.vouchers.destroy', $voucher) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $vouchers->withQueryString()->links() }}
        </div>
    </div>
    @endsection
