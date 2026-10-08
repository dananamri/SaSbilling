<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', 'Portal Pelanggan') - SATAK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300,400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen font-['Plus_Jakarta_Sans']" x-data="{ mobileMenuOpen: false, notifOpen: false }">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/satak_logo.jpeg') }}" alt="SATAK" class="h-8 w-auto rounded">
                    <div class="flex flex-col">
                        <span class="text-sm font-extrabold text-gray-800 tracking-wider">SATAK</span>
                        <span class="text-[8px] font-medium text-gray-400 tracking-[2px] uppercase">Konek Terus</span>
                    </div>
                </div>
                <!-- Desktop Nav -->
                <nav class="hidden lg:flex items-center gap-5">
                    <a href="{{ route('customer.dashboard') }}" class="text-sm font-medium {{ request()->routeIs('customer.dashboard') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Dashboard</a>
                    <a href="{{ route('customer.invoices') }}" class="text-sm font-medium {{ request()->routeIs('customer.invoices*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Tagihan</a>
                    <a href="{{ route('customer.payments') }}" class="text-sm font-medium {{ request()->routeIs('customer.payments*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Pembayaran</a>
                    <a href="{{ route('customer.packages') }}" class="text-sm font-medium {{ request()->routeIs('customer.packages*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Paket Saya</a>
                    <a href="{{ route('customer.connection') }}" class="text-sm font-medium {{ request()->routeIs('customer.connection*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Status Koneksi</a>
                    <a href="{{ route('customer.usage') }}" class="text-sm font-medium {{ request()->routeIs('customer.usage*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Pemakaian</a>
                    <a href="{{ route('customer.tickets') }}" class="text-sm font-medium {{ request()->routeIs('customer.tickets*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Pengaduan</a>
                    <!-- Notifikasi Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @php
                                $unreadCount = auth('customer')->user() ? \App\Models\WhatsAppNotification::where('customer_id', auth('customer')->user()->id)->where('status', 'pending')->count() : 0;
                            @endphp
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $unreadCount }}</span>
                            @endif
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <a href="{{ route('customer.notifications') }}" class="text-sm font-medium text-blue-600 hover:underline">Lihat Semua Notifikasi</a>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @php
                                    $recentNotifs = auth('customer')->user() ? \App\Models\WhatsAppNotification::where('customer_id', auth('customer')->user()->id)->orderByDesc('created_at')->limit(5)->get() : collect();
                                @endphp
                                @forelse($recentNotifs as $notif)
                                    <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-50">
                                        <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $notif->type->value)) }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($notif->message, 60) }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500">Tidak ada notifikasi</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#00E5CC] to-[#0066FF] rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(auth('customer')->user()->name ?? 'P', 0, 1)) }}
                            </div>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ auth('customer')->user()->name ?? 'Pelanggan' }}</p>
                                <p class="text-xs text-gray-500">{{ auth('customer')->user()->email ?? '-' }}</p>
                            </div>
                            <a href="{{ route('customer.profile') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Profil Saya</a>
                            <a href="{{ route('customer.settings') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Pengaturan</a>
                            <form method="POST" action="{{ route('customer.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Keluar</button>
                            </form>
                        </div>
                    </div>
                </nav>
                <div class="flex items-center gap-3">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <form method="POST" action="{{ route('customer.logout') }}" class="hidden lg:block">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="lg:hidden border-t border-gray-200 bg-white" style="display: none;">
            <nav class="px-4 py-2 space-y-1">
                <a href="{{ route('customer.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('customer.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Dashboard</a>
                <a href="{{ route('customer.invoices') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('customer.invoices*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Tagihan</a>
                <a href="{{ route('customer.payments') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('customer.payments*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Pembayaran</a>
                <a href="{{ route('customer.packages') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('customer.packages*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Paket Saya</a>
                <a href="{{ route('customer.connection') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('customer.connection*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Status Koneksi</a>
                <a href="{{ route('customer.usage') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('customer.usage*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Pemakaian</a>
                <a href="{{ route('customer.tickets') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('customer.tickets*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Pengaduan</a>
                <a href="{{ route('customer.notifications') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('customer.notifications*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Notifikasi</a>
                <a href="{{ route('customer.profile') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('customer.profile*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Profil Saya</a>
                <a href="{{ route('customer.settings') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('customer.settings*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Pengaturan</a>
                <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-3 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-gray-50">Keluar</button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/satak_logo.jpeg') }}" alt="SATAK" class="h-6 w-auto rounded">
                    <span class="text-sm font-semibold text-gray-600">SATAK - Konek Terus</span>
                </div>
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} SATAK. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
