@extends('layouts.admin')
@section('title', 'Laporan Gangguan')
@section('header', 'Laporan Gangguan / Helpdesk')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex justify-between items-center">
        <div>
            <h3 class="font-semibold text-gray-800">Tiket Gangguan Pelanggan</h3>
            <p class="text-xs text-gray-500">Kelola keluhan teknis, penugasan teknisi, dan SLA perbaikan jaringan.</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Tiket</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keluhan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioritas</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teknisi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($tickets as $t)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium">{{ $t->ticket_number }}</td>
                        <td class="px-4 py-3 text-sm">{{ $t->customer->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <div class="font-medium text-gray-900">{{ $t->title }}</div>
                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ $t->description }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded 
                                {{ $t->priority === 'urgent' ? 'bg-red-100 text-red-800' : ($t->priority === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst($t->priority) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $t->technician->name ?? 'Belum Ditugaskan' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-0.5 text-xs rounded 
                                {{ $t->status === 'resolved' ? 'bg-green-100 text-green-800' : ($t->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $t->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin.tickets.show', $t) }}" class="text-blue-600 hover:underline">Kelola & Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">Tidak ada tiket gangguan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t">{{ $tickets->links() }}</div>
</div>
@endsection
