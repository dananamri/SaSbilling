<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'isolir_threshold_days' => 'required|integer|min:1|max:90',
            'company_name' => 'required|string|max:255',
            'company_phone' => 'required|string|max:20',
            'company_address' => 'nullable|string',
            'reminder_days_before' => 'required|integer|min:0|max:30',
            'admin_whatsapp' => 'nullable|string|max:20',
            'payment_bank_name' => 'nullable|string|max:50',
            'payment_bank_account' => 'nullable|string|max:50',
            'payment_bank_holder' => 'nullable|string|max:100',
            'payment_qris_info' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
