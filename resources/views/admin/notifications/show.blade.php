@extends('layouts.admin')
    @section('title', 'Detail Notifikasi')
    @section('header', 'Detail Notifikasi')

    @section('content')
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-2xl">
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Pelanggan</dt><dd>{{ $notification->customer->name ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Tipe</dt><dd>{{ ucfirst(str_replace('_', ' ', $notification->type->value)) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Status</dt>
                <dd>
                    <span class="px-2 py-1 text-xs rounded-full {{ $notification->status == 'sent' ? 'bg-green-100 text-green-800' : ($notification->status == 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($notification->status) }}
                    </span>
                </dd>
            </div>
            <div class="flex justify-between"><dt class="text-gray-500">Tanggal</dt><dd>{{ $notification->created_at->format('d M Y H:i') }}</dd></div>
            @if($notification->sent_at)
                <div class="flex justify-between"><dt class="text-gray-500">Terkirim</dt><dd>{{ $notification->sent_at->format('d M Y H:i') }}</dd></div>
            @endif
        </dl>
        <div class="mt-4 p-4 bg-gray-50 rounded">
            <p class="text-sm whitespace-pre-wrap">{{ $notification->message }}</p>
        </div>
        <div class="mt-6">
            <a href="{{ route('admin.notifications.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400">Kembali</a>
        </div>
    </div>
    @endsection
