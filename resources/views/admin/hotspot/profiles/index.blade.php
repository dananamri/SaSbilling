@extends('layouts.admin')
    @section('title', 'Profil Hotspot')
    @section('header', 'Profil Hotspot')

    @section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="font-semibold">Daftar Profil Hotspot</h3>
            <a href="{{ route('admin.hotspot.profiles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">+ Tambah Profil</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Kecepatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Masa Berlaku</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Voucher</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Member</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($profiles as $profile)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $profile->name }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $profile->type == 'PPoE' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ $profile->type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">Rp {{ number_format($profile->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm hidden md:table-cell">{{ $profile->speed ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm hidden md:table-cell">{{ $profile->validity ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $profile->vouchers_count }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $profile->members_count }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $profile->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $profile->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('admin.hotspot.profiles.edit', $profile) }}" class="text-green-600 hover:underline mr-2">Edit</a>
                                <form method="POST" action="{{ route('admin.hotspot.profiles.destroy', $profile) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $profiles->links() }}
        </div>
    </div>
    @endsection
