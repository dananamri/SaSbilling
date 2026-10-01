@extends('layouts.admin')
    @section('title', 'Isolir')
    @section('header', 'Manajemen Isolir')

    @section('content')
    <div class="flex justify-end mb-4">
        <form method="POST" action="{{ route('admin.isolir.check') }}">
            @csrf
            <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded text-sm hover:bg-orange-700">Jalankan Pengecekan Sekarang</button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="font-semibold">Pelanggan Terisolir</h3>
            </div>
            <div class="overflow-x-auto">
                @if($isolatedCustomers->isNotEmpty())
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tunggakan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($isolatedCustomers as $customer)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $customer->name }}</td>
                                    <td class="px-4 py-3 text-sm text-red-600">Rp {{ number_format($customer->totalOutstanding(), 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <form method="POST" action="{{ route('admin.isolir.reopen', $customer) }}">
                                            @csrf
                                            <input type="text" name="reason" placeholder="Alasan..." class="border rounded px-2 py-1 text-xs mr-2">
                                            <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700">Buka Isolir</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="p-4 text-gray-500 text-sm">Tidak ada pelanggan terisolir</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
                <h3 class="font-semibold">Riwayat Isolir</h3>
            </div>
            <div class="overflow-x-auto">
                @if($isolirLogs->isNotEmpty())
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alasan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($isolirLogs as $log)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $log->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $log->customer->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $log->action->value == 'isolate' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $log->action->value == 'isolate' ? 'Isolir' : 'Buka' }}
                                        </span>
                                        @if($log->is_automatic)
                                            <span class="text-xs text-gray-400 ml-1">Auto</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $log->reason }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="p-4 text-gray-500 text-sm">Tidak ada riwayat</p>
                @endif
            </div>
            <div class="p-4 border-t">
                {{ $isolirLogs->links() }}
            </div>
        </div>
    </div>
    @endsection
