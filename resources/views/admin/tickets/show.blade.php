@extends('layouts.admin')
@section('title', 'Detail Gangguan #' . $ticket->ticket_number)
@section('header', 'Detail Gangguan #' . $ticket->ticket_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $ticket->title }}</h3>
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $ticket->description }}</p>

            @if($ticket->resolution_notes)
                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded">
                    <p class="text-xs font-semibold text-green-800">Catatan Penyelesaian Teknisi:</p>
                    <p class="text-sm text-green-900 mt-1">{{ $ticket->resolution_notes }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h4 class="font-semibold text-gray-800 mb-4">Selesaikan Tiket Gangguan</h4>
            <form action="{{ route('admin.tickets.resolve', $ticket) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Catatan Solusi / Tindakan Perbaikan *</label>
                    <textarea name="resolution_notes" rows="3" required placeholder="Jelaskan apa yang sudah diperbaiki / diganti..." class="mt-1 w-full border rounded px-3 py-2 text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Foto Bukti Perbaikan (opsional)</label>
                    <input type="file" name="proof_photo" accept="image/*" class="mt-1 w-full text-sm">
                </div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">Tandai Selesai (Resolve)</button>
            </form>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h4 class="font-semibold text-gray-800 mb-3">Informasi Pelanggan</h4>
            <div class="text-sm space-y-2">
                <div><span class="text-gray-400">Nama:</span> <span class="font-medium text-gray-800">{{ $ticket->customer->name ?? '-' }}</span></div>
                <div><span class="text-gray-400">HP:</span> {{ $ticket->customer->phone ?? '-' }}</div>
                <div><span class="text-gray-400">Paket:</span> {{ $ticket->customer->package->name ?? '-' }}</div>
                <div><span class="text-gray-400">Alamat:</span> {{ $ticket->customer->address ?? '-' }}</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h4 class="font-semibold text-gray-800 mb-3">Tugaskan Teknisi</h4>
            <form action="{{ route('admin.tickets.assign', $ticket) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs text-gray-500">Pilih Teknisi</label>
                    <select name="assigned_to" required class="mt-1 w-full border rounded px-3 py-2 text-sm bg-white">
                        <option value="">-- Pilih Staf Teknisi --</option>
                        @foreach($technicians as $t)
                            <option value="{{ $t->id }}" @selected($ticket->assigned_to === $t->id)>{{ $t->name }} ({{ $t->role->value }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500">Tingkat Prioritas</label>
                    <select name="priority" class="mt-1 w-full border rounded px-3 py-2 text-sm bg-white">
                        <option value="low" @selected($ticket->priority === 'low')>Low (48 Jam)</option>
                        <option value="medium" @selected($ticket->priority === 'medium')>Medium (24 Jam)</option>
                        <option value="high" @selected($ticket->priority === 'high')>High (8 Jam)</option>
                        <option value="urgent" @selected($ticket->priority === 'urgent')>Urgent (4 Jam)</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded text-sm hover:bg-blue-700">Update Penugasan</button>
            </form>
        </div>
    </div>
</div>
@endsection
