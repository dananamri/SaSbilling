@extends('layouts.admin')
    @section('title', 'Paket')
    @section('header', 'Data Paket')

    @section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="font-semibold">Daftar Paket Internet</h3>
            <a href="{{ route('admin.packages.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">+ Tambah Paket</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Kecepatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($packages as $package)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $package->name }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $package->speed ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $package->customers_count }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $package->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('admin.packages.edit', $package) }}" class="text-green-600 hover:underline mr-2">Edit</a>
                                <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" class="inline">
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
            {{ $packages->links() }}
        </div>
    </div>
    @endsection
