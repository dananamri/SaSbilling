<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Menunggu Konfirmasi Pembayaran - SATAK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300,400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#0A1628] text-white min-h-screen flex items-center justify-center p-4 font-['Plus_Jakarta_Sans']">
    <!-- Background Decor -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute w-[500px] h-[500px] bg-[#00E5CC] rounded-full blur-[100px] opacity-15 -top-[150px] -right-[100px]"></div>
        <div class="absolute w-[500px] h-[500px] bg-[#0066FF] rounded-full blur-[100px] opacity-15 -bottom-[150px] -left-[100px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg">
        <div class="bg-[#132240]/90 backdrop-blur-xl border border-[rgba(0,229,204,0.2)] rounded-3xl p-6 sm:p-8 shadow-2xl">
            <!-- Icon Status -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400">
                    <svg class="w-8 h-8 animate-pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
            </div>

            <h1 class="text-2xl font-bold text-center mb-2">Pendaftaran Berhasil!</h1>
            <p class="text-gray-400 text-center text-sm mb-6">
                Akun dan paket internet Anda saat ini <span class="text-amber-400 font-semibold">belum aktif</span> dan menunggu verifikasi pembayaran oleh Admin.
            </p>

            @if($customer)
                @php
                    $latestInvoice = $customer->invoices->last();
                    $latestPayment = $latestInvoice ? $latestInvoice->payments->last() : null;
                    $packageName = $customer->package->name ?? 'Paket Internet';
                    $totalAmount = $latestInvoice ? $latestInvoice->amount : ($customer->package->price ?? 0);
                    $formattedTotal = 'Rp ' . number_format($totalAmount, 0, ',', '.');
                    
                    // Admin WA setup
                    $adminPhone = $settings['admin_whatsapp'] ?? $settings['company_phone'] ?? '6281234567890';
                    $cleanPhone = preg_replace('/[^0-9]/', '', $adminPhone);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                    
                    $message = "Halo Admin SATAK, saya {$customer->name} telah melakukan pembayaran dengan paket {$packageName} dengan total pembayaran {$formattedTotal}. Mohon persetujuan dan aktivasi akun saya.";
                    $waUrl = "https://wa.me/{$cleanPhone}?text=" . rawurlencode($message);
                @endphp

                <!-- Rincian Pembayaran -->
                <div class="bg-[#0A1628]/80 rounded-2xl p-5 border border-white/5 space-y-3 mb-6">
                    <div class="flex justify-between items-center text-sm border-b border-white/5 pb-2">
                        <span class="text-gray-400">Nama Pelanggan</span>
                        <span class="font-semibold text-white">{{ $customer->name }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-b border-white/5 pb-2">
                        <span class="text-gray-400">Username / Telepon</span>
                        <span class="text-white">{{ $customer->username }} / {{ $customer->phone }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-b border-white/5 pb-2">
                        <span class="text-gray-400">Paket Dipilih</span>
                        <span class="font-semibold text-[#00E5CC]">{{ $packageName }}</span>
                    </div>
                    @if($latestPayment)
                    <div class="flex justify-between items-center text-sm border-b border-white/5 pb-2">
                        <span class="text-gray-400">Metode Pembayaran</span>
                        <span class="text-white font-medium">{{ strtoupper(str_replace('_', ' ', $latestPayment->method->value ?? $latestPayment->method)) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-gray-300 font-medium">Total Pembayaran</span>
                        <span class="text-xl font-extrabold text-[#00E5CC]">{{ $formattedTotal }}</span>
                    </div>
                </div>

                <!-- Info Rekening Transfer Admin -->
                <div class="bg-gradient-to-r from-blue-900/30 to-teal-900/30 border border-blue-500/20 rounded-2xl p-4 mb-6 text-sm">
                    <p class="text-xs uppercase tracking-wider text-blue-300 font-bold mb-2">Tujuan Pembayaran Transfer:</p>
                    <div class="text-white font-semibold">Bank / E-Wallet: {{ $settings['payment_bank_name'] ?? 'BCA' }}</div>
                    <div class="text-lg font-mono text-[#00E5CC] font-bold">{{ $settings['payment_bank_account'] ?? 'Hubungi Admin' }}</div>
                    <div class="text-gray-300 text-xs">a.n {{ $settings['payment_bank_holder'] ?? 'SATAK Billing' }}</div>
                    @if(!empty($settings['payment_qris_info']))
                        <div class="mt-2 pt-2 border-t border-white/10 text-xs text-gray-400">{{ $settings['payment_qris_info'] }}</div>
                    @endif
                </div>

                <!-- Action Button WhatsApp Admin -->
                <div class="space-y-3">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-3 py-4 px-6 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl shadow-[0_0_30px_rgba(16,185,129,0.3)] transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.769.832 2.791.832 3.181 0 5.767-2.587 5.767-5.766.001-3.182-2.585-5.768-5.767-5.768zm7.399 5.766c.001 4.079-3.321 7.399-7.399 7.399-1.306 0-2.529-.344-3.593-.943l-4.438 1.162 1.183-4.324c-.692-1.127-1.085-2.451-1.085-3.874 0-4.08 3.32-7.4 7.399-7.4 4.08 0 7.4 3.32 7.4 7.4z"/>
                        </svg>
                        Konfirmasi ke WhatsApp Admin
                    </a>

                    <a href="{{ route('customer.login') }}" class="w-full inline-flex items-center justify-center py-3 px-6 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white font-medium rounded-xl text-sm transition">
                        Sudah Konfirmasi? Coba Masuk
                    </a>
                </div>
            @else
                <p class="text-sm text-center text-gray-400 mb-6">Pendaftaran Anda telah dicatat. Silakan hubungi admin untuk konfirmasi pembayaran.</p>
                <a href="{{ route('customer.login') }}" class="block text-center text-sm text-[#00E5CC] hover:underline">
                    Kembali ke Halaman Masuk
                </a>
            @endif
        </div>
    </div>
</body>
</html>