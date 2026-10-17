<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - SATAK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white text-gray-900 min-h-screen flex items-center justify-center p-4 font-['Plus_Jakarta_Sans'] relative overflow-x-hidden selection:bg-[#00E5CC] selection:text-gray-900">

    <!-- Background Asli SATAK -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute w-[600px] h-[600px] bg-[#00E5CC] rounded-full blur-[80px] opacity-20 -top-[200px] -right-[100px] animate-[float_20s_ease-in-out_infinite]"></div>
        <div class="absolute w-[500px] h-[500px] bg-[#0066FF] rounded-full blur-[80px] opacity-20 -bottom-[150px] -left-[100px] animate-[float_20s_ease-in-out_infinite_-7s]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(0,0,0,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(0,0,0,0.02)_1px,transparent_1px)] bg-[size:60px_60px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-[460px] my-6">
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-6 sm:p-10 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.12)] border border-gray-200/70">

            <div class="flex justify-center mb-5">
                <a href="{{ route('login') }}">
                    <img src="{{ asset('images/satak_logo.jpeg') }}" alt="SATAK" class="h-16 w-auto rounded-2xl border-2 border-gray-100 shadow-sm">
                </a>
            </div>

            <div class="text-center mb-6">
                <span class="inline-block px-3 py-1 text-[11px] font-extrabold text-[#0066FF] bg-blue-50 rounded-full border border-blue-100 uppercase tracking-wider mb-2">
                    Pemulihan Akun ({{ $type === 'admin' ? 'Admin / Staf' : 'Pelanggan' }})
                </span>
                <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Lupa Kata Sandi?</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1.5">
                    Masukkan {{ $type === 'admin' ? 'username atau email admin' : 'username, nomor telepon, atau email' }} Anda untuk menerima kode verifikasi pemulihan.
                </p>
            </div>

            @if($errors->any())
                <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-2xl text-red-600 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="mb-5">
                    <label class="block text-[11px] font-bold tracking-wider text-gray-500 uppercase mb-2">IDENTITAS AKUN</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input type="text"
                               name="identity"
                               value="{{ old('identity') }}"
                               required
                               autofocus
                               class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm placeholder:text-gray-400 focus:outline-none focus:border-[#00E5CC] focus:bg-white focus:ring-4 focus:ring-[#00E5CC]/15 transition"
                               placeholder="{{ $type === 'admin' ? 'Masukkan username atau email' : 'Masukkan telepon / username / email' }}">
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-[#00E5CC] to-[#0066FF] hover:opacity-95 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-[#00E5CC]/25 transition-all duration-200 cursor-pointer">
                    Lanjutkan & Dapatkan Kode OTP &rarr;
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 flex flex-col gap-2 text-center text-xs">
                <a href="{{ route('login', ['role' => $type]) }}" class="text-gray-600 hover:text-gray-900 font-semibold">
                    &larr; Kembali ke Halaman Masuk
                </a>
            </div>

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
