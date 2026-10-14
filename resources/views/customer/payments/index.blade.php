<x-customer-layout>
    @section('title', 'Pembayaran')
    @section('header', 'Pembayaran Tagihan')

    @section('content')
    <div class="space-y-5">
        <!-- Greeting / Info Ringkas -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl p-5 sm:p-6 text-white relative overflow-hidden">
            <img src="{{ asset('images/satak_logo.jpeg') }}" alt="" class="absolute top-0 right-0 w-40 h-40 object-cover opacity-10 rounded-bl-full pointer-events-none" />
            <p class="text-sm text-blue-100">Halo, {{ $customer->name }}</p>
            <h2 class="text-lg sm:text-xl font-extrabold mt-1">Kelola Pembayaran Tagihan</h2>
            <p class="text-sm text-blue-100 mt-2">Bayar tepat waktu agar internet tetap lancar. Unggah bukti transfer dan kami verifikasi dalam 1x24 jam.</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('customer.history', ['tab' => 'invoices']) }}" class="flex-1 sm:flex-none text-center bg-white text-blue-600 px-4 py-2.5 rounded-xl text-xs font-bold shadow hover:bg-blue-50 transition">Riwayat Tagihan</a>
                <a href="{{ route('customer.invoices') }}" class="flex-1 sm:flex-none text-center bg-white/20 text-white px-4 py-2.5 rounded-xl text-xs font-bold border border-white/20 hover:bg-white/30 transition">Semua Invoice</a>
            </div>
        </div>

        <!-- Ringkasan Rekening Tujuan Bayar -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-3">Rekening Tujuan Pembayaran</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center shrink-0 text-blue-700 font-bold text-xs">BCA</div>
                    <div>
                        <p class="font-bold text-gray-900">BCA: 9876543210</p>
                        <p class="text-xs text-gray-500 mt-0.5">a.n. PT SATAK Konek Terus</p>
                    </div>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex gap-3">
                    <div class="w-9 h-9 rounded-lg bg-orange-100 flex items-center justify-center shrink-0 text-orange-700 font-bold text-xs">QR</div>
                    <div>
                        <p class="font-bold text-gray-900">QRIS: SATAK ISP</p>
                        <p class="text-xs text-gray-500 mt-0.5">Scan untuk bayar instan via MBanking</p>
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">Konfirmasi pembayaran setelah transfer selesai di form bawah. Kami kirim WA saat lunas.</p>
        </div>

        <!-- Tagihan Belum Lunas -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm">Tagihan Menunggu Pembayaran</h3>
                <span class="text-xs font-bold text-gray-500">{{ $unpaidInvoices->count() }} tagihan</span>
            </div>

            @if($unpaidInvoices->isEmpty())
                <div class="p-8 text-center">
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Semua tagihan sudah lunas</p>
                    <p class="text-xs text-gray-500 mt-1">Tidak ada tunggakan. Terima kasih sudah bayar tepat waktu!</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($unpaidInvoices as $invoice)
                        <div class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900">{{ $invoice->invoice_number }}</p>
                                <p class="text-xs text-gray-500 mt-1">Periode {{ $invoice->period }} &bull; Jatuh tempo {{ $invoice->due_date->format('d M Y') }}</p>
                                <p class="text-xs text-red-600 font-bold mt-1">Rp {{ number_format($invoice->remaining(), 0, ',', '.') }} • {{ ucfirst($invoice->status->value) }}</p>
                            </div>
                            <a href="{{ route('customer.invoices.show', $invoice) }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 text-white hover:bg-blue-700 transition text-center whitespace-nowrap min-h-[42px] flex items-center justify-center">Lihat & Bayar</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Upload Bukti Transfer -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5" x-data="{ open: false }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Konfirmasi Pembayaran (Upload Bukti Transfer)</h3>
                    <p class="text-xs text-gray-500 mt-1">Sudah transfer? Unggah bukti di sini agar admin segera verifikasi.</p>
                </div>
                <button @click="open = !open" class="w-full sm:w-auto bg-gray-900 text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-black transition text-center min-h-[40px]" x-text="open ? 'Tutup Form' : 'Unggah Bukti'"></button>
            </div>

            <form x-show="open" action="{{ route('customer.payment-tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 border-t border-gray-100 pt-4" style="display: none;">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Tagihan *</label>
                        <select name="invoice_id" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                            @foreach($unpaidInvoices as $invoice)
                                <option value="{{ $invoice->id }}">{{ $invoice->invoice_number }} — Rp {{ number_format($invoice->amount, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nominal Transfer *</label>
                        <input type="number" name="amount" min="1000" required placeholder="e.g. 150000" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Bank Pengirim *</label>
                        <input type="text" name="bank_name" placeholder="BCA / Mandiri / BNI / BRI" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Pemilik Rekening *</label>
                        <input type="text" name="account_name" placeholder="Sesuai rekening pengirim" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Transfer *</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Foto Bukti Transfer *</label>
                        <input type="file" name="proof" accept="image/*" required class="w-full text-sm file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold hover:file:bg-blue-100 border border-gray-200 rounded-xl px-1 py-1" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan (opsional)</label>
                    <input type="text" name="notes" placeholder="Keterangan pembayaran..." class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm" />
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl text-sm hover:bg-blue-700 transition">
                    Kirim Bukti Pembayaran
                </button>
            </form>

            @if($pendingTickets->isNotEmpty())
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-bold text-gray-700 mb-2">Bukti yang Sedang Diverifikasi</p>
                    <div class="space-y-2">
                        @foreach($pendingTickets as $t)
                            <div class="flex items-center justify-between bg-yellow-50 border border-yellow-200 rounded-xl px-3 py-2">
                                <div>
                                    <p class="text-xs font-bold text-gray-900">{{ $t->ticket_number ?? $t->id }} — {{ $t->invoice->invoice_number ?? '-' }}</p>
                                    <p class="text-[11px] text-gray-600 mt-0.5">Rp {{ number_format($t->amount, 0, ',', '.') }} • Bank: {{ $t->bank_name }}</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $t->status === 'approved' ? 'bg-green-100 text-green-700 border border-green-200' : ($t->status === 'rejected' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-yellow-100 text-yellow-700 border border-yellow-200') }}">{{ ucfirst($t->status) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Riwayat Pembayaran Berhasil -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-900 text-sm">Riwayat Pembayaran Terakhir</h3>
                <a href="{{ route('customer.history', ['tab' => 'payments']) }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua</a>
            </div>

            @if($payments->isEmpty())
                <div class="p-8 text-center text-sm text-gray-500">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                    </div>
                    <p>Belum ada pembayaran tercatat.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($payments as $payment)
                        <a href="{{ route('customer.payments.show', $payment) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                            <div>
                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $payment->invoice->invoice_number }} • {{ $payment->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">Berhasil</span>
                        </a>
                    @endforeach
                </div>
                <div class="p-4 border-t">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
    @endsection
</x-customer-layout>
