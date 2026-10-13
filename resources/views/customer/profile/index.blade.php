<x-customer-layout>
    @section('title', 'Profil Saya')
    @section('header', 'Profil')

    @section('content')
    <div class="space-y-6">
        <!-- Header Card Profil -->
        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-blue-800 rounded-3xl p-5 sm:p-7 text-white relative overflow-hidden shadow-sm">
            <div class="absolute -right-8 -top-6 w-40 h-40 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-5">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-white/15 backdrop-blur border border-white/20 flex items-center justify-center text-white text-2xl sm:text-3xl font-black shrink-0">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight truncate">{{ $customer->name }}</h2>
                    <p class="text-sm text-blue-100 mt-1">ID: <span class="font-mono font-bold">CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</span> • Bergabung {{ $customer->joined_at?->format('d M Y') ?? '-' }}</p>
                    <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                        <span class="px-3 py-1 rounded-full font-bold {{ $customer->status->value === 'active' ? 'bg-emerald-400 text-emerald-950' : 'bg-red-400 text-red-950' }}">
                            {{ ucfirst($customer->status->value) }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-white/15 backdrop-blur border border-white/20 font-semibold">
                            {{ $customer->package->name ?? 'Tanpa Paket' }} @if($customer->package) • {{ $customer->package->speed }} @endif
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('customer.logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-white text-blue-700 text-xs font-bold hover:bg-blue-50 transition">Keluar Akun</button>
                </form>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">◯</span>
                    Informasi Akun
                </h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4 py-2 border-b border-gray-100">
                        <dt class="text-gray-500">Nama Lengkap</dt>
                        <dd class="font-semibold text-gray-900 text-right">{{ $customer->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 border-b border-gray-100">
                        <dt class="text-gray-500">Telepon / Login</dt>
                        <dd class="font-semibold text-gray-900 text-right">{{ $customer->phone }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 border-b border-gray-100">
                        <dt class="text-gray-500">Email</dt>
                        <dd class="font-semibold text-gray-900 text-right truncate max-w-[60%]">{{ $customer->email ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2">
                        <dt class="text-gray-500">Alamat Pemasangan</dt>
                        <dd class="font-semibold text-gray-900 text-right max-w-[60%]">{{ $customer->address ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">◐</span>
                    Langganan & Tagihan
                </h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4 py-2 border-b border-gray-100">
                        <dt class="text-gray-500">Paket</dt>
                        <dd class="font-semibold text-gray-900 text-right">{{ $customer->package->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 border-b border-gray-100">
                        <dt class="text-gray-500">Kecepatan</dt>
                        <dd class="font-semibold text-gray-900 text-right">{{ $customer->package->speed ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2 border-b border-gray-100">
                        <dt class="text-gray-500">Siklus Tagihan</dt>
                        <dd class="font-semibold text-gray-900 text-right">Tgl {{ $customer->billing_day ?? 1 }} tiap bulan</dd>
                    </div>
                    <div class="flex justify-between gap-4 py-2">
                        <dt class="text-gray-500">Tindakan Cepat</dt>
                        <dd class="text-right flex gap-1.5 justify-end">
                            <a href="{{ route('customer.packages') }}" class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold hover:bg-blue-100">Paket</a>
                            <a href="{{ route('customer.payments') }}" class="px-2.5 py-1 rounded-lg bg-gray-900 text-white text-xs font-bold hover:bg-black">Bayar</a>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Form Edit Profil -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-6">
            <h3 class="text-sm font-bold text-gray-900">Edit Profil</h3>
            <p class="text-xs text-gray-500 mt-1">Perbarui data kontak agar notifikasi tagihan sampai tepat waktu.</p>

            @if($errors->any())
                <div class="mt-4 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                    <ul class="list-disc ml-4 space-y-1">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('customer.profile.update') }}" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Telepon *</label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" placeholder="opsional" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat</label>
                        <input type="text" name="address" value="{{ old('address', $customer->address) }}" placeholder="Alamat pemasangan" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                </div>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition">Simpan Perubahan</button>
            </form>
        </div>

        <!-- Ubah Password -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 sm:p-6">
            <h3 class="text-sm font-bold text-gray-900">Ubah Password</h3>
            <p class="text-xs text-gray-500 mt-1">Gunakan kombinasi aman, minimal 6 karakter.</p>
            <form method="POST" action="{{ route('customer.profile.password') }}" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Password Saat Ini *</label>
                    <input type="password" name="current_password" required class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Password Baru *</label>
                        <input type="password" name="new_password" required class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Konfirmasi Password *</label>
                        <input type="password" name="new_password_confirmation" required class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-black transition">Ubah Password</button>
            </form>
        </div>
    </div>
    @endsection
</x-customer-layout>
