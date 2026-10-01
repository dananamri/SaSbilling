<x-customer-layout>
    @section('title', 'Profil Saya')
    @section('header', 'Profil Saya')

    @section('content')
    <div class="space-y-6">
        <!-- Info Profil -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row items-center gap-4 mb-6">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#00E5CC] to-[#0066FF] flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <div class="text-center sm:text-left">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $customer->name }}</h3>
                    <p class="text-sm text-gray-500">ID: CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</p>
                    <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full {{ $customer->status->value == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($customer->status->value) }}
                    </span>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Telepon</p>
                    <p class="font-medium text-gray-900">{{ $customer->phone }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Email</p>
                    <p class="font-medium text-gray-900">{{ $customer->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Alamat</p>
                    <p class="font-medium text-gray-900">{{ $customer->address ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Paket Internet</p>
                    <p class="font-medium text-gray-900">{{ $customer->package->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Tanggal Bergabung</p>
                    <p class="font-medium text-gray-900">{{ $customer->joined_at?->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Form Edit Profil -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Edit Profil</h3>
            <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <input type="text" name="address" value="{{ old('address', $customer->address) }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Simpan Perubahan</button>
            </form>
        </div>

        <!-- Ubah Password -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Ubah Password</h3>
            <form method="POST" action="{{ route('customer.profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                    <input type="password" name="current_password" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="new_password" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="new_password_confirmation" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Ubah Password</button>
            </form>
        </div>
    </div>
    @endsection
</x-customer-layout>
