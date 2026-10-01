<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Pelanggan') - SATAK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300,400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen font-['Plus_Jakarta_Sans']">
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
                <nav class="hidden sm:flex items-center gap-6">
                    <a href="{{ route('customer.dashboard') }}" class="text-sm font-medium {{ request()->routeIs('customer.dashboard') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Dashboard</a>
                    <a href="{{ route('customer.invoices') }}" class="text-sm font-medium {{ request()->routeIs('customer.invoices*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Tagihan</a>
                    <a href="{{ route('customer.payments') }}" class="text-sm font-medium {{ request()->routeIs('customer.payments*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Pembayaran</a>
                </nav>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600 hidden sm:block">{{ auth('customer')->user()->name ?? 'Pelanggan' }}</span>
                    <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
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
