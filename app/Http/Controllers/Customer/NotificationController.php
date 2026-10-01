<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $customer = auth('customer')->user();

        $notifications = WhatsAppNotification::where('customer_id', $customer->id)
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('customer.notifications.index', compact('notifications'));
    }

    public function markAsRead(WhatsAppNotification $notification): RedirectResponse
    {
        $this->authorize('view', $notification);

        $notification->update(['status' => 'sent']);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        $customer = auth('customer')->user();

        WhatsAppNotification::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->update(['status' => 'sent']);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
