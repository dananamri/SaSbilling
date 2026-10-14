<x-customer-layout>
    @section('title', 'Beranda')
    @section('header', 'Beranda')

    @section('content')
    <div class="space-y-6">
        <!-- Banner Sambutan & Status Akun -->
        <div class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-2xl sm:rounded-3xl p-4 sm:p-7 text-white shadow-lg overflow-hidden">
            <!-- Background Accent Circles -->
            <div class="absolute -right-8 -top-8 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute right-12 -bottom-10 w-36 h-36 bg-cyan-400/20 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/15 backdrop-blur rounded-full text-xs font-semibold mb-2">
                        <span class="w-2 h-2 rounded-full {{ $connectionStatus === 'online' ? 'bg-green-400 animate-pulse' : 'bg-red-400' }}"></span>
                        <span>Internet {{ $connectionStatus === 'online' ? 'Terhubung (Online)' : 'Terputus (Offline)' }}</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight">Halo, {{ $customer->name }}!</h2>
                    <p class="text-xs sm:text-sm text-blue-100 mt-1">ID Pelanggan: <span class="font-mono font-bold tracking-wider">CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</span></p>
                </div>

                <!-- Info Paket Aktif Singkat -->
                @if($customer->package)
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-4 sm:min-w-[220px]">
                        <p class="text-[11px] text-blue-100 uppercase font-semibold">Paket Aktif</p>
                        <p class="text-base font-extrabold mt-0.5">{{ $customer->package->name }}</p>
                        <p class="text-xs text-blue-100 mt-1">{{ $customer->package->speed }} &bull; Rp {{ number_format($customer->package->price, 0, ',', '.') }}/bln</p>
                    </div>
                @endif
            </div>

            <!-- Warning Tagihan Aktif (Jika Ada Belum Lunas) -->
            @if($currentInvoice)
                <div class="relative z-10 mt-5 pt-4 border-t border-white/15 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-blue-100">Tagihan Periode {{ $currentInvoice->period }}</p>
                            <p class="text-base font-bold">Rp {{ number_format($currentInvoice->remaining(), 0, ',', '.') }} <span class="text-xs font-normal text-blue-200">(Jatuh tempo {{ $currentInvoice->due_date->format('d M Y') }})</span></p>
                        </div>
                    </div>
                    <a href="{{ route('customer.payments') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 sm:py-2.5 rounded-xl bg-white text-blue-700 text-xs font-extrabold hover:bg-blue-50 shadow transition whitespace-nowrap text-center">
                        Bayar Sekarang &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- 4 Quick Actions Shortcut -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('customer.payments') }}" class="bg-white p-4 rounded-2xl border border-gray-200 hover:border-blue-400 hover:shadow-sm transition flex flex-col items-center text-center group">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-800">Bayar Tagihan</span>
                <span class="text-[10px] text-gray-400 mt-0.5">Upload Bukti</span>
            </a>

            <a href="{{ route('customer.packages') }}" class="bg-white p-4 rounded-2xl border border-gray-200 hover:border-blue-400 hover:shadow-sm transition flex flex-col items-center text-center group">
                <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-800">Paket Saya</span>
                <span class="text-[10px] text-gray-400 mt-0.5">Speed & Info</span>
            </a>

            <a href="{{ route('customer.usage') }}" class="bg-white p-4 rounded-2xl border border-gray-200 hover:border-blue-400 hover:shadow-sm transition flex flex-col items-center text-center group">
                <div class="w-11 h-11 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-800">Pemakaian</span>
                <span class="text-[10px] text-gray-400 mt-0.5">Kuota & FUP</span>
            </a>

            <a href="{{ route('customer.tickets') }}" class="bg-white p-4 rounded-2xl border border-gray-200 hover:border-blue-400 hover:shadow-sm transition flex flex-col items-center text-center group">
                <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-800">Lapor Gangguan</span>
                <span class="text-[10px] text-gray-400 mt-0.5">Bantuan 24/7</span>
            </a>
        </div>

        <!-- 3 Kartu Ringkasan -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <p class="text-xs font-semibold text-gray-400 uppercase">Tagihan Belum Lunas</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-1">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-2">Total kewajiban aktif</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <p class="text-xs font-semibold text-gray-400 uppercase">Total Sudah Dibayar</p>
                <p class="text-2xl font-extrabold text-green-600 mt-1">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-2">Sepanjang langganan</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <p class="text-xs font-semibold text-gray-400 uppercase">Status Akun</p>
                <p class="text-2xl font-extrabold mt-1 {{ $customer->status->value === 'active' ? 'text-green-600' : 'text-red-600' }}">
                    {{ ucfirst($customer->status->value) }}
                </p>
                <p class="text-xs text-gray-500 mt-2">Billing cycle tgl {{ $customer->billing_day ?? 1 }}</p>
            </div>
        </div>

        <!-- Tagihan Terakhir & Riwayat Singkat -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- Tagihan Terbaru -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 text-sm">Tagihan Terbaru</h3>
                    <a href="{{ route('customer.history', ['tab' => 'invoices']) }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua</a>
                </div>
                @if($recentInvoices->isNotEmpty())
                    <div class="divide-y divide-gray-100">
                        @foreach($recentInvoices as $inv)
                            <a href="{{ route('customer.invoices.show', $inv) }}" class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                                <div>
                                    <p class="text-xs font-bold text-gray-900">{{ $inv->invoice_number }}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">Periode {{ $inv->period }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-gray-900">Rp {{ number_format($inv->amount, 0, ',', '.') }}</p>
                                    <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $inv->status->value === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($inv->status->value) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-gray-400">Belum ada invoice.</div>
                @endif
            </div>

            <!-- Pembayaran Terakhir -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 text-sm">Pembayaran Terakhir</h3>
                    <a href="{{ route('customer.history', ['tab' => 'payments']) }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua</a>
                </div>
                @if($recentPayments->isNotEmpty())
                    <div class="divide-y divide-gray-100">
                        @foreach($recentPayments as $pay)
                            <a href="{{ route('customer.payments.show', $pay) }}" class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                                <div>
                                    <p class="text-xs font-bold text-gray-900">{{ $pay->invoice->invoice_number ?? '-' }}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">{{ $pay->created_at->format('d M Y') }} &bull; {{ $pay->method?->label() ?? 'Transfer' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-green-600">Rp {{ number_format($pay->amount, 0, ',', '.') }}</p>
                                    <span class="text-[10px] font-bold text-gray-400">Sukses</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-gray-400">Belum ada pembayaran.</div>
                @endif
            </div>
        </div>
    </div>
    @endsection
</x-customer-layout>
