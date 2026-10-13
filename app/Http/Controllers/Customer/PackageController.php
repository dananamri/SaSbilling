<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageChangeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $customer = auth('customer')->user();
        $currentPackage = $customer->package;
        $availablePackages = Package::where('is_active', true)->get();

        $pendingRequest = null;
        $historyRequests = collect();

        if (Schema::hasTable('package_change_requests')) {
            try {
                $pendingRequest = PackageChangeRequest::where('customer_id', $customer->id)
                    ->where('status', 'pending')
                    ->with(['currentPackage', 'targetPackage'])
                    ->latest()
                    ->first();

                $historyRequests = PackageChangeRequest::where('customer_id', $customer->id)
                    ->with(['currentPackage', 'targetPackage'])
                    ->latest()
                    ->limit(5)
                    ->get();
            } catch (\Throwable $e) {
                // Graceful fallback if table is mid-migration
                $pendingRequest = null;
                $historyRequests = collect();
            }
        }

        return view('customer.packages.index', compact('customer', 'currentPackage', 'availablePackages', 'pendingRequest', 'historyRequests'));
    }

    public function requestUpgrade(Request $request): RedirectResponse
    {
        $customer = auth('customer')->user();

        $validated = $request->validate([
            'target_package_id' => 'required|exists:packages,id',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($customer->package_id == $validated['target_package_id']) {
            return back()->with('error', 'Anda sudah menggunakan paket ini.');
        }

        if (! Schema::hasTable('package_change_requests')) {
            return back()->with('error', 'Fitur upgrade sedang dalam pemeliharaan (tabel belum siap). Silakan hubungi admin.');
        }

        $existingPending = PackageChangeRequest::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPending) {
            return back()->with('error', 'Anda masih memiliki permohonan upgrade paket yang sedang menunggu persetujuan admin.');
        }

        $changeRequest = PackageChangeRequest::create([
            'customer_id' => $customer->id,
            'current_package_id' => $customer->package_id,
            'target_package_id' => $validated['target_package_id'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', "Permintaan upgrade paket ({$changeRequest->request_number}) berhasil diajukan. Menunggu persetujuan admin.");
    }
}
