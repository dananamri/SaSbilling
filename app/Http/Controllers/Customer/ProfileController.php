<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $customer = auth('customer')->user();

        return view('customer.profile.index', compact('customer'));
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = auth('customer')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone,'.$customer->id,
            'email' => 'nullable|email|unique:customers,email,'.$customer->id,
            'address' => 'nullable|string',
        ]);

        $customer->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $customer = auth('customer')->user();

        // Delete old photo if exists
        if ($customer->photo) {
            Storage::disk('public')->delete($customer->photo);
        }

        // Store new photo
        $path = $request->file('photo')->store('customers/photos', 'public');

        $customer->update(['photo' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $customer = auth('customer')->user();

        if (! Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $customer->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
