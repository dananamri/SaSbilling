@extends('layouts.admin')
    @section('title', 'Member Hotspot')
    @section('header', 'Member Hotspot')

    @section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b flex justify-between items-center">
            <form method="GET" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari username/nama..." class="border rounded px-3 py-2 text-sm">
                <select name="status" class="border rounded px-3 py-2 text-sm">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                <button type="submit" class="bg-slate-600 text-white px-4 py-2 rounded text-sm">Filter</button>
            </form>
            <a href="{{ route('admin.hotspot.members.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">+ Tambah Member</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Telepon</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Profil</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Kedaluwarsa</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($members as $member)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $member->username }}</td>
                            <td class="px-4 py-3 text-sm">{{ $member->fullname ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $member->phone ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $member->profile->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $member->status == 'active' ? 'bg-green-100 text-green-800' : ($member->status == 'suspended' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $member->expired_at?->format('d M Y') ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('admin.hotspot.members.edit', $member) }}" class="text-green-600 hover:underline mr-2">Edit</a>
                                <form method="POST" action="{{ route('admin.hotspot.members.destroy', $member) }}" class="inline">
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
            {{ $members->withQueryString()->links() }}
        </div>
    </div>
    @endsection
