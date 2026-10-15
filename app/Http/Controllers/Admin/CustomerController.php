<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CustomerStatus;
use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::with(['package', 'tenant'])
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
        $tenants = Tenant::where('is_active', true)->get();

        return view('admin.customers.create', compact('packages', 'tenants'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();

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
            'photo' => 'nullable|image|max:2048',
            'tenant_id' => $isSuperAdmin ? 'required|exists:tenants,id' : 'nullable',
        ]);

        $validated['status'] = CustomerStatus::Active;
        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('customers/photos', 'public');
        }

        // Set tenant_id: SuperAdmin can choose, others use their tenant
        if (! $isSuperAdmin) {
            $validated['tenant_id'] = $user->tenant_id;
        }

        Customer::create($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Customer $customer): View
    {
        $customer->load(['package', 'tenant', 'invoices.payments', 'isolirLogs']);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        $packages = Package::where('is_active', true)->get();
        $tenants = Tenant::where('is_active', true)->get();

        return view('admin.customers.edit', compact('customer', 'packages', 'tenants'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();

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
            'photo' => 'nullable|image|max:2048',
            'tenant_id' => $isSuperAdmin ? 'required|exists:tenants,id' : 'nullable',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($customer->photo) {
                Storage::disk('public')->delete($customer->photo);
            }
            $validated['photo'] = $request->file('photo')->store('customers/photos', 'public');
        }

        // Set tenant_id: SuperAdmin can choose, others keep original tenant
        if (! $isSuperAdmin) {
            unset($validated['tenant_id']);
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function approve(Customer $customer): RedirectResponse
    {
        $customer->update([
            'status' => CustomerStatus::Active,
            'joined_at' => now(),
            'billing_day' => now()->day,
        ]);

        // Aktifkan invoice dan payment pending
        $unpaidInvoices = $customer->invoices()->where('status', '!=', InvoiceStatus::Paid)->get();
        foreach ($unpaidInvoices as $invoice) {
            $invoice->update([
                'status' => InvoiceStatus::Paid,
                'paid_at' => now(),
            ]);

            $invoice->payments()->update([
                'status' => 'success',
            ]);
        }

        return back()->with('success', "Akun pelanggan {$customer->name} berhasil disetujui dan diaktifkan.");
    }

    public function reject(Customer $customer): RedirectResponse
    {
        $customer->update([
            'status' => CustomerStatus::Inactive,
        ]);

        return back()->with('warning', "Pendaftaran pelanggan {$customer->name} ditolak / dinonaktifkan.");
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
