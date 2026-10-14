@extends('layouts.admin')

@section('title', 'Permintaan Upgrade Paket')
@section('header', 'Permintaan Upgrade Paket Pelanggan')

@section('content')
<div class="space-y-6">
    <!-- Filter Status Tabs -->
    <div class="overflow-x-auto pb-1 max-w-full">
        <div class="flex gap-2 bg-white p-1.5 sm:p-2 rounded-2xl border border-gray-200 w-max">
            <a href="{{ route('admin.package-change-requests.index', ['status' => 'pending']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $status === 'pending' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                Pending ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.package-change-requests.index', ['status' => 'approved']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $status === 'approved' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                Disetujui ({{ $counts['approved'] }})
            </a>
            <a href="{{ route('admin.package-change-requests.index', ['status' => 'rejected']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $status === 'rejected' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                Ditolak ({{ $counts['rejected'] }})
            </a>
            <a href="{{ route('admin.package-change-requests.index', ['status' => 'all']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $status === 'all' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                Semua
            </a>
        </div>
    </div>

    <!-- Tabel Permintaan -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        @if($requests->isEmpty())
            <div class="p-12 text-center">
                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3 text-gray-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <p class="text-sm font-semibold text-gray-800">Tidak ada permohonan upgrade paket dengan status "{{ $status }}".</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50 text-gray-500 font-semibold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">No. Request</th>
                            <th class="px-4 py-3 text-left">Pelanggan</th>
                            <th class="px-4 py-3 text-left">Paket Lama &rarr; Baru</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($requests as $req)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="font-mono font-bold text-blue-600">{{ $req->request_number }}</span>
                                    @if($req->notes)
                                        <p class="text-[11px] text-gray-500 mt-0.5 italic">"{{ Str::limit($req->notes, 40) }}"</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <p class="font-bold text-gray-900">{{ $req->customer->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $req->customer->phone }}</p>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-xs">
                                        <span class="text-gray-500 font-medium">{{ $req->currentPackage?->name ?? 'Tanpa Paket' }}</span>
                                        <span class="mx-1.5 text-blue-600 font-bold">&rarr;</span>
                                        <span class="font-extrabold text-gray-900">{{ $req->targetPackage->name }}</span>
                                        <span class="block text-[11px] text-emerald-600 font-bold mt-0.5">Rp {{ number_format($req->targetPackage->price, 0, ',', '.') }}/bln</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $req->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    @if($req->status === 'pending')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">Pending</span>
                                    @elseif($req->status === 'approved')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Disetujui</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">Ditolak</span>
                                    @endif
                                    @if($req->rejection_reason)
                                        <p class="text-[10px] text-red-600 mt-1 max-w-[160px] truncate" title="{{ $req->rejection_reason }}">Alasan: {{ $req->rejection_reason }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-xs font-medium">
                                    @if($req->status === 'pending')
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('admin.package-change-requests.approve', $req) }}" method="POST" onsubmit="return confirm('Setujui upgrade paket ini? Paket pelanggan akan otomatis diperbarui dan notifikasi WA dikirim ke pelanggan.')">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm transition">Setujui</button>
                                            </form>

                                            <!-- Button Modal Reject Trigger -->
                                            <button type="button" @click="$dispatch('open-reject-modal', { id: '{{ $req->id }}', number: '{{ $req->request_number }}', name: '{{ $req->customer->name }}' })" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-sm transition">Tolak</button>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">Selesai oleh {{ $req->approver?->name ?? 'Admin' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(method_exists($requests, 'links') && $requests->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50">
                    {{ $requests->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Modal Penolakan (Alpine.js) -->
    <div x-data="{ open: false, requestId: '', requestNumber: '', customerName: '' }"
         @open-reject-modal.window="open = true; requestId = $event.detail.id; requestNumber = $event.detail.number; customerName = $event.detail.name;"
         class="fixed inset-0 z-[10000] flex items-center justify-center bg-black/60 p-4"
         style="display: none;"
         x-show="open" x-cloak>
        <div class="bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 max-w-md w-full shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <h3 class="text-base font-bold text-gray-900">Tolak Permintaan Upgrade (<span x-text="requestNumber"></span>)</h3>
            <p class="text-xs text-gray-500">Pelanggan: <strong x-text="customerName"></strong></p>

            <form :action="'/admin/package-change-requests/' + requestId + '/reject'" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Penolakan *</label>
                    <textarea name="rejection_reason" rows="3" required placeholder="Tuliskan alasan mengapa ditolak..." class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
                </div>

                <div class="flex gap-2 justify-end pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-red-600 text-white hover:bg-red-700 transition">Konfirmasi Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
