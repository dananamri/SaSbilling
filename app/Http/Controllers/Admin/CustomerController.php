<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CustomerStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::with('package')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function create(): View
    {
        $packages = Package::where('is_active', true)->get();

        return view('admin.customers.create', compact('packages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:customers,username',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'package_id' => 'required|exists:packages,id',
            'billing_day' => 'required|integer|min:1|max:28',
            'joined_at' => 'nullable|date',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validated['status'] = CustomerStatus::Active;
        $validated['password'] = Hash::make($validated['password']);

        Customer::create($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Customer $customer): View
    {
        $customer->load(['package', 'invoices.payments', 'isolirLogs']);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        $packages = Package::where('is_active', true)->get();

        return view('admin.customers.edit', compact('customer', 'packages'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:customers,username,'.$customer->id,
            'phone' => 'required|string|max:20|unique:customers,phone,'.$customer->id,
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'package_id' => 'required|exists:packages,id',
            'billing_day' => 'required|integer|min:1|max:28',
            'joined_at' => 'nullable|date',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
