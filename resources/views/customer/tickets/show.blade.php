<x-customer-layout>
    @section('title', 'Detail Pengaduan')
    @section('header', 'Detail Pengaduan')

    @section('content')
    <div class="space-y-6">
        <!-- Info Tiket -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-4">
                <div>
                    <p class="text-sm text-gray-500">{{ $ticket->ticket_number }}</p>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $ticket->title }}</h3>
                </div>
                <span class="px-3 py-1 text-xs rounded-full self-start {{ $ticket->status == 'resolved' ? 'bg-green-100 text-green-800' : ($ticket->status == 'closed' ? 'bg-gray-100 text-gray-800' : ($ticket->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                </span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Kategori</p>
                    <p class="font-medium text-gray-900">{{ $ticket->category }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Prioritas</p>
                    <p class="font-medium {{ $ticket->priority == 'urgent' ? 'text-red-600' : ($ticket->priority == 'high' ? 'text-orange-600' : 'text-gray-900') }}">
                        {{ ucfirst($ticket->priority) }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Tanggal</p>
                    <p class="font-medium text-gray-900">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</p>
                </div>
            </div>
        </div>

        <!-- Percakapan -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Percakapan</h3>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($ticket->replies as $reply)
                    <div class="flex {{ $reply->is_from_customer ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] {{ $reply->is_from_customer ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-900' }} rounded-2xl px-4 py-3">
                            <p class="text-sm">{{ $reply->message }}</p>
                            <p class="text-xs {{ $reply->is_from_customer ? 'text-blue-200' : 'text-gray-500' }} mt-1">
                                {{ $reply->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">Belum ada balasan</p>
                @endforelse
            </div>
        </div>

        <!-- Form Balasan -->
        @if($ticket->status !== 'closed' && $ticket->status !== 'resolved')
            <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Balas Pengaduan</h3>
                <form method="POST" action="{{ route('customer.tickets.reply', $ticket) }}" class="space-y-3">
                    @csrf
                    <textarea name="message" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis balasan..." required></textarea>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                        Kirim Balasan
                    </button>
                </form>
            </div>
        @endif
    </div>
    @endsection
</x-customer-layout>
