<x-customer-layout>
    @section('title', 'Paket Saya')
    @section('header', 'Paket Langganan')

    @section('content')
    <div class="space-y-6" x-data="{
        upgradeModalOpen: false,
        selectedPackageId: null,
        selectedPackageName: '',
        selectedPackageSpeed: '',
        selectedPackagePrice: 0,
        openUpgrade(id, name, speed, price) {
            this.selectedPackageId = id;
            this.selectedPackageName = name;
            this.selectedPackageSpeed = speed;
            this.selectedPackagePrice = price;
            this.upgradeModalOpen = true;
        }
    }">

        <!-- Status Permintaan Upgrade Pending (Jika Ada) -->
        @if($pendingRequest)
            <div class="bg-amber-50 border-2 border-amber-300 rounded-3xl p-5 sm:p-6 text-amber-900 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start sm:items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-mono font-bold bg-amber-200 px-2 py-0.5 rounded-lg">{{ $pendingRequest->request_number }}</span>
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Menunggu Persetujuan Admin</span>
                        </div>
                        <p class="text-sm font-bold text-gray-900 mt-1">
                            Permintaan Upgrade ke: <span class="text-blue-700 font-extrabold">{{ $pendingRequest->targetPackage->name }} ({{ $pendingRequest->targetPackage->speed }})</span>
                        </p>
                        <p class="text-xs text-amber-800 mt-0.5">
                            Diajukan pada {{ $pendingRequest->created_at->format('d M Y H:i') }} WIB. Tim admin akan segera meninjau dan mengirim konfirmasi via WhatsApp.
                        </p>
                    </div>
                </div>
                <div class="text-xs text-amber-800 font-semibold bg-white/70 px-4 py-2 rounded-2xl border border-amber-200 self-start sm:self-auto">
                    Biaya Paket Baru: Rp {{ number_format($pendingRequest->targetPackage->price, 0, ',', '.') }}/bln
                </div>
            </div>
        @endif

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

        <!-- Pilihan Paket Lainnya untuk Upgrade -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-6">
            <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900">Pilihan Paket Lainnya (Upgrade Langsung)</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Pilih paket baru yang Anda inginkan. Permintaan akan langsung diteruskan ke portal admin untuk persetujuan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($availablePackages as $package)
                    @php
                        $isCurrent = $currentPackage && $package->id === $currentPackage->id;
                        $isPendingTarget = $pendingRequest && $pendingRequest->target_package_id === $package->id;
                    @endphp
                    <div class="rounded-2xl p-5 border transition flex flex-col justify-between {{ $isCurrent ? 'border-blue-500 ring-2 ring-blue-100 bg-blue-50/30' : ($isPendingTarget ? 'border-amber-400 bg-amber-50/30' : 'border-gray-200 hover:border-gray-300 bg-white') }}">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-gray-900 text-base">{{ $package->name }}</h4>
                                @if($isCurrent)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">Paket Aktif</span>
                                @elseif($isPendingTarget)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">Diproses Admin</span>
                                @endif
                            </div>
                            <p class="text-2xl font-black text-gray-900">
                                Rp {{ number_format($package->price, 0, ',', '.') }}
                                <span class="text-xs font-normal text-gray-500">/bln</span>
                            </p>
                            <p class="text-xs font-semibold text-blue-600 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Kecepatan: {{ $package->speed }}
                            </p>
                            @if($package->description)
                                <p class="text-xs text-gray-500 mt-2">{{ $package->description }}</p>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-100">
                            @if($isCurrent)
                                <button disabled class="w-full py-2.5 rounded-xl text-xs font-bold bg-gray-100 text-gray-400 cursor-not-allowed">Sedang Digunakan</button>
                            @elseif($isPendingTarget)
                                <button disabled class="w-full py-2.5 rounded-xl text-xs font-bold bg-amber-100 text-amber-700 cursor-not-allowed">Menunggu Persetujuan</button>
                            @elseif($pendingRequest)
                                <button disabled class="w-full py-2.5 rounded-xl text-xs font-bold bg-gray-100 text-gray-400 cursor-not-allowed" title="Selesaikan permohonan yang aktif terlebih dahulu">Upgrade Ditangguhkan</button>
                            @else
                                <button type="button" @click="openUpgrade({{ $package->id }}, '{{ addslashes($package->name) }}', '{{ addslashes($package->speed) }}', {{ $package->price }})" class="w-full py-2.5 rounded-xl text-xs font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-sm transition">
                                    Ajukan Upgrade Paket Ini
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Riwayat Permintaan Upgrade -->
        @if($historyRequests->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 p-5 overflow-hidden">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Riwayat Pengajuan Upgrade Paket</h3>
                <div class="divide-y divide-gray-100 text-xs">
                    @foreach($historyRequests as $hist)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <p class="font-bold text-gray-900">{{ $hist->request_number }} &bull; {{ $hist->currentPackage?->name ?? '-' }} &rarr; <span class="text-blue-600">{{ $hist->targetPackage->name }}</span></p>
                                <p class="text-gray-500 mt-0.5">{{ $hist->created_at->format('d M Y H:i') }} WIB</p>
                                @if($hist->rejection_reason)
                                    <p class="text-red-600 mt-0.5">Alasan tolak: {{ $hist->rejection_reason }}</p>
                                @endif
                            </div>
                            <div>
                                @if($hist->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Disetujui</span>
                                @elseif($hist->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-800">Ditolak</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Pending</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Modal Konfirmasi Upgrade Paket -->
        <div x-show="upgradeModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" style="display: none;" x-cloak>
            <div @click.away="upgradeModalOpen = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-base font-bold text-gray-900">Konfirmasi Upgrade Paket</h3>
                    <button type="button" @click="upgradeModalOpen = false" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Paket Saat Ini:</span>
                        <span class="font-bold text-gray-900">{{ $currentPackage->name ?? 'Belum ada' }} ({{ $currentPackage->speed ?? '-' }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Paket Baru Tujuan:</span>
                        <span class="font-extrabold text-blue-700" x-text="selectedPackageName + ' (' + selectedPackageSpeed + ')'"></span>
                    </div>
                    <div class="flex justify-between border-t border-blue-200/60 pt-2">
                        <span class="text-gray-600">Biaya Baru:</span>
                        <span class="font-bold text-gray-900" x-text="'Rp ' + Number(selectedPackagePrice).toLocaleString('id-ID') + '/bulan'"></span>
                    </div>
                </div>

                <form action="{{ route('customer.packages.upgrade-request') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="target_package_id" :value="selectedPackageId" />

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan (opsional)</label>
                        <textarea name="notes" rows="2" placeholder="e.g. Mohon aktifkan secepatnya..." class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
                    </div>

                    <p class="text-[11px] text-gray-500 leading-relaxed">
                        * Permintaan akan langsung masuk ke antrean persetujuan admin. Anda akan menerima pesan notifikasi WhatsApp saat upgrade telah diaktifkan.
                    </p>

                    <div class="flex gap-2 justify-end pt-2">
                        <button type="button" @click="upgradeModalOpen = false" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 text-white hover:bg-blue-700 transition shadow">Kirim Permintaan Upgrade</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    @endsection
</x-customer-layout>
