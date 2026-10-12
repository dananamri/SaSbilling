@extends('layouts.admin')
@section('title', 'Setoran Kasir')
@section('header', 'Rekap & Verifikasi Setoran Kasir')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <div>
            <h3 class="font-semibold text-gray-800">Daftar Setoran Kasir / Kolektor</h3>
            <p class="text-xs text-gray-500">Serah terima dana tunai harian dari petugas kasir ke admin.</p>
        </div>
        <a href="{{ route('admin.cash-deposits.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-center whitespace-nowrap">+ Buat Setoran Kasir</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Setoran</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kasir / Petugas</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($deposits as $d)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium">{{ $d->deposit_number }}</td>
                        <td class="px-4 py-3 text-sm">{{ $d->cashier->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-gray-900">Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $d->deposit_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($d->status === 'approved')
                                <span class="px-2 py-0.5 text-xs rounded bg-green-100 text-green-800">Diverifikasi</span>
                            @elseif($d->status === 'rejected')
                                <span class="px-2 py-0.5 text-xs rounded bg-red-100 text-red-800">Ditolak</span>
                            @else
                                <span class="px-2 py-0.5 text-xs rounded bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($d->status === 'pending')
                                <form method="POST" action="{{ route('admin.cash-deposits.approve', $d) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:underline mr-2" onclick="return confirm('Verifikasi setoran ini?')">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('admin.cash-deposits.reject', $d) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="rejection_reason" value="Selisih fisik kas">
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Tolak setoran ini?')">Tolak</button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500 text-sm">Belum ada data setoran kasir.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t">
        {{ $deposits->links() }}
    </div>
</div>
@endsection
