<x-customer-layout>
    @section('title', 'Detail Tagihan')
    @section('header', 'Detail Tagihan')

    @section('content')
    <div class="space-y-5">
        <!-- Header Tagihan Card -->
        <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 rounded-3xl p-6 sm:p-7 text-white relative overflow-hidden shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs text-gray-300">Invoice</p>
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight mt-0.5">{{ $invoice->invoice_number }}</h2>
                    <p class="text-xs text-gray-300 mt-1">Periode {{ $invoice->period }} • Jatuh tempo {{ $invoice->due_date->format('d M Y') }}</p>
                </div>
                <span class="self-start sm:self-auto px-3.5 py-1.5 rounded-full text-xs font-bold {{ $invoice->status->value === 'paid' ? 'bg-emerald-400 text-emerald-950' : ($invoice->status->value === 'overdue' ? 'bg-red-400 text-red-950' : 'bg-yellow-300 text-yellow-950') }}">
                    {{ ucfirst($invoice->status->value) }}
                </span>
            </div>

            <div class="mt-5 pt-5 border-t border-white/15 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                <div>
                    <p class="text-xs text-gray-300">Sisa Tagihan Aktif</p>
                    <p class="text-2xl sm:text-3xl font-black mt-0.5">Rp {{ number_format($invoice->remaining(), 0, ',', '.') }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('customer.invoices.download', $invoice) }}" class="px-4 py-2 rounded-xl bg-white text-gray-900 text-xs font-bold hover:bg-gray-100 transition">Download</a>
                    <a href="{{ route('customer.invoices.print', $invoice) }}" target="_blank" class="px-4 py-2 rounded-xl bg-white/15 border border-white/20 text-white text-xs font-bold hover:bg-white/25 transition">Print</a>
                </div>
            </div>
        </div>

        <!-- Rincian Tagihan -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Rincian Tagihan</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-gray-500">{{ $invoice->customer->package->name ?? 'Paket Internet' }}</span>
                    <span class="font-semibold text-gray-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                </div>
                @if($invoice->late_fee > 0)
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-gray-500">Denda Keterlambatan</span>
                    <span class="font-semibold text-red-600">Rp {{ number_format($invoice->late_fee, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($invoice->discount > 0)
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-gray-500">Diskon</span>
                    <span class="font-semibold text-green-600">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between py-2.5">
                    <span class="font-bold text-gray-900">Total Tagihan</span>
                    <span class="font-black text-gray-900">Rp {{ number_format($invoice->totalDue(), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2.5 pt-3 border-t border-dashed border-gray-200">
                    <span class="text-gray-500">Sudah Dibayar</span>
                    <span class="font-semibold text-green-600">Rp {{ number_format($invoice->totalPaid(), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Upload Bukti Bayar (khusus tagihan belum lunas) -->
        @if($invoice->status->value !== 'paid' && $invoice->remaining() > 0)
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Konfirmasi Pembayaran</h3>
                        <p class="text-xs text-gray-500 mt-1">Transfer ke rekening perusahaan, lalu unggah bukti di sini. Nominal transfer: <span class="font-bold text-gray-800">Rp {{ number_format($invoice->remaining(), 0, ',', '.') }}</span></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-700 border border-yellow-200 text-[11px] font-bold whitespace-nowrap">Menunggu Pembayaran</span>
                </div>

                <form action="{{ route('customer.payment-tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}" />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nominal Transfer *</label>
                            <input type="number" name="amount" min="1000" max="{{ (int) $invoice->remaining() }}" value="{{ (int) $invoice->remaining() }}" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Transfer *</label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Bank Pengirim *</label>
                            <input type="text" name="bank_name" placeholder="BCA / Mandiri / BNI / BRI" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Pemilik Rekening *</label>
                            <input type="text" name="account_name" placeholder="Sesuai rekening pengirim" required class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Foto Bukti Transfer *</label>
                            <input type="file" name="proof" accept="image/*" required class="w-full text-sm file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold hover:file:bg-blue-100 border border-gray-200 rounded-xl px-1 py-1" />
                            <p class="text-[11px] text-gray-400 mt-1">Format JPG/PNG, maks 4MB. Pastikan nominal & tanggal terlihat jelas.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan (opsional)</label>
                            <input type="text" name="notes" placeholder="e.g. Bayar via m-banking sore ini" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm" />
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl text-sm hover:bg-blue-700 transition">Kirim Bukti Pembayaran</button>
                </form>
            </div>
        @endif

        <!-- Riwayat Pembayaran -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Riwayat Pembayaran Tagihan Ini</h3>
            @if($invoice->payments->isNotEmpty())
                <div class="divide-y divide-gray-100">
                    @foreach($invoice->payments as $payment)
                        <div class="py-3 flex items-center justify-between text-sm">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $payment->created_at->format('d M Y H:i') }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $payment->method?->label() ?? 'Transfer' }} • {{ ucfirst($payment->status) }}</p>
                            </div>
                            <p class="font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center text-sm text-gray-400 bg-gray-50 rounded-xl border border-dashed border-gray-200">Belum ada pembayaran tercatat untuk tagihan ini.</div>
            @endif
        </div>
    </div>
    @endsection
</x-customer-layout>
