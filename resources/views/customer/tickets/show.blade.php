<x-customer-layout>
    @section('title', 'Detail Pengaduan')
    @section('header', 'Detail Pengaduan')

    @section('content')
    <div class="space-y-6">
        <!-- Info Tiket -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-4">
                <div>
                    <p class="text-xs font-mono font-bold text-blue-600">{{ $ticket->ticket_number }}</p>
                    <h3 class="text-lg font-bold text-gray-900 mt-0.5">{{ $ticket->title }}</h3>
                </div>
                <div class="flex items-center gap-2">
                    @if(!empty($directWaUrl))
                        <a href="{{ $directWaUrl }}" target="_blank" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-sm transition">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.124-.669-1.613-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Hubungi WA Admin
                        </a>
                    @endif
                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $ticket->status == 'resolved' ? 'bg-green-100 text-green-800' : ($ticket->status == 'closed' ? 'bg-gray-100 text-gray-800' : ($ticket->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                </div>
            </div>

            <!-- Pesan Informasi WhatsApp Admin -->
            <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Laporan ini otomatis dikirim ke WhatsApp Admin (<strong>{{ $adminPhone ?? '+6287863389320' }}</strong>).</span>
                </div>
                @if(!empty($directWaUrl))
                    <a href="{{ $directWaUrl }}" target="_blank" class="text-emerald-700 font-bold underline hover:text-emerald-900 whitespace-nowrap">Chat WA &rarr;</a>
                @endif
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm pt-3 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-500">Kategori</p>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Prioritas</p>
                    <p class="font-bold mt-0.5 {{ $ticket->priority == 'urgent' ? 'text-red-600' : ($ticket->priority == 'high' ? 'text-orange-600' : 'text-gray-900') }}">
                        {{ ucfirst($ticket->priority) }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal Lapor</p>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Teknisi</p>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $ticket->technician->name ?? 'Belum Ditugaskan' }}</p>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-500 mb-1">Deskripsi Masalah:</p>
                <div class="text-sm text-gray-800 bg-gray-50 rounded-xl p-3 whitespace-pre-line leading-relaxed">
                    {{ $ticket->description }}
                </div>
            </div>
        </div>

        <!-- Percakapan / Pesan -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-4">Percakapan Tiket</h3>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($ticket->messages as $msg)
                    <div class="flex {{ $msg->user_id ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-[85%] {{ $msg->user_id ? 'bg-gray-100 text-gray-900' : 'bg-blue-600 text-white' }} rounded-2xl px-4 py-3">
                            <p class="text-xs font-bold {{ $msg->user_id ? 'text-blue-600' : 'text-blue-100' }} mb-1">
                                {{ $msg->user ? $msg->user->name.' (Petugas)' : 'Anda (Pelanggan)' }}
                            </p>
                            <p class="text-sm whitespace-pre-line">{{ $msg->message }}</p>
                            <p class="text-[10px] {{ $msg->user_id ? 'text-gray-400' : 'text-blue-200' }} mt-1 text-right">
                                {{ $msg->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-xs text-center py-4">Belum ada percakapan tambahan untuk tiket ini.</p>
                @endforelse
            </div>
        </div>
    </div>
    @endsection
</x-customer-layout>
