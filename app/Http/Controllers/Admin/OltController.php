<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Olt;
use App\Models\Onu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OltController extends Controller
{
    public function index(): View
    {
        $olts = Olt::withCount('onus')->paginate(10);
        $onus = Onu::with(['olt', 'customer'])->orderByDesc('created_at')->paginate(15);

        return view('admin.olt.index', compact('olts', 'onus'));
    }

    public function create(): View
    {
        return view('admin.olt.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'brand' => 'required|in:zte,huawei,vsol,hioso,other',
            'host' => 'required|string|max:100',
            'snmp_community' => 'required|string|max:50',
            'location' => 'nullable|string|max:255',
        ]);

        $olt = Olt::create($validated);
        ActivityLog::log('created', 'olt', "Menambahkan OLT: {$olt->name} ({$olt->brand})");

        return redirect()->route('admin.olt.index')->with('success', 'OLT berhasil didaftarkan.');
    }

    public function poll(Olt $olt): RedirectResponse
    {
        // Polling status / simulasi SNMP response
        $olt->update([
            'last_polled_at' => now(),
            'last_status' => [
                'status' => 'online',
                'pon_count' => 8,
                'registered_onus' => rand(20, 120),
                'temperature' => rand(38, 48).' C',
            ],
        ]);
        ActivityLog::log('polled', 'olt', "Polling status OLT: {$olt->name}");

        return back()->with('success', "Status OLT {$olt->name} berhasil diperbarui.");
    }
}
