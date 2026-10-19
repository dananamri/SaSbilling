<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PackageChangeRequest;
use App\Services\WhatsAppGatewayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PackageChangeRequestController extends Controller
{
    public function __construct(
        protected WhatsAppGatewayService $waGateway,
    ) {}

    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');

        $requests = collect();
        $counts = ['pending' => 0, 'approved' => 0, 'rejected' => 0];

        if (Schema::hasTable('package_change_requests')) {
            try {
                $requests = PackageChangeRequest::with(['customer', 'currentPackage', 'targetPackage', 'approver'])
                    ->when($status !== 'all', fn ($q) => $q->where('status', $status))
                    ->latest()
                    ->paginate(15);

                $counts = [
                    'pending' => PackageChangeRequest::where('status', 'pending')->count(),
                    'approved' => PackageChangeRequest::where('status', 'approved')->count(),
                    'rejected' => PackageChangeRequest::where('status', 'rejected')->count(),
                ];
            } catch (\Throwable $e) {
                $requests = collect();
            }
        }

        return view('admin.package-change-requests.index', compact('requests', 'status', 'counts'));
    }

    public function approve(PackageChangeRequest $packageChangeRequest): RedirectResponse
    {
        if ($packageChangeRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $customer = $packageChangeRequest->customer;
        $targetPackage = $packageChangeRequest->targetPackage;
        $oldPackageName = $packageChangeRequest->currentPackage?->name ?? 'Tanpa Paket';

        // Update paket pelanggan
        $customer->update([
            'package_id' => $targetPackage->id,
        ]);

        // Update status permohonan
        $packageChangeRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        ActivityLog::log(
            'approved',
            'upgrade-paket',
            "Menyetujui upgrade paket pelanggan {$customer->name} dari {$oldPackageName} ke {$targetPackage->name} ({$packageChangeRequest->request_number})",
            ['request_id' => $packageChangeRequest->id]
        );

        // Notifikasi balik ke WhatsApp Pelanggan
        $message = "Halo *{$customer->name}*,\n\n"
            ."Permintaan ganti/upgrade paket Anda (*{$packageChangeRequest->request_number}*) telah *DISETUJUI* oleh Admin.\n\n"
            ."*Paket Baru:* {$targetPackage->name}\n"
            ."*Kecepatan:* {$targetPackage->speed}\n"
            .'*Biaya:* Rp '.number_format($targetPackage->price, 0, ',', '.')."/bulan\n"
            .'*Tanggal Aktif:* '.now()->format('d-m-Y H:i')." WIB\n\n"
            .'Terima kasih telah menggunakan layanan SATAK!';

        $this->waGateway->send($customer->phone, $message, $customer->id);

        return back()->with('success', "Permintaan upgrade {$packageChangeRequest->request_number} berhasil disetujui. Paket pelanggan telah diperbarui dan notifikasi terkirim.");
    }

    public function reject(Request $request, PackageChangeRequest $packageChangeRequest): RedirectResponse
    {
        if ($packageChangeRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $packageChangeRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $customer = $packageChangeRequest->customer;
        $targetPackage = $packageChangeRequest->targetPackage;

        ActivityLog::log(
            'rejected',
            'upgrade-paket',
            "Menolak upgrade paket pelanggan {$customer->name} ({$packageChangeRequest->request_number}): {$validated['rejection_reason']}",
            ['request_id' => $packageChangeRequest->id]
        );

        // Notifikasi penolakan ke WhatsApp Pelanggan
        $message = "Halo *{$customer->name}*,\n\n"
            ."Mohon maaf, permintaan upgrade paket Anda (*{$packageChangeRequest->request_number}*) ke paket {$targetPackage->name} *BELUM DAPAT DISETUJUI*.\n\n"
            ."*Alasan:* {$validated['rejection_reason']}\n\n"
            .'Silakan hubungi customer service kami jika ada pertanyaan lebih lanjut.';

        $this->waGateway->send($customer->phone, $message, $customer->id);

        return back()->with('success', "Permintaan upgrade {$packageChangeRequest->request_number} telah ditolak dan notifikasi terkirim ke pelanggan.");
    }
}
