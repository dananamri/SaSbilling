<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Masuk - SATAK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white text-gray-900 min-h-screen flex items-center justify-center overflow-x-hidden font-['Plus_Jakarta_Sans']">
    <!-- Background -->
    <div class="fixed inset-0 z-0 overflow-hidden">
        <div class="absolute w-[600px] h-[600px] bg-[#00E5CC] rounded-full blur-[80px] opacity-20 -top-[200px] -right-[100px] animate-[float_20s_ease-in-out_infinite]"></div>
        <div class="absolute w-[500px] h-[500px] bg-[#0066FF] rounded-full blur-[80px] opacity-20 -bottom-[150px] -left-[100px] animate-[float_20s_ease-in-out_infinite_-7s]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(0,0,0,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(0,0,0,0.02)_1px,transparent_1px)] bg-[size:60px_60px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-[440px] px-4 sm:px-6 my-6">
        <div class="bg-white/80 sm:bg-transparent backdrop-blur-xl border border-gray-200/50 rounded-2xl sm:rounded-3xl p-6 sm:p-12 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.1)]">
            <!-- Logo -->
            <div class="flex flex-col items-center mb-6 sm:mb-8">
                <img src="{{ asset('images/satak_logo.jpeg') }}" alt="SATAK" class="h-20 sm:h-24 w-auto mb-4 rounded-xl border-2 border-gray-200">
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-[15px] transition-all duration-300 placeholder:text-gray-400 focus:outline-none focus:border-[#00E5CC] focus:bg-white focus:shadow-[0_0_0_3px_rgba(0,229,204,0.1)]" placeholder="Masukkan username">
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-[15px] transition-all duration-300 placeholder:text-gray-400 focus:outline-none focus:border-[#00E5CC] focus:bg-white focus:shadow-[0_0_0_3px_rgba(0,229,204,0.1)]" placeholder="Masukkan password">
                </div>

                <div class="flex justify-between items-center mb-6">
                    <label class="flex items-center gap-2 text-[13px] text-gray-500 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 accent-[#00E5CC]">
                        Ingat saya
                    </label>
                    <a href="#" class="text-[13px] text-[#0066FF] font-medium hover:underline">Lupa Password?</a>
                </div>

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-[#00E5CC] to-[#0066FF] text-white text-base font-bold rounded-xl transition-all duration-300 shadow-[0_0_40px_rgba(0,229,204,0.2)] hover:-translate-y-0.5 hover:shadow-[0_0_60px_rgba(0,229,204,0.3)] active:translate-y-0">
                    Masuk
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center gap-4 my-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400">atau</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <!-- Back Link -->
            <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 text-sm text-gray-500 transition-colors duration-300 hover:text-[#0066FF]">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
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
