@extends('layouts.admin')
    @section('title', 'Pengaturan')
    @section('header', 'Pengaturan')

    @section('content')
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <h3 class="text-base sm:text-lg font-semibold mb-4">Pengaturan Perusahaan</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan *</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name'] ?? '') }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon Perusahaan *</label>
                    <input type="text" name="company_phone" value="{{ old('company_phone', $settings['company_phone'] ?? '') }}" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Perusahaan</label>
                    <textarea name="company_address" rows="2" class="w-full border rounded px-3 py-2 text-sm">{{ old('company_address', $settings['company_address'] ?? '') }}</textarea>
                </div>
            </div>

            <h3 class="text-base sm:text-lg font-semibold mt-6 mb-4">Pengaturan Isolir</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Threshold Isolir (hari keterlambatan) *</label>
                    <input type="number" name="isolir_threshold_days" value="{{ old('isolir_threshold_days', $settings['isolir_threshold_days'] ?? 7) }}" min="1" max="90" class="w-full border rounded px-3 py-2 text-sm">
                    <p class="text-xs text-gray-500 mt-1">Isolir otomatis jika tunggakan melebihi hari ini</p>
                </div>
            </div>

            <h3 class="text-base sm:text-lg font-semibold mt-6 mb-4">Pengaturan Pengingat</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kirim Pengingat Sebelum Jatuh Tempo (hari) *</label>
                    <input type="number" name="reminder_days_before" value="{{ old('reminder_days_before', $settings['reminder_days_before'] ?? 3) }}" min="0" max="30" class="w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
    @endsection
