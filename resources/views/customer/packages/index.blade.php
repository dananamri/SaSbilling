<x-customer-layout>
    @section('title', 'Paket Saya')
    @section('header', 'Paket Langganan')

    @section('content')
    <div class="space-y-6">
        <!-- Paket Aktif Hero Card -->
        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-blue-800 rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-lg">
            <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-white/10 rounded-full blur-xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/15 backdrop-blur rounded-full text-xs font-semibold mb-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Paket Sedang Aktif
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">{{ $currentPackage->name ?? 'Belum Ada Paket' }}</h2>
                    <p class="text-sm text-blue-100 mt-1">{{ $currentPackage->description ?? 'Layanan internet unlimited SATAK' }}</p>
                </div>

                @if($currentPackage)
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-4 sm:text-right">
                        <p class="text-xs text-blue-200">Biaya Langganan</p>
                        <p class="text-2xl font-black mt-0.5">Rp {{ number_format($currentPackage->price, 0, ',', '.') }}<span class="text-xs font-normal text-blue-200">/bln</span></p>
                        <p class="text-[11px] text-blue-200 mt-1">Kecepatan: {{ $currentPackage->speed }}</p>
                    </div>
                @endif
            </div>

            <!-- Detail Spesifikasi Singkat -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-white/15 text-xs">
                <div class="bg-white/10 backdrop-blur rounded-xl p-3">
                    <p class="text-blue-200">Kecepatan</p>
                    <p class="font-bold text-sm mt-0.5">{{ $currentPackage->speed ?? '-' }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-3">
                    <p class="text-blue-200">Tipe Paket</p>
                    <p class="font-bold text-sm mt-0.5">{{ ucfirst($currentPackage->type ?? 'Broadband') }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-3">
                    <p class="text-blue-200">Siklus Tagihan</p>
                    <p class="font-bold text-sm mt-0.5">Tgl {{ $customer->billing_day ?? 1 }} / Bulan</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-3">
                    <p class="text-blue-200">Mulai Langganan</p>
                    <p class="font-bold text-sm mt-0.5">{{ $customer->joined_at?->format('d M Y') ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Info Upgrade / CS -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Ingin Upgrade Kecepatan?</p>
                    <p class="text-xs text-gray-600 mt-0.5">Hubungi customer service atau buat tiket bantuan untuk ganti paket.</p>
                </div>
            </div>
            <a href="{{ route('customer.tickets.create') }}" class="px-4 py-2 bg-blue-600 text-white font-bold text-xs rounded-xl hover:bg-blue-700 transition text-center whitespace-nowrap">
                Ajukan Upgrade
            </a>
        </div>

        <!-- Pilihan Paket Lainnya -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-6">
            <div class="mb-5">
                <h3 class="text-base sm:text-lg font-bold text-gray-900">Pilihan Paket Lainnya</h3>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Bandingkan kecepatan dan harga paket internet SATAK yang tersedia.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($availablePackages as $package)
                    @php $isCurrent = $currentPackage && $package->id === $currentPackage->id; @endphp
                    <div class="rounded-2xl p-5 border transition flex flex-col justify-between {{ $isCurrent ? 'border-blue-500 ring-2 ring-blue-100 bg-blue-50/30' : 'border-gray-200 hover:border-gray-300 bg-white' }}">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-gray-900 text-base">{{ $package->name }}</h4>
                                @if($isCurrent)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">Paket Aktif</span>
                                @endif
                            </div>
                            <p class="text-2xl font-black text-gray-900">
                                Rp {{ number_format($package->price, 0, ',', '.') }}
                                <span class="text-xs font-normal text-gray-500">/bln</span>
                            </p>
                            <p class="text-xs font-semibold text-blue-600 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Kecepatan up to {{ $package->speed }}
                            </p>
                            @if($package->description)
                                <p class="text-xs text-gray-500 mt-2">{{ $package->description }}</p>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-100">
                            @if($isCurrent)
                                <button disabled class="w-full py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-400 cursor-not-allowed">Sedang Digunakan</button>
                            @else
                                <a href="{{ route('customer.tickets.create') }}" class="block text-center w-full py-2 rounded-xl text-xs font-bold bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition">Pilih Paket Ini</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endsection
</x-customer-layout>
