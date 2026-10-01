<x-customer-layout>
    @section('title', 'Buat Pengaduan')
    @section('header', 'Buat Pengaduan')

    @section('content')
    <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-6 max-w-2xl">
        <form method="POST" action="{{ route('customer.tickets.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category" id="category" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Internet Mati" {{ old('category') == 'Internet Mati' ? 'selected' : '' }}>Internet Mati</option>
                    <option value="Internet Lambat" {{ old('category') == 'Internet Lambat' ? 'selected' : '' }}>Internet Lambat</option>
                    <option value="Gangguan Jaringan" {{ old('category') == 'Gangguan Jaringan' ? 'selected' : '' }}>Gangguan Jaringan</option>
                    <option value="Masalah Pembayaran" {{ old('category') == 'Masalah Pembayaran' ? 'selected' : '' }}>Masalah Pembayaran</option>
                    <option value="Masalah Paket" {{ old('category') == 'Masalah Paket' ? 'selected' : '' }}>Masalah Paket</option>
                    <option value="Perubahan Data" {{ old('category') == 'Perubahan Data' ? 'selected' : '' }}>Perubahan Data</option>
                    <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('category')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                @error('title')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="5" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                <select name="priority" id="priority" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Mendesak</option>
                </select>
                @error('priority')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    Kirim Pengaduan
                </button>
                <a href="{{ route('customer.tickets') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
    @endsection
</x-customer-layout>
