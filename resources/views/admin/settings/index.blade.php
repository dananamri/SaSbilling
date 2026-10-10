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

            <h3 class="text-base sm:text-lg font-semibold mt-6 mb-4">Pengaturan Pembayaran & WhatsApp</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Admin (Konfirmasi Pembayaran)</label>
                    <input type="text" name="admin_whatsapp" value="{{ old('admin_whatsapp', $settings['admin_whatsapp'] ?? '') }}" placeholder="Contoh: 6281234567890" class="w-full border rounded px-3 py-2 text-sm">
                    <p class="text-xs text-gray-500 mt-1">Gunakan kode negara tanpa tanda plus atau nol di depan (misal: 628xxx)</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank / E-Wallet</label>
                        <input type="text" name="payment_bank_name" value="{{ old('payment_bank_name', $settings['payment_bank_name'] ?? 'BCA') }}" placeholder="Contoh: BCA / Mandiri / Dana" class="w-full border rounded px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening / Akun</label>
                        <input type="text" name="payment_bank_account" value="{{ old('payment_bank_account', $settings['payment_bank_account'] ?? '') }}" placeholder="Contoh: 1234567890" class="w-full border rounded px-3 py-2 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Atas Nama Rekening</label>
                    <input type="text" name="payment_bank_holder" value="{{ old('payment_bank_holder', $settings['payment_bank_holder'] ?? '') }}" placeholder="Contoh: PT SATAK Konek Terus" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Info QRIS / Catatan Pembayaran</label>
                    <input type="text" name="payment_qris_info" value="{{ old('payment_qris_info', $settings['payment_qris_info'] ?? '') }}" placeholder="Contoh: Scan QRIS di outlet / upload bukti via WA" class="w-full border rounded px-3 py-2 text-sm">
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
                <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2.5 rounded text-sm hover:bg-blue-700 font-medium">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
    @endsection
