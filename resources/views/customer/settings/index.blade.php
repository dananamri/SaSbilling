<x-customer-layout>
    @section('title', 'Pengaturan')
    @section('header', 'Pengaturan')

    @section('content')
    <div class="space-y-6">
        <!-- Pengaturan Notifikasi -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Pengaturan Notifikasi</h3>
            <form method="POST" action="{{ route('customer.settings.notifications') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <div>
                        <p class="font-medium text-gray-900">Notifikasi Email</p>
                        <p class="text-sm text-gray-500">Terima notifikasi melalui email</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_notification" value="1" class="sr-only peer" {{ $customer->email_notification ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div>
                        <p class="font-medium text-gray-900">Notifikasi WhatsApp</p>
                        <p class="text-sm text-gray-500">Terima notifikasi melalui WhatsApp</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="whatsapp_notification" value="1" class="sr-only peer" {{ $customer->whatsapp_notification ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Simpan Pengaturan</button>
            </form>
        </div>

        <!-- Info Akun -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Info Akun</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Username</span>
                    <span class="font-medium text-gray-900">{{ $customer->username }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Email</span>
                    <span class="font-medium text-gray-900">{{ $customer->email ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Bergabung Sejak</span>
                    <span class="font-medium text-gray-900">{{ $customer->joined_at?->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-customer-layout>
