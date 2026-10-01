<x-customer-layout>
    @section('title', 'Status Koneksi')
    @section('header', 'Status Koneksi')

    @section('content')
    <div class="space-y-6">
        <!-- Status Koneksi -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 mb-2">Status Koneksi</h3>
                    <div class="flex items-center gap-3">
                        <span class="relative flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $connection['status'] == 'online' ? 'bg-green-400' : ($connection['status'] == 'offline' ? 'bg-red-400' : 'bg-yellow-400') }} opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 {{ $connection['status'] == 'online' ? 'bg-green-500' : ($connection['status'] == 'offline' ? 'bg-red-500' : 'bg-yellow-500') }}"></span>
                        </span>
                        <span class="text-lg font-bold {{ $connection['status'] == 'online' ? 'text-green-600' : ($connection['status'] == 'offline' ? 'text-red-600' : 'text-yellow-600') }}">
                            {{ ucfirst($connection['status']) }}
                        </span>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    <p>Terakhir online: {{ $connection['last_online'] ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Detail Koneksi -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Detail Koneksi</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">IP Address</p>
                    <p class="font-medium text-gray-900">{{ $connection['ip_address'] ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Username PPPoE</p>
                    <p class="font-medium text-gray-900">{{ $connection['username_pppoe'] ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Waktu Mulai Koneksi</p>
                    <p class="font-medium text-gray-900">{{ $connection['connected_at'] ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Durasi Koneksi</p>
                    <p class="font-medium text-gray-900">{{ $connection['duration'] ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Download Speed</p>
                    <p class="font-medium text-gray-900">{{ $connection['download_speed'] ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Upload Speed</p>
                    <p class="font-medium text-gray-900">{{ $connection['upload_speed'] ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
            <p class="text-sm text-blue-800">
                <strong>Info:</strong> Data koneksi akan otomatis terhubung ketika sistem sudah terintegrasi dengan MikroTik/RADIUS.
            </p>
        </div>
    </div>
    @endsection
</x-customer-layout>
