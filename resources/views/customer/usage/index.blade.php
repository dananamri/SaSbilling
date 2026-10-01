<x-customer-layout>
    @section('title', 'Pemakaian Internet')
    @section('header', 'Pemakaian Internet')

    @section('content')
    <div class="space-y-6">
        <!-- Total Download/Upload -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
                <p class="text-sm text-gray-500 mb-1">Total Download</p>
                <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ $usage['total_download'] }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
                <p class="text-sm text-gray-500 mb-1">Total Upload</p>
                <p class="text-xl sm:text-2xl font-bold text-indigo-600">{{ $usage['total_upload'] }}</p>
            </div>
        </div>

        <!-- Pemakaian Hari Ini & Bulan Ini -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
                <p class="text-sm text-gray-500 mb-1">Pemakaian Hari Ini</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $usage['today_download'] }} / {{ $usage['today_upload'] }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
                <p class="text-sm text-gray-500 mb-1">Pemakaian Bulan Ini</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $usage['month_download'] }} / {{ $usage['month_upload'] }}</p>
            </div>
        </div>

        <!-- Grafik Sederhana -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Grafik Pemakaian</h3>
            <div class="space-y-3">
                @foreach($usage['daily_usage'] as $day => $data)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600">{{ $day }}</span>
                            <span class="text-gray-900">{{ $data['download'] }} / {{ $data['upload'] }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                        </div>
                    </div>
                @endforeach
                @if(empty($usage['daily_usage']))
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada data pemakaian</p>
                @endif
            </div>
        </div>

        <!-- Riwayat Koneksi -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Riwayat Koneksi</h3>
            @if(!empty($usage['connection_history']))
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Download</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Upload</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($usage['connection_history'] as $history)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $history['date'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $history['duration'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $history['download'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $history['upload'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-4">Belum ada riwayat koneksi</p>
            @endif
        </div>

        <!-- Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
            <p class="text-sm text-blue-800">
                <strong>Info:</strong> Data pemakaian akan otomatis terhubung ketika sistem sudah terintegrasi dengan MikroTik/RADIUS.
            </p>
        </div>
    </div>
    @endsection
</x-customer-layout>
