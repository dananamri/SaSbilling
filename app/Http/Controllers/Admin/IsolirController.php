<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\IsolirLog;
use App\Services\IsolirService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IsolirController extends Controller
{
    public function index(): View
    {
        $isolirLogs = IsolirLog::with('customer')
            ->orderByDesc('created_at')
            ->paginate(15);

        $isolatedCustomers = Customer::where('status', 'isolated')
            ->with('package')
            ->get();

        return view('admin.isolir.index', compact('isolirLogs', 'isolatedCustomers'));
    }

    public function isolate(Customer $customer, Request $request, IsolirService $isolirService, NotificationService $notificationService): RedirectResponse
    {
        $reason = $request->input('reason', 'Isolir manual oleh admin');

        $isolirService->isolate($customer, $reason);
        $notificationService->sendIsolatedNotification($customer);

        return back()->with('success', "Pelanggan {$customer->name} berhasil diisolir.");
    }

    public function reopen(Customer $customer, Request $request, IsolirService $isolirService, NotificationService $notificationService): RedirectResponse
    {
        $reason = $request->input('reason', 'Buka isolir manual oleh admin');

        $isolirService->reopen($customer, $reason);
        $notificationService->sendReopenedNotification($customer);

        return back()->with('success', "Isolir pelanggan {$customer->name} berhasil dibuka.");
    }

    public function checkNow(IsolirService $isolirService): RedirectResponse
    {
        $isolated = $isolirService->checkAndIsolate();
        $reopened = $isolirService->checkAndReopen();

        return back()->with('success', "Pengecekan selesai. Diisolir: {$isolated}, Dibuka: {$reopened}.");
    }
}
