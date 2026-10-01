<x-customer-layout>
    @section('title', 'Notifikasi')
    @section('header', 'Notifikasi')

    @section('content')
    <div class="bg-white rounded-2xl border border-gray-200">
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h3 class="font-semibold text-gray-900">Semua Notifikasi</h3>
            <div class="flex flex-col sm:flex-row gap-2">
                <form method="GET" class="flex gap-2">
                    <select name="type" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">Semua Tipe</option>
                        <option value="reminder" {{ request('type') == 'reminder' ? 'selected' : '' }}>Pengingat</option>
                        <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                        <option value="payment_received" {{ request('type') == 'payment_received' ? 'selected' : '' }}>Pembayaran</option>
                        <option value="isolated" {{ request('type') == 'isolated' ? 'selected' : '' }}>Isolir</option>
                    </select>
                    <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Filter</button>
                </form>
                <form method="POST" action="{{ route('customer.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 text-center">
                        Tandai Sudah Dibaca
                    </button>
                </form>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($notifications as $notification)
                <div class="p-4 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-1 text-xs rounded-full {{ $notification->type->value == 'reminder' ? 'bg-yellow-100 text-yellow-800' : ($notification->type->value == 'payment_received' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $notification->type->value)) }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-900">{{ Str::limit($notification->message, 100) }}</p>
                        </div>
                        <form method="POST" action="{{ route('customer.notifications.read', $notification) }}">
                            @csrf
                            <button type="submit" class="text-xs text-blue-600 hover:underline whitespace-nowrap">Tandai Dibaca</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm text-gray-500">Tidak ada notifikasi</div>
            @endforelse
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $notifications->withQueryString()->links() }}
        </div>
    </div>
    @endsection
</x-customer-layout>
