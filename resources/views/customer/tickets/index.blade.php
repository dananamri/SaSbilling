<x-customer-layout>
    @section('title', 'Pengaduan')
    @section('header', 'Daftar Pengaduan')

    @section('content')
    <div class="bg-white rounded-2xl border border-gray-200">
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h3 class="font-semibold text-gray-900">Semua Pengaduan</h3>
            <div class="flex flex-col sm:flex-row gap-2">
                <form method="GET" class="flex gap-2">
                    <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">Semua Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Terbuka</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Selesai</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                    <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Filter</button>
                </form>
                <a href="{{ route('customer.tickets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 text-center">
                    Buat Pengaduan
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Tiket</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $ticket->ticket_number }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $ticket->title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $ticket->category }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $ticket->status == 'open' ? 'bg-green-100 text-green-800' : ($ticket->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' : ($ticket->status == 'resolved' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $ticket->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('customer.tickets.show', $ticket) }}" class="text-blue-600 hover:underline">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">Belum ada pengaduan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $tickets->withQueryString()->links() }}
        </div>
    </div>
    @endsection
</x-customer-layout>
