@extends('layouts.admin')
@section('title', 'Profile PPPoE')
@section('header', 'Profile PPPoE /ppp profile')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-6 h-fit">
        <h3 class="font-semibold text-gray-800 mb-4">Tambah Profil Baru</h3>
        <form action="{{ route('admin.pppoe.profiles.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium">Router *</label>
                <select name="router_id" required class="mt-1 w-full border rounded px-3 py-2 text-sm bg-white">
                    @foreach($routers as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Nama Profil *</label>
                <input type="text" name="name" placeholder="e.g. 10M-PPPoE" required class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">Rate Limit</label>
                <input type="text" name="rate_limit" placeholder="10M/10M" class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium">DNS Server</label>
                <input type="text" name="dns_server" placeholder="8.8.8.8,1.1.1.1" class="mt-1 w-full border rounded px-3 py-2 text-sm">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded text-sm hover:bg-blue-700">Simpan Profil</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-800">Daftar Profil</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Router</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rate Limit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">DNS</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($profiles as $p)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $p->name }}</td>
                            <td class="px-4 py-3 text-sm">{{ $p->router->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $p->rate_limit ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $p->dns_server ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada profil.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $profiles->links() }}</div>
    </div>
</div>
@endsection
