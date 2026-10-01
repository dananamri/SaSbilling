@extends('layouts.admin')
    @section('title', 'Buat Invoice')
    @section('header', 'Buat Invoice Manual')

    @section('content')
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-2xl" x-data="{
        selectedCustomer: '{{ old('customer_id') }}',
        customers: {
            @foreach($customers as $customer)
                '{{ $customer->id }}': { name: '{{ $customer->name }}', price: {{ $customer->package->price ?? 0 }}, package: '{{ $customer->package->name ?? 'Tanpa Paket' }}' },
            @endforeach
        },
        get selectedPrice() {
            return this.selectedCustomer ? (this.customers[this.selectedCustomer]?.price ?? 0) : 0;
        },
        get selectedPackage() {
            return this.selectedCustomer ? (this.customers[this.selectedCustomer]?.package ?? '-') : '-';
        }
    }">
        <form method="POST" action="{{ route('admin.invoices.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pelanggan *</label>
                    <select name="customer_id" x-model="selectedCustomer" class="w-full border rounded px-3 py-2 text-sm @error('customer_id') border-red-500 @endif">
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->package->name ?? 'Tanpa Paket' }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>

                <div class="p-3 bg-gray-50 rounded text-sm" x-show="selectedCustomer">
                    <p><span class="text-gray-500">Paket:</span> <span class="font-medium" x-text="selectedPackage"></span></p>
                    <p class="mt-1"><span class="text-gray-500">Harga Paket:</span> <span class="font-medium text-green-600">Rp <span x-text="selectedPrice.toLocaleString('id-ID')"></span></span></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periode (YYYY-MM) *</label>
                    <input type="month" name="period" value="{{ old('period', now()->format('Y-m')) }}" class="w-full border rounded px-3 py-2 text-sm @error('period') border-red-500 @endif">
                    @error('period')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp) *</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" x-model="selectedPrice" min="0" class="w-full border rounded px-3 py-2 text-sm @error('amount') border-red-500 @endif">
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                    <p class="text-xs text-gray-500 mt-1">Otomatis terisi dari harga paket pelanggan</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jatuh Tempo *</label>
                    <input type="date" name="due_date" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2 text-sm @error('due_date') border-red-500 @endif">
                    @error('due_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>

                <div class="p-3 bg-blue-50 rounded border border-blue-200">
                    <div class="flex items-center">
                        <input type="checkbox" name="send_notification" value="1" id="send_notification" class="mr-2">
                        <label for="send_notification" class="text-sm text-gray-700">Kirim notifikasi WhatsApp ke pelanggan</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pesan otomatis terisi berdasarkan data pelanggan dan invoice</p>
                </div>
            </div>
            <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-center">Simpan</button>
                <a href="{{ route('admin.invoices.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-400 text-center">Batal</a>
            </div>
        </form>
    </div>
    @endsection
