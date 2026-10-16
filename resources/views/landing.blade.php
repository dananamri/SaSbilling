<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Portal - SATAK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex items-center justify-center overflow-x-hidden font-['Plus_Jakarta_Sans'] p-4">
    <!-- Background Accents -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute w-[600px] h-[600px] bg-[#00E5CC] rounded-full blur-[90px] opacity-15 -top-[200px] -right-[100px] animate-[float_20s_ease-in-out_infinite]"></div>
        <div class="absolute w-[500px] h-[500px] bg-[#0066FF] rounded-full blur-[90px] opacity-15 -bottom-[150px] -left-[100px] animate-[float_20s_ease-in-out_infinite_-7s]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(0,0,0,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(0,0,0,0.02)_1px,transparent_1px)] bg-[size:60px_60px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-2xl mx-auto py-6 sm:py-10">
        <!-- Logo & Title -->
        <div class="text-center mb-6 sm:mb-10">
            <img src="{{ asset('images/satak_logo.jpeg') }}" alt="SATAK" class="h-16 sm:h-20 w-auto mx-auto mb-3 sm:mb-4 rounded-2xl border-2 border-gray-200 shadow-sm">
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">SATAK Billing & Network</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1.5">Pilih pintu masuk portal sesuai kebutuhan Anda</p>
        </div>

        <!-- Portal Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <!-- 1. Portal Pelanggan -->
            <div class="bg-white/95 backdrop-blur-xl border border-gray-200/80 rounded-2xl sm:rounded-3xl p-6 sm:p-7 shadow-lg flex flex-col justify-between hover:border-blue-400 hover:shadow-xl transition group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-105 transition shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <span class="px-2.5 py-1 text-[11px] font-bold text-blue-700 bg-blue-50 rounded-full border border-blue-100">Pelanggan</span>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1.5">Portal Pelanggan</h2>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed mb-6">Cek tagihan internet, upload bukti transfer, pantau koneksi & kuota, serta ajukan upgrade paket.</p>
                </div>
                <div class="space-y-2.5">
                    <a href="{{ route('customer.login') }}" class="w-full py-3 sm:py-3.5 bg-blue-600 text-white text-xs sm:text-sm font-bold rounded-xl shadow hover:bg-blue-700 transition text-center block">
                        Masuk Pelanggan &rarr;
                    </a>
                    <a href="{{ route('customer.register') }}" class="w-full py-2.5 bg-gray-100 text-gray-700 text-xs sm:text-sm font-semibold rounded-xl hover:bg-gray-200 transition text-center block">
                        Daftar Pasang Baru
                    </a>
                </div>
            </div>

            <!-- 2. Portal Admin -->
            <div class="bg-white/95 backdrop-blur-xl border border-gray-200/80 rounded-2xl sm:rounded-3xl p-6 sm:p-7 shadow-lg flex flex-col justify-between hover:border-gray-400 hover:shadow-xl transition group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gray-900 text-white flex items-center justify-center group-hover:scale-105 transition shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="px-2.5 py-1 text-[11px] font-bold text-gray-700 bg-gray-100 rounded-full border border-gray-200">Staff / Admin</span>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1.5">Admin & Petugas</h2>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed mb-6">Kelola pelanggan, tagihan & kasir, router MikroTik, jaringan OLT, hotspot voucher, dan laporan.</p>
                </div>
                <div>
                    <a href="{{ route('login') }}" class="w-full py-3 sm:py-3.5 bg-gray-900 text-white text-xs sm:text-sm font-bold rounded-xl shadow hover:bg-black transition text-center block">
                        Masuk Admin / Staf &rarr;
                    </a>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-8">&copy; {{ date('Y') }} SATAK. All rights reserved.</p>
    </div>

    <style>
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(50px, -50px) scale(1.1); }
            50% { transform: translate(-30px, 30px) scale(0.9); }
            75% { transform: translate(30px, 50px) scale(1.05); }
        }
    </style>
</body>
</html>
