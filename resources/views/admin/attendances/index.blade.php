@extends('layouts.admin')
@section('title', 'Absensi')
@section('header', 'Absensi Pegawai & Teknisi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg shadow h-fit">
        <h3 class="font-semibold text-gray-800 mb-4">Presensi Hari Ini</h3>
        <div class="border p-4 rounded-lg bg-gray-50 text-center mb-4">
            <p class="text-xs text-gray-500 mb-3">Jam sekarang: <span class="font-bold text-gray-800">{{ now()->format('H:i:s') }}</span></p>
            <div class="grid grid-cols-2 gap-2">
                <form method="POST" action="{{ route('admin.attendances.clock-in') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700">Check-In</button>
                </form>
                <form method="POST" action="{{ route('admin.attendances.clock-out') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700">Check-Out</button>
                </form>
            </div>
        </div>

        <h4 class="font-semibold text-gray-800 mb-3">Input Manual Admin</h4>
        <form action="{{ route('admin.attendances.store') }}" method="POST" class="space-y-2">
            @csrf
            <select name="user_id" required class="w-full border rounded px-3 py-2 text-sm bg-white text-xs">
                @foreach($staff as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full border rounded px-3 py-2 text-sm">
            <select name="status" class="w-full border rounded px-3 py-2 text-sm bg-white">
                <option value="present">Hadir (Present)</option>
                <option value="late">Terlambat</option>
                <option value="sick">Sakit</option>
                <option value="leave">Cuti / Izin</option>
                <option value="absent">Tanpa Keterangan</option>
            </select>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded text-sm hover:bg-blue-700">Simpan Kehadiran Manual</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-800">Daftar Kehadiran Harian</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Staf</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masuk / Keluar</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($attendances as $a)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $a->date->format('d-m-Y') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $a->user->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm font-mono text-xs">
                                <div>{{ $a->clock_in ? substr($a->clock_in, 0, 5) : '-' }} — {{ $a->clock_out ? substr($a->clock_out, 0, 5) : '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-0.5 text-xs font-bold rounded uppercase {{ $a->status === 'present' ? 'bg-green-100 text-green-800' : ($a->status === 'late' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-700') }}">
                                    {{ $a->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada data absensi hari ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $attendances->links() }}</div>
    </div>
</div>
@endsection
