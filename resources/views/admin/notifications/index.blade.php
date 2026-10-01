@extends('layouts.admin')
    @section('title', 'Notifikasi')
    @section('header', 'Notifikasi WhatsApp')

    @section('content')
    <div class="flex justify-between mb-4">
        <form method="GET" class="flex space-x-2">
            <select name="type" class="border rounded px-3 py-2 text-sm">
                <option value="">Semua Tipe</option>
                <option value="reminder" {{ request('type') == 'reminder' ? 'selected' : '' }}>Pengingat</option>
                <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                <option value="payment_received" {{ request('type') == 'payment_received' ? 'selected' : '' }}>Pembayaran</option>
                <option value="isolated" {{ request('type') == 'isolated' ? 'selected' : '' }}>Isolir</option>
                <option value="reopened" {{ request('type') == 'reopened' ? 'selected' : '' }}>Buka Isolir</option>
            </select>
            <select name="status" class="border rounded px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Terkirim</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
            </select>
            <button type="submit" class="bg-slate-600 text-white px-4 py-2 rounded text-sm">Filter</button>
        </form>
        <div class="flex space-x-2">
            <form method="POST" action="{{ route('admin.notifications.send-pending') }}">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">Kirim Pending</button>
            </form>
            <a href="{{ route('admin.notifications.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">+ Kirim Notifikasi</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Pesan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($notifications as $notification)
                        <tr>
                            <td class="px-4 py-3 text-sm">{{ $notification->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $notification->customer->name ?? '-' }}</td>
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <span class="px-2 py-1 text-xs rounded-full {{ $notification->type->value == 'reminder' ? 'bg-yellow-100 text-yellow-800' : ($notification->type->value == 'payment_received' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $notification->type->value)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm max-w-xs truncate hidden sm:table-cell">{{ Str::limit($notification->message, 50) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $notification->status == 'sent' ? 'bg-green-100 text-green-800' : ($notification->status == 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($notification->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('admin.notifications.show', $notification) }}" class="text-blue-600 hover:underline">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $notifications->withQueryString()->links() }}
        </div>
    </div>
    @endsection
