@extends('layouts.admin')
@section('title', 'OLT Network')
@section('header', 'OLT Network & Status Optical ONU')

@section('content')
<div class="grid grid-cols-1 gap-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b flex justify-between items-center">
            <div>
                <h3 class="font-semibold text-gray-800">Daftar OLT (Optical Line Terminal)</h3>
                <p class="text-xs text-gray-500">ZTE, Huawei, VSOL, HIOSO via SNMP / Telnet.</p>
            </div>
            <a href="{{ route('admin.olts.create') }}" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-700">+ Tambah OLT</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama OLT</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Brand</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Host IP</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($olts as $o)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $o->name }}</td>
                            <td class="px-4 py-3 text-sm uppercase font-semibold text-gray-600">{{ $o->brand }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $o->host }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-0.5 text-xs rounded bg-green-100 text-green-800">Online</span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <form method="POST" action="{{ route('admin.olts.poll', $o) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:underline">Poll Status</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada perangkat OLT didaftarkan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
