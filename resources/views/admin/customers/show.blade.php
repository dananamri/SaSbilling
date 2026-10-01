@extends('layouts.admin')
    @section('title', 'Detail Pelanggan')
    @section('header', 'Detail Pelanggan')

    @section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold mb-4">Informasi Pelanggan</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Nama</dt><dd class="font-medium">{{ $customer->name }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Telepon</dt><dd>{{ $customer->phone }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd>{{ $customer->email ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Alamat</dt><dd>{{ $customer->address ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Paket</dt><dd>{{ $customer->package->name ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Hari Billing</dt><dd>{{ $customer->billing_day }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Status</dt>
                    <dd>
                        <span class="px-2 py-1 text-xs rounded-full {{ $customer->status->value == 'active' ? 'bg-green-100 text-green-800' : ($customer->status->value == 'isolated' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($customer->status->value) }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between"><dt class="text-gray-500">Bergabung</dt><dd>{{ $customer->joined_at?->format('d M Y') ?? '-' }}</dd></div>
            </dl>
            <div class="mt-4 flex flex-col sm:flex-row gap-2 sm:space-x-2">
                <a href="{{ route('admin.customers.edit', $customer) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 text-center">Edit</a>
                <a href="{{ route('admin.customers.index') }}" class="bg-gray-300 text-gray-700 px-3 py-1 rounded text-xs hover:bg-gray-400 text-center">Kembali</a>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold mb-4">Invoice Terakhir</h3>
                @if($customer->invoices->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead><tr class="border-b"><th class="text-left py-2">Invoice</th><th class="text-left py-2 hidden sm:table-cell">Periode</th><th class="text-left py-2">Jumlah</th><th class="text-left py-2">Status</th></tr></thead>
                            <tbody>
                                @foreach($customer->invoices->take(5) as $invoice)
                                    <tr class="border-b">
                                        <td class="py-2">{{ $invoice->invoice_number }}</td>
                                        <td class="py-2 hidden sm:table-cell">{{ $invoice->period }}</td>
                                        <td class="py-2">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="px-2 py-1 text-xs rounded-full {{ $invoice->status->value == 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status->value == 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($invoice->status->value) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">Belum ada invoice</p>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-semibold mb-4">Riwayat Isolir</h3>
                @if($customer->isolirLogs->isNotEmpty())
                    <div class="space-y-2">
                        @foreach($customer->isolirLogs as $log)
                            <div class="flex justify-between items-center p-2 {{ $log->action->value == 'isolate' ? 'bg-red-50' : 'bg-green-50' }} rounded">
                                <div>
                                    <p class="font-medium text-sm">{{ $log->action->value == 'isolate' ? 'Diisolir' : 'Dibuka' }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->reason }}</p>
                                </div>
                                <p class="text-xs text-gray-500">{{ $log->created_at->format('d M Y H:i') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Tidak ada riwayat isolir</p>
                @endif
            </div>
        </div>
    </div>
    @endsection
