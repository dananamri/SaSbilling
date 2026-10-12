@extends('layouts.admin')
@section('title', 'Pembukuan')
@section('header', 'Pembukuan / Buku Kas ISP')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
        <p class="text-xs text-green-600">Total Pemasukan</p>
        <p class="text-xl font-bold text-green-800">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
    </div>
    <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
        <p class="text-xs text-red-600">Total Pengeluaran</p>
        <p class="text-xl font-bold text-red-800">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
    </div>
    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
        <p class="text-xs text-blue-600">Saldo Kas Bersih</p>
        <p class="text-xl font-bold text-blue-800">Rp {{ number_format($balance, 0, ',', '.') }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <h3 class="font-semibold text-gray-800">Riwayat Kas Masuk & Keluar</h3>
        <a href="{{ route('admin.book-entries.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-center whitespace-nowrap">+ Tambah Transaksi</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor / Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($entries as $entry)
                    <tr>
                        <td class="px-4 py-3 text-sm">
                            <div class="font-medium">{{ $entry->entry_number }}</div>
                            <div class="text-xs text-gray-400">{{ $entry->date->format('d M Y') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 text-xs rounded {{ $entry->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $entry->type === 'income' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $entry->category }}</td>
                        <td class="px-4 py-3 text-sm font-bold {{ $entry->type === 'income' ? 'text-green-700' : 'text-red-700' }}">
                            {{ $entry->type === 'income' ? '+' : '-' }} Rp {{ number_format($entry->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $entry->description ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <form method="POST" action="{{ route('admin.book-entries.destroy', $entry) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500 text-sm">Belum ada transaksi pembukuan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t">
        {{ $entries->links() }}
    </div>
</div>
@endsection
