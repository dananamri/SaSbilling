@extends('layouts.admin')
@section('title', 'Tiket Pembayaran')
@section('header', 'Verifikasi Bukti Transfer Pelanggan')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b">
        <h3 class="font-semibold text-gray-800">Antrean Tiket Pembayaran Manual</h3>
        <p class="text-xs text-gray-500">Verifikasi bukti transfer dari pelanggan sebelum invoice dilunasi otomatis oleh sistem.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Tiket</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Bayar</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($tickets as $t)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium">{{ $t->ticket_number }}</td>
                        <td class="px-4 py-3 text-sm">{{ $t->customer->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-bold">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $t->payment_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($t->status === 'approved')
                                <span class="px-2 py-0.5 text-xs rounded bg-green-100 text-green-800">Disetujui</span>
                            @elseif($t->status === 'rejected')
                                <span class="px-2 py-0.5 text-xs rounded bg-red-100 text-red-800">Ditolak</span>
                            @else
                                <span class="px-2 py-0.5 text-xs rounded bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($t->status === 'pending')
                                <form method="POST" action="{{ route('admin.payment-tickets.approve', $t) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:underline mr-2" onclick="return confirm('Setujui pembayaran ini?')">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('admin.payment-tickets.reject', $t) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="rejection_reason" value="Bukti transfer tidak valid">
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Tolak bukti transfer?')">Tolak</button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500 text-sm">Belum ada tiket pembayaran masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
