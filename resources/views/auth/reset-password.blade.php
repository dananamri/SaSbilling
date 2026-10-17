<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi - SATAK</title>
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
                <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Atur Kata Sandi Baru</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1.5">
                    Masukkan kode verifikasi OTP beserta kata sandi baru untuk akun <strong class="text-gray-800">{{ $target }}</strong>
                </p>
            </div>

            @if(session('status'))
                <div class="mb-5 p-3.5 bg-blue-50 border border-blue-200 rounded-2xl text-blue-800 text-xs flex flex-col gap-1.5">
                    <span class="font-bold">{{ session('status') }}</span>
                    @if($hintOtp)
                        <div class="mt-1 p-2 bg-white rounded-xl border border-blue-100 flex items-center justify-between">
                            <span class="text-gray-600">Kode OTP Anda:</span>
                            <span class="font-mono text-base font-extrabold text-[#0066FF] tracking-widest">{{ $hintOtp }}</span>
                        </div>
                    @endif
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-2xl text-red-600 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <!-- Input OTP -->
                <div class="mb-4">
                    <label class="block text-[11px] font-bold tracking-wider text-gray-500 uppercase mb-2">KODE OTP (6 DIGIT)</label>
                    <input type="text"
                           name="otp"
                           maxlength="6"
                           required
                           autofocus
                           value="{{ old('otp', $hintOtp) }}"
                           class="w-full text-center tracking-[8px] font-mono font-bold text-lg px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder:text-gray-300 focus:outline-none focus:border-[#00E5CC] focus:bg-white focus:ring-4 focus:ring-[#00E5CC]/15 transition"
                           placeholder="000000">
                </div>

                <!-- Input Password Baru -->
                <div class="mb-4">
                    <label class="block text-[11px] font-bold tracking-wider text-gray-500 uppercase mb-2">KATA SANDI BARU</label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm placeholder:text-gray-400 focus:outline-none focus:border-[#00E5CC] focus:bg-white focus:ring-4 focus:ring-[#00E5CC]/15 transition"
                           placeholder="Minimal 6 karakter">
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="mb-6">
                    <label class="block text-[11px] font-bold tracking-wider text-gray-500 uppercase mb-2">ULANGI KATA SANDI BARU</label>
                    <input type="password"
                           name="password_confirmation"
                           required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm placeholder:text-gray-400 focus:outline-none focus:border-[#00E5CC] focus:bg-white focus:ring-4 focus:ring-[#00E5CC]/15 transition"
                           placeholder="Ulangi kata sandi">
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-[#00E5CC] to-[#0066FF] hover:opacity-95 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-[#00E5CC]/25 transition-all duration-200 cursor-pointer">
                    Simpan Kata Sandi Baru
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center text-xs">
                <a href="{{ route('customer.password.request') }}" class="text-gray-500 hover:text-gray-700">
                    Kirim ulang kode OTP pemulihan
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
