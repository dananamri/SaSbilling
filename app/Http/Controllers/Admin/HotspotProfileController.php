<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotspotProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotspotProfileController extends Controller
{
    public function index(): View
    {
        $profiles = HotspotProfile::withCount(['vouchers', 'members'])
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.hotspot.profiles.index', compact('profiles'));
    }

    public function create(): View
    {
        return view('admin.hotspot.profiles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:PPoE,Hotspot',
            'price' => 'required|numeric|min:0',
            'speed' => 'nullable|string|max:50',
            'validity' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        HotspotProfile::create($validated);

        return redirect()->route('admin.hotspot.profiles.index')->with('success', 'Profil hotspot berhasil ditambahkan.');
    }

    public function edit(HotspotProfile $profile): View
    {
        return view('admin.hotspot.profiles.edit', compact('profile'));
    }

    public function update(Request $request, HotspotProfile $profile): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:PPoE,Hotspot',
            'price' => 'required|numeric|min:0',
            'speed' => 'nullable|string|max:50',
            'validity' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $profile->update($validated);

        return redirect()->route('admin.hotspot.profiles.index')->with('success', 'Profil hotspot berhasil diperbarui.');
    }

    public function destroy(HotspotProfile $profile): RedirectResponse
    {
        if ($profile->vouchers()->exists() || $profile->members()->exists()) {
            return back()->with('error', 'Profil tidak dapat dihapus karena masih memiliki voucher atau member.');
        }

        $profile->delete();

        return redirect()->route('admin.hotspot.profiles.index')->with('success', 'Profil hotspot berhasil dihapus.');
    }
}
