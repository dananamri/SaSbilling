<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotspotProfile;
use App\Models\HotspotVoucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HotspotVoucherController extends Controller
{
    public function index(Request $request): View
    {
        $vouchers = HotspotVoucher::with(['profile', 'member'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->profile_id, fn ($q) => $q->where('profile_id', $request->profile_id))
            ->orderByDesc('created_at')
            ->paginate(20);

        $profiles = HotspotProfile::where('is_active', true)->get();

        return view('admin.hotspot.vouchers.index', compact('vouchers', 'profiles'));
    }

    public function create(): View
    {
        $profiles = HotspotProfile::where('is_active', true)->get();

        return view('admin.hotspot.vouchers.create', compact('profiles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'profile_id' => 'required|exists:hotspot_profiles,id',
            'quantity' => 'required|integer|min:1|max:1000',
            'prefix' => 'nullable|string|max:10',
        ]);

        $vouchers = [];

        for ($i = 0; $i < $validated['quantity']; $i++) {
            $vouchers[] = [
                'profile_id' => $validated['profile_id'],
                'code' => ($validated['prefix'] ?? '').strtoupper(Str::random(8)),
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        HotspotVoucher::insert($vouchers);

        return redirect()->route('admin.hotspot.vouchers.index')->with('success', "{$validated['quantity']} voucher berhasil dibuat.");
    }

    public function destroy(HotspotVoucher $voucher): RedirectResponse
    {
        if ($voucher->status !== 'available') {
            return back()->with('error', 'Voucher tidak dapat dihapus karena sudah digunakan.');
        }

        $voucher->delete();

        return redirect()->route('admin.hotspot.vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }

    public function print(HotspotVoucher $voucher): View
    {
        return view('admin.hotspot.vouchers.print', compact('voucher'));
    }

    public function rekap(): View
    {
        $totalProfiles = HotspotProfile::count();
        $totalVouchers = HotspotVoucher::count();
        $usedVouchers = HotspotVoucher::where('status', 'used')->count();
        $totalMembers = \App\Models\HotspotMember::count();

        $vouchersByProfile = HotspotVoucher::selectRaw('hotspot_profiles.name as profile_name, COUNT(*) as total_count, SUM(CASE WHEN hotspot_vouchers.status = "used" THEN 1 ELSE 0 END) as used_count')
            ->join('hotspot_profiles', 'hotspot_vouchers.profile_id', '=', 'hotspot_profiles.id')
            ->groupBy('hotspot_profiles.name')
            ->get();

        $membersByProfile = \App\Models\HotspotMember::selectRaw('hotspot_profiles.name as profile_name, COUNT(*) as member_count')
            ->join('hotspot_profiles', 'hotspot_members.profile_id', '=', 'hotspot_profiles.id')
            ->groupBy('hotspot_profiles.name')
            ->get();

        $vouchersByMonth = HotspotVoucher::selectRaw('strftime("%Y-%m", used_at) as month, COUNT(*) as count')
            ->where('status', 'used')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('admin.hotspot.rekap.index', compact(
            'totalProfiles',
            'totalVouchers',
            'usedVouchers',
            'totalMembers',
            'vouchersByProfile',
            'membersByProfile',
            'vouchersByMonth',
        ));
    }
}
