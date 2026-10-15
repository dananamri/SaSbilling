<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Pelanggan') - SATAK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen font-['Plus_Jakarta_Sans'] pb-28 lg:pb-0" x-data="{ mobileMenuOpen: false }">
    <!-- Header -->
    <header class="bg-white/95 backdrop-blur border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/satak_logo.jpeg') }}" alt="SATAK" class="h-9 w-auto rounded-lg shadow-sm">
                    <div class="flex flex-col">
                        <span class="text-sm font-extrabold text-gray-900 tracking-wider">SATAK</span>
                        <span class="text-[9px] font-semibold text-blue-600 tracking-[2px] uppercase">Portal Pelanggan</span>
                    </div>
                </a>

                <!-- Desktop Nav (5 Tab Utama) -->
                <nav class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('customer.dashboard') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('customer.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Beranda
                    </a>
                    <a href="{{ route('customer.packages') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('customer.packages*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Paket Saya
                    </a>
                    <a href="{{ route('customer.history') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('customer.history*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Riwayat
                    </a>
                    <a href="{{ route('customer.payments') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('customer.payments*') || request()->routeIs('customer.invoices*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Pembayaran
                    </a>
                    <a href="{{ route('customer.profile') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('customer.profile*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Profil
                    </a>
                </nav>

                <!-- Header Actions -->
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <a href="{{ route('customer.notifications') }}" class="relative p-2 min-w-[40px] min-h-[40px] flex items-center justify-center text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @php
                            $unreadCount = auth('customer')->user() ? \App\Models\WhatsAppNotification::where('customer_id', auth('customer')->user()->id)->where('status', 'pending')->count() : 0;
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $unreadCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('customer.tickets') }}" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Bantuan
                    </a>

                    <!-- Mobile Menu (sub-menu pendukung) -->
                    <div class="relative lg:hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 min-w-[40px] min-h-[40px] flex items-center justify-center text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition" aria-label="Menu lainnya">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 text-sm" style="display: none;" x-cloak>
                            <a href="{{ route('customer.connection') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-50">Status Koneksi</a>
                            <a href="{{ route('customer.usage') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-50">Pemakaian Kuota</a>
                            <a href="{{ route('customer.tickets') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-50">Laporan Gangguan</a>
                            <a href="{{ route('customer.settings') }}" class="block px-4 py-2.5 text-gray-700 hover:bg-gray-50">Pengaturan</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-red-600 font-medium hover:bg-red-50">Keluar</button>
                            </form>
                        </div>
                    </div>

                    <!-- Profile Dropdown (Desktop) -->
                    <div class="relative hidden lg:block" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-full hover:ring-2 hover:ring-blue-100 transition">
                            <div class="w-9 h-9 bg-gradient-to-br from-[#00E5CC] to-[#0066FF] rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                {{ strtoupper(substr(auth('customer')->user()->name ?? 'P', 0, 1)) }}
                            </div>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 text-sm" style="display: none;">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="font-bold text-gray-900 truncate">{{ auth('customer')->user()->name ?? 'Pelanggan' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth('customer')->user()->phone }}</p>
                            </div>
                            <a href="{{ route('customer.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Profil Saya</a>
                            <a href="{{ route('customer.connection') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Status Koneksi</a>
                            <a href="{{ route('customer.usage') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Pemakaian Kuota</a>
                            <a href="{{ route('customer.tickets') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Laporan Gangguan</a>
                            <a href="{{ route('customer.settings') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Pengaturan</a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 font-medium hover:bg-red-50">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-5 sm:py-7">
        @if(session('success'))
            <div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-800 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer Desktop -->
    <footer class="hidden lg:block bg-white border-t border-gray-200 mt-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/satak_logo.jpeg') }}" alt="SATAK" class="h-6 w-auto rounded">
                    <span class="text-xs font-semibold text-gray-600">SATAK - Layanan Internet Cepat & Stabil</span>
                </div>
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} SATAK. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- MOBILE BOTTOM NAVIGATION BAR (5 TAB UTAMA) -->
    <nav class="lg:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur border-t border-gray-200 z-50 px-2 pt-1.5 shadow-lg" style="padding-bottom: calc(0.375rem + env(safe-area-inset-bottom));">
        <div class="grid grid-cols-5 gap-1 max-w-md mx-auto">
            <!-- 1. Beranda -->
            <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center justify-center py-2 min-h-[56px] rounded-xl transition {{ request()->routeIs('customer.dashboard') ? 'text-blue-600 font-bold' : 'text-gray-400 hover:text-gray-600 font-medium' }}">
                <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px]">Beranda</span>
            </a>

            <!-- 2. Paket Saya -->
            <a href="{{ route('customer.packages') }}" class="flex flex-col items-center justify-center py-2 min-h-[56px] rounded-xl transition {{ request()->routeIs('customer.packages*') ? 'text-blue-600 font-bold' : 'text-gray-400 hover:text-gray-600 font-medium' }}">
                <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span class="text-[10px]">Paket</span>
            </a>

            <!-- 3. Riwayat -->
            <a href="{{ route('customer.history') }}" class="flex flex-col items-center justify-center py-2 min-h-[56px] rounded-xl transition {{ request()->routeIs('customer.history*') ? 'text-blue-600 font-bold' : 'text-gray-400 hover:text-gray-600 font-medium' }}">
                <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-[10px]">Riwayat</span>
            </a>

            <!-- 4. Pembayaran -->
            <a href="{{ route('customer.payments') }}" class="flex flex-col items-center justify-center py-2 min-h-[56px] rounded-xl transition {{ request()->routeIs('customer.payments*') || request()->routeIs('customer.invoices*') ? 'text-blue-600 font-bold' : 'text-gray-400 hover:text-gray-600 font-medium' }}">
                <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span class="text-[10px]">Bayar</span>
            </a>

            <!-- 5. Profil -->
            <a href="{{ route('customer.profile') }}" class="flex flex-col items-center justify-center py-2 min-h-[56px] rounded-xl transition {{ request()->routeIs('customer.profile*') ? 'text-blue-600 font-bold' : 'text-gray-400 hover:text-gray-600 font-medium' }}">
                <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-[10px]">Profil</span>
            </a>
        </div>
    </nav>
</body>
</html>
