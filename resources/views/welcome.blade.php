<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SATAK - Konek Terus | Kelola Keuangan Jadi Lebih Mudah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white text-gray-900 min-h-screen overflow-x-hidden font-['Plus_Jakarta_Sans']">
    <!-- Background -->
    <div class="fixed inset-0 z-0 overflow-hidden">
        <div class="absolute w-[600px] h-[600px] bg-[#00E5CC] rounded-full blur-[80px] opacity-20 -top-[200px] -right-[100px] animate-[float_20s_ease-in-out_infinite]"></div>
        <div class="absolute w-[500px] h-[500px] bg-[#0066FF] rounded-full blur-[80px] opacity-20 -bottom-[150px] -left-[100px] animate-[float_20s_ease-in-out_infinite_-7s]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(0,0,0,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(0,0,0,0.02)_1px,transparent_1px)] bg-[size:60px_60px]"></div>
    </div>

    <div class="relative z-10 max-w-[1200px] mx-auto px-6 min-h-screen flex flex-col">
        <!-- Header -->
        <header class="flex justify-between items-center py-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/satak_logo.jpeg') }}" alt="SATAK" class="h-10 sm:h-12 w-auto rounded-lg border-2 border-gray-200">
            </div>
            <div class="hidden sm:flex items-center gap-2 bg-[rgba(0,229,204,0.1)] border border-[rgba(0,229,204,0.3)] px-4 py-2 rounded-full text-xs font-semibold text-[#00E5CC]">
                <span class="w-2 h-2 bg-[#00E5CC] rounded-full animate-pulse"></span>
                Sistem Online
            </div>
        </header>

        <!-- Hero Section -->
        <section class="flex-1 flex flex-col justify-center items-center text-center py-16">
            <div class="bg-[rgba(0,102,255,0.08)] border border-[rgba(0,102,255,0.2)] px-6 py-2.5 rounded-full text-[13px] font-semibold text-blue-600 mb-8 inline-flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
                Internet Cepat & Terpercaya
            </div>
            
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6 max-w-[800px]">
                Kelola Keuangan Jadi <br>
                <span class="bg-gradient-to-r from-[#00E5CC] to-[#0066FF] bg-clip-text text-transparent">Lebih Mudah</span>
            </h1>
            
            <p class="text-lg md:text-xl font-normal text-gray-500 max-w-[600px] leading-relaxed mb-12">
                Platform billing internet modern untuk mengelola pembayaran, 
                paket, dan pelanggan dalam satu tempat yang terintegrasi.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="relative inline-flex items-center justify-center gap-3 px-10 py-4 bg-gradient-to-r from-[#00E5CC] to-[#0066FF] text-white text-lg font-bold rounded-2xl transition-all duration-300 shadow-[0_0_40px_rgba(0,229,204,0.3)] overflow-hidden group hover:-translate-y-1 hover:shadow-[0_0_60px_rgba(0,229,204,0.5)] active:-translate-y-0.5">
                    <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-500"></span>
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Masuk Admin
                </a>
                <a href="{{ route('customer.login') }}" class="relative inline-flex items-center justify-center gap-3 px-10 py-4 bg-white border-2 border-[#0066FF] text-[#0066FF] text-lg font-bold rounded-2xl transition-all duration-300 overflow-hidden group hover:-translate-y-1 hover:bg-[#0066FF] hover:text-white hover:shadow-[0_0_40px_rgba(0,102,255,0.3)] active:-translate-y-0.5">
                    <span class="absolute inset-0 bg-gradient-to-r from-transparent via-[#0066FF]/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-500"></span>
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Masuk Pelanggan
                </a>
            </div>

        <!-- Packages Section -->
        <section class="py-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-3">
                    Paket Internet <span class="bg-gradient-to-r from-[#00E5CC] to-[#0066FF] bg-clip-text text-transparent">Pilihan</span>
                </h2>
                <p class="text-base text-gray-500 max-w-[500px] mx-auto">
                    Pilih paket yang sesuai dengan kebutuhan internet Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                @foreach($packages as $package)
                    <div class="relative bg-white border border-gray-200 rounded-3xl p-8 transition-all duration-500 cursor-pointer hover:-translate-y-2 hover:border-[rgba(0,229,204,0.5)] hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.1)] overflow-hidden group">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#00E5CC] to-[#0066FF] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        @if($package['featured'])
                            <div class="absolute top-5 right-5 bg-gradient-to-r from-[#00E5CC] to-[#0066FF] text-[#0A1628] px-3.5 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-wider">Populer</div>
                        @endif
                        
                        <div class="w-14 h-14 bg-[rgba(0,229,204,0.15)] rounded-2xl flex items-center justify-center mb-5">
                            @if($package['icon'] === 'wifi')
                                <svg class="w-7 h-7 text-[#00E5CC]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
                                    <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
                                    <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
                                    <circle cx="12" cy="20" r="1"/>
                                </svg>
                            @elseif($package['icon'] === 'bolt')
                                <svg class="w-7 h-7 text-[#00E5CC]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                                </svg>
                            @else
                                <svg class="w-7 h-7 text-[#00E5CC]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                                    <path d="M8 21h8M12 17v4"/>
                                </svg>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $package['name'] }}</h3>
                        
                        <div class="flex items-baseline gap-1 mb-4">
                            <span class="text-4xl font-extrabold bg-gradient-to-r from-[#00E5CC] to-[#0066FF] bg-clip-text text-transparent">{{ $package['speed'] }}</span>
                            <span class="text-sm text-gray-500 font-medium">Mbps</span>
                        </div>
                        
                        <ul class="mb-6">
                            @foreach($package['features'] as $feature)
                                <li class="flex items-center gap-2.5 py-2 text-sm text-gray-600">
                                    <svg class="w-[18px] h-[18px] text-[#00E5CC] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M20 6L9 17l-5-5"/>
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        
                        <div class="flex items-baseline gap-1 pt-5 border-t border-gray-100">
                            <span class="text-base font-semibold text-gray-500">Rp</span>
                            <span class="text-3xl font-extrabold text-gray-900">{{ $package['price'] }}</span>
                            <span class="text-sm text-gray-500">/bulan</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Footer CTA -->
        <section class="text-center py-16 border-t border-white/5">
            <p class="text-lg text-slate-400 mb-6">
                Sudah punya akun? <strong class="text-white">Masuk sekarang</strong> untuk mengelola billing Anda
            </p>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-3 px-12 py-4.5 bg-gradient-to-r from-[#00E5CC] to-[#0066FF] text-[#0A1628] text-lg font-bold rounded-2xl transition-all duration-300 shadow-[0_0_40px_rgba(0,229,204,0.3)] hover:-translate-y-1 hover:shadow-[0_0_60px_rgba(0,229,204,0.5)]">
                Masuk ke Dashboard
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </section>

        <!-- Footer -->
        <footer class="text-center py-8 border-t border-white/5">
            <p class="text-[13px] text-slate-600">
                &copy; 2026 <a href="{{ route('home') }}" class="text-[#00E5CC] hover:underline">SATAK</a> - Konek Terus. All rights reserved.
            </p>
        </footer>
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
