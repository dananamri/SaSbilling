<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\WhatsAppNotification;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = WhatsAppNotification::with('customer')
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create(): View
    {
        $customers = Customer::all();

        return view('admin.notifications.create', compact('customers'));
    }

    public function store(Request $request, NotificationService $notificationService): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'message' => 'required|string',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);
        $notificationService->sendInfo($customer, $validated['message']);

        return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi berhasil dikirim.');
    }

    public function sendPending(NotificationService $notificationService): RedirectResponse
    {
        $pending = WhatsAppNotification::where('status', 'pending')->get();

        foreach ($pending as $notification) {
            $notificationService->markAsSent($notification);
        }

        return back()->with('success', "{$pending->count()} notifikasi berhasil dikirim.");
    }

    public function show(WhatsAppNotification $notification): View
    {
        return view('admin.notifications.show', compact('notification'));
    }
}
