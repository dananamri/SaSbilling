<x-customer-layout>
    @section('title', 'Riwayat Saya')
    @section('header', 'Riwayat')

    @section('content')
    <div x-data="{ tab: '{{ $tab }}' }" class="space-y-5">
        <!-- Judul & Deskripsi -->
        <div>
            <h2 class="text-lg sm:text-xl font-extrabold text-gray-900">Riwayat</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau riwayat lengkap tagihan, pembayaran dan aduan gangguan Anda.</p>
        </div>

        <!-- Tab Switcher (rounded pill) -->
        <div class="bg-white rounded-2xl p-1.5 flex gap-1 border border-gray-200 w-full sm:w-fit">
            <button @click="tab = 'invoices'" :class="tab === 'invoices' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100'" class="flex-1 sm:flex-none px-4 py-2 rounded-xl text-sm font-semibold transition">
                Tagihan
            </button>
            <button @click="tab = 'payments'" :class="tab === 'payments' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100'" class="flex-1 sm:flex-none px-4 py-2 rounded-xl text-sm font-semibold transition">
                Pembayaran
            </button>
            <button @click="tab = 'tickets'" :class="tab === 'tickets' ? 'bg-blue-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100'" class="flex-1 sm:flex-none px-4 py-2 rounded-xl text-sm font-semibold transition">
                Gangguan
            </button>
        </div>

        <!-- TAB: Riwayat Tagihan -->
        <div x-show="tab === 'invoices'" class="bg-white rounded-2xl border border-gray-200 overflow-hidden" x-cloak>
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900 text-sm">Riwayat Tagihan & Langganan</h3>
                <p class="text-xs text-gray-500 mt-0.5">Semua tagihan bulanan dari awal berlangganan sampai akhir.</p>
            </div>

            @if($invoices->isEmpty())
                <div class="p-8 text-center">
                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-sm text-gray-500">Belum ada tagihan tercatat.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($invoices as $invoice)
                        <a href="{{ route('customer.invoices.show', $invoice) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                            <div class="flex-1 min-w-0 mr-4">
                                <p class="text-sm font-bold text-gray-900">{{ $invoice->invoice_number }}</p>
                                <p class="text-xs text-gray-500 mt-1">Periode: {{ $invoice->period }} &bull; Jatuh tempo: {{ $invoice->due_date->format('d M Y') }}</p>
                                <p class="text-xs mt-1 font-semibold text-gray-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                            </div>
                            <span class="shrink-0 px-3 py-1.5 rounded-full text-xs font-bold {{ $invoice->status->value === 'paid' ? 'bg-green-50 text-green-700 border border-green-200' : ($invoice->status->value === 'overdue' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-yellow-50 text-yellow-700 border border-yellow-200') }}">
                                {{ ucfirst($invoice->status->value) }}
                            </span>
                        </a>
                    @endforeach
                </div>
                <div class="p-4 border-t">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>

        <!-- TAB: Riwayat Pembayaran -->
        <div x-show="tab === 'payments'" class="bg-white rounded-2xl border border-gray-200 overflow-hidden" x-cloak>
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900 text-sm">Riwayat Pembayaran</h3>
                <p class="text-xs text-gray-500 mt-0.5">Transaksi yang sudah dibayar, diselesaikan, dan bukti kuitansi.</p>
            </div>

            @if($payments->isEmpty())
                <div class="p-8 text-center">
                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <p class="text-sm text-gray-500">Belum ada pembayaran.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($payments as $payment)
                        <a href="{{ route('customer.payments.show', $payment) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                            <div class="flex-1 min-w-0 mr-4">
                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $payment->created_at->format('d M Y H:i') }} &bull; {{ $payment->method?->label() ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">Invoice: {{ $payment->invoice->invoice_number ?? '-' }}</p>
                            </div>
                            <span class="shrink-0 px-3 py-1.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">Berhasil</span>
                        </a>
                    @endforeach
                </div>
                <div class="p-4 border-t">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

        <!-- TAB: Riwayat Gangguan / Tiket -->
        <div x-show="tab === 'tickets'" class="bg-white rounded-2xl border border-gray-200 overflow-hidden" x-cloak>
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Riwayat Laporan Gangguan</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Laporan gangguan internet yang pernah Anda kirimkan.</p>
                </div>
                <a href="{{ route('customer.tickets.create') }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 shrink-0">Buat Laporan</a>
            </div>

            @if($tickets->isEmpty())
                <div class="p-8 text-center">
                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <p class="text-sm text-gray-500">Belum ada laporan gangguan.</p>
                    <a href="{{ route('customer.tickets.create') }}" class="inline-block mt-3 text-sm text-blue-600 font-semibold hover:underline">Buat laporan sekarang</a>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($tickets as $ticket)
                        <a href="{{ route('customer.tickets.show', $ticket) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                            <div class="flex-1 min-w-0 mr-4">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $ticket->title }}</p>
                                <p class="text-xs text-gray-500 mt-1 truncate">{{ Str::limit($ticket->description, 80) }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <span class="shrink-0 px-3 py-1.5 rounded-full text-xs font-bold {{ $ticket->status === 'resolved' ? 'bg-green-50 text-green-700 border border-green-200' : ($ticket->status === 'in_progress' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-yellow-50 text-yellow-700 border border-yellow-200') }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </a>
                    @endforeach
                </div>
                <div class="p-4 border-t">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
    @endsection
</x-customer-layout>
