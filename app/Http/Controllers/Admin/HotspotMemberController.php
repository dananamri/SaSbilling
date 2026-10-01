<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotspotMember;
use App\Models\HotspotProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class HotspotMemberController extends Controller
{
    public function index(Request $request): View
    {
        $members = HotspotMember::with('profile')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->where(function ($query) use ($request) {
                $query->where('username', 'like', "%{$request->search}%")
                    ->orWhere('fullname', 'like', "%{$request->search}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.hotspot.members.index', compact('members'));
    }

    public function create(): View
    {
        $profiles = HotspotProfile::where('is_active', true)->get();

        return view('admin.hotspot.members.create', compact('profiles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:hotspot_members,username',
            'password' => 'required|string|min:6',
            'fullname' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_id' => 'nullable|exists:hotspot_profiles,id',
            'expired_at' => 'nullable|date',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'active';

        HotspotMember::create($validated);

        return redirect()->route('admin.hotspot.members.index')->with('success', 'Member hotspot berhasil ditambahkan.');
    }

    public function edit(HotspotMember $member): View
    {
        $profiles = HotspotProfile::where('is_active', true)->get();

        return view('admin.hotspot.members.edit', compact('member', 'profiles'));
    }

    public function update(Request $request, HotspotMember $member): RedirectResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:hotspot_members,username,'.$member->id,
            'password' => 'nullable|string|min:6',
            'fullname' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_id' => 'nullable|exists:hotspot_profiles,id',
            'status' => 'required|in:active,suspended,expired',
            'expired_at' => 'nullable|date',
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $member->update($validated);

        return redirect()->route('admin.hotspot.members.index')->with('success', 'Member hotspot berhasil diperbarui.');
    }

    public function destroy(HotspotMember $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('admin.hotspot.members.index')->with('success', 'Member hotspot berhasil dihapus.');
    }
}
