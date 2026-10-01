<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $customer = auth('customer')->user();

        return view('customer.settings.index', compact('customer'));
    }

    public function updateNotificationSettings(Request $request): RedirectResponse
    {
        $customer = auth('customer')->user();

        $validated = $request->validate([
            'email_notification' => 'boolean',
            'whatsapp_notification' => 'boolean',
        ]);

        $customer->update([
            'email_notification' => $validated['email_notification'] ?? false,
            'whatsapp_notification' => $validated['whatsapp_notification'] ?? false,
        ]);

        return back()->with('success', 'Pengaturan notifikasi berhasil disimpan.');
    }
}
