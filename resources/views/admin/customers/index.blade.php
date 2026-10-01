@extends('layouts.admin')
    @section('title', 'Pelanggan')
    @section('header', 'Data Pelanggan')

    @section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 sm:p-6 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <form method="GET" class="flex flex-col sm:flex-row gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/telepon..." class="border rounded px-3 py-2 text-sm">
                <select name="status" class="border rounded px-3 py-2 text-sm">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="isolated" {{ request('status') == 'isolated' ? 'selected' : '' }}>Isolir</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="bg-slate-600 text-white px-4 py-2 rounded text-sm">Filter</button>
            </form>
            <a href="{{ route('admin.customers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-center">+ Tambah Pelanggan</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Telepon</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Paket</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tunggakan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($customers as $customer)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $customer->name }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $customer->phone }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $customer->package->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $customer->status->value == 'active' ? 'bg-green-100 text-green-800' : ($customer->status->value == 'isolated' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($customer->status->value) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell {{ $customer->totalOutstanding() > 0 ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                Rp {{ number_format($customer->totalOutstanding(), 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-0">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 hover:underline sm:mr-2">Lihat</a>
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="text-green-600 hover:underline sm:mr-2">Edit</a>
                                    <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:p-6 border-t">
            {{ $customers->withQueryString()->links() }}
        </div>
    </div>
    @endsection
