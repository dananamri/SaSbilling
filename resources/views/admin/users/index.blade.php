@extends('layouts.admin')
@section('title', 'Users & Staff')
@section('header', 'Manajemen Pengguna & Staf')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <div>
            <h3 class="font-semibold text-gray-800">Daftar Staf & Hak Akses</h3>
            <p class="text-xs text-gray-500">Kelola pengguna yang memiliki akses ke panel admin.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-center whitespace-nowrap">+ Tambah User</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username / Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. HP</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <div>{{ $user->username }}</div>
                            <div class="text-xs text-gray-400">{{ $user->email }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                {{ $user->role instanceof \BackedEnum ? $user->role->label() : ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $user->phone ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-0.5 text-xs rounded {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin hapus user ini?')">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada user staf.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t">
        {{ $users->links() }}
    </div>
</div>
@endsection
