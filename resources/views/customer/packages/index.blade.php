<x-customer-layout>
    @section('title', 'Paket Saya')
    @section('header', 'Paket Saya')

    @section('content')
    <!-- Paket Aktif -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Paket Aktif</h3>
        @if($currentPackage)
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $currentPackage->name }}</p>
                    <p class="text-sm text-gray-500">{{ $currentPackage->speed }}</p>
                    <p class="text-sm text-gray-500">Rp {{ number_format($currentPackage->price, 0, ',', '.') }}/bulan</p>
                </div>
                <div class="flex flex-col sm:items-end gap-2">
                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">
                        Active
                    </span>
                    <p class="text-xs text-gray-400">Mulai: {{ $customer->joined_at?->format('d M Y') }}</p>
                </div>
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada paket aktif</p>
        @endif
    </div>

    <!-- Paket Tersedia -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Paket Tersedia</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($availablePackages as $package)
                <div class="border border-gray-200 rounded-xl p-4 hover:border-blue-300 transition">
                    <p class="font-semibold text-gray-900">{{ $package->name }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $package->speed }}</p>
                    <p class="text-sm font-medium text-gray-900 mt-2">Rp {{ number_format($package->price, 0, ',', '.') }}/bulan</p>
                    @if($currentPackage && $package->id === $currentPackage->id)
                        <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paket Aktif</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endsection
</x-customer-layout>
