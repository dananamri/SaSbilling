@extends('layouts.admin')
    @section('title', 'Invoice')
    @section('header', 'Data Invoice')

    @section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b flex justify-between items-center">
            <form method="GET" class="flex space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice/pelanggan..." class="border rounded px-3 py-2 text-sm">
                <select name="status" class="border rounded px-3 py-2 text-sm">
                    <option value="">Semua Status</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Sebagian</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Tunggakan</option>
                </select>
                <input type="month" name="period" value="{{ request('period') }}" class="border rounded px-3 py-2 text-sm">
                <button type="submit" class="bg-slate-600 text-white px-4 py-2 rounded text-sm">Filter</button>
            </form>
            <a href="{{ route('admin.invoices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">+ Buat Invoice</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Periode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Jatuh Tempo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($invoices as $invoice)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $invoice->invoice_number }}</td>
                            <td class="px-4 py-3 text-sm">{{ $invoice->customer->name }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $invoice->period }}</td>
                            <td class="px-4 py-3 text-sm">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm hidden sm:table-cell">{{ $invoice->due_date->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $invoice->status->value == 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status->value == 'overdue' ? 'bg-red-100 text-red-800' : ($invoice->status->value == 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($invoice->status->value) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('admin.invoices.show', $invoice) }}" class="text-blue-600 hover:underline">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t">
            {{ $invoices->withQueryString()->links() }}
        </div>
    </div>
    @endsection
