<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SATAK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white text-gray-900 min-h-screen flex items-center justify-center p-4 font-['Plus_Jakarta_Sans'] relative overflow-x-hidden selection:bg-[#00E5CC] selection:text-gray-900"
      x-data="{
          role: '{{ request('role') === 'admin' ? 'admin' : (request('role') === 'customer' ? 'customer' : (old('role', 'customer'))) }}',
          showPassword: false
      }">

    <!-- Background Asli SATAK (Putih Bersih + Aksen Cyan #00E5CC & Biru #0066FF) -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute w-[600px] h-[600px] bg-[#00E5CC] rounded-full blur-[80px] opacity-20 -top-[200px] -right-[100px] animate-[float_20s_ease-in-out_infinite]"></div>
        <div class="absolute w-[500px] h-[500px] bg-[#0066FF] rounded-full blur-[80px] opacity-20 -bottom-[150px] -left-[100px] animate-[float_20s_ease-in-out_infinite_-7s]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(0,0,0,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(0,0,0,0.02)_1px,transparent_1px)] bg-[size:60px_60px]"></div>
    </div>

    <!-- Login Modal Card -->
    <div class="relative z-10 w-full max-w-[460px] my-6">
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-6 sm:p-10 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.12)] border border-gray-200/70">

            <!-- Logo di Tengah -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/satak_logo.jpeg') }}" alt="SATAK" class="h-16 w-auto rounded-2xl border-2 border-gray-100 shadow-sm">
            </div>

            <!-- Pill Toggle Switcher (Pelanggan / Admin) -->
            <div class="bg-gray-100 p-1.5 rounded-2xl flex items-center mb-5 text-sm font-bold relative border border-gray-200/60">
                <button type="button"
                        @click="role = 'customer'"
                        :class="role === 'customer' ? 'bg-white text-gray-900 shadow-sm font-extrabold' : 'text-gray-500 hover:text-gray-800 font-semibold'"
                        class="flex-1 py-2.5 rounded-xl transition-all duration-200 text-center cursor-pointer">
                    Pelanggan
                </button>
                <button type="button"
                        @click="role = 'admin'"
                        :class="role === 'admin' ? 'bg-white text-gray-900 shadow-sm font-extrabold' : 'text-gray-500 hover:text-gray-800 font-semibold'"
                        class="flex-1 py-2.5 rounded-xl transition-all duration-200 text-center cursor-pointer">
                    Admin
                </button>
            </div>

            <!-- Banner Deskripsi Role Aktif (Border Biru seperti di gambar) -->
            <div class="border border-[#0066FF] rounded-2xl p-4 mb-6 bg-blue-50/40">
                <template x-if="role === 'customer'">
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Masuk sebagai Pelanggan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Cek tagihan, pembayaran & status internet</p>
                    </div>
                </template>
                <template x-if="role === 'admin'">
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Masuk sebagai Admin</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola pelanggan, tagihan & laporan</p>
                    </div>
                </template>
            </div>

            <!-- Alert Error -->
            @if($errors->any())
                <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-2xl text-red-600 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form Interaktif: Action berubah sesuai tab yang dipilih -->
            <form method="POST" :action="role === 'customer' ? '{{ route('customer.login.post') }}' : '{{ route('login.post') }}'">
                @csrf
                <input type="hidden" name="role" :value="role">

                <!-- Field 1: Username / Email / Telepon -->
                <div class="mb-4">
                    <label class="block text-[11px] font-bold tracking-wider text-gray-500 uppercase mb-2" x-text="role === 'customer' ? 'USERNAME / TELEPON / EMAIL' : 'USERNAME / EMAIL'"></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <!-- Input untuk tab Admin -->
                        <template x-if="role === 'admin'">
                            <input type="text"
                                   name="username"
                                   value="{{ old('username') }}"
                                   required
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm placeholder:text-gray-400 focus:outline-none focus:border-[#00E5CC] focus:bg-white focus:ring-4 focus:ring-[#00E5CC]/15 transition"
                                   placeholder="admin@satak.net / username">
                        </template>
                        <!-- Input untuk tab Pelanggan -->
                        <template x-if="role === 'customer'">
                            <input type="text"
                                   name="login"
                                   value="{{ old('login') }}"
                                   required
                                   class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm placeholder:text-gray-400 focus:outline-none focus:border-[#00E5CC] focus:bg-white focus:ring-4 focus:ring-[#00E5CC]/15 transition"
                                   placeholder="0812xxxxxx / username">
                        </template>
                    </div>
                </div>

                <!-- Field 2: Password -->
                <div class="mb-3">
                    <label class="block text-[11px] font-bold tracking-wider text-gray-500 uppercase mb-2">PASSWORD</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input :type="showPassword ? 'text' : 'password'"
                               name="password"
                               required
                               class="w-full pl-11 pr-11 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm placeholder:text-gray-400 focus:outline-none focus:border-[#00E5CC] focus:bg-white focus:ring-4 focus:ring-[#00E5CC]/15 transition"
                               placeholder="••••••••">
                        <button type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                            <template x-if="!showPassword">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </template>
                            <template x-if="showPassword">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </template>
                        </button>
                    </div>
                </div>

                <!-- Lupa Password Link -->
                <div class="flex justify-end mb-6">
                    <a :href="role === 'customer' ? '{{ route('customer.password.request') }}' : '{{ route('admin.password.request') }}'" class="text-xs text-[#0066FF] font-medium hover:underline">
                        Lupa Password?
                    </a>
                </div>

                <!-- Tombol Submit (Gradient Cyan #00E5CC ke Biru #0066FF) -->
                <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-[#00E5CC] to-[#0066FF] hover:opacity-95 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-[#00E5CC]/25 transition-all duration-200 active:scale-[0.99] cursor-pointer">
                    Masuk
                </button>
            </form>

            <!-- Link Daftar Akun (khusus tab Pelanggan) -->
            <div x-show="role === 'customer'" class="mt-5 text-center text-xs text-gray-500" x-cloak>
                Belum punya akun?
                <a href="{{ route('customer.register') }}" class="text-[#0066FF] font-bold hover:underline ml-0.5">Daftar</a>
            </div>

            <!-- Footer Copyright -->
            <div class="mt-6 text-center text-[11px] text-gray-400">
                &copy; {{ date('Y') }} SATAK. All rights reserved.
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
