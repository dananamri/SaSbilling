<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(): View
    {
        $tenants = Tenant::orderBy('name')->paginate(15);

        return view('admin.tenants.index', compact('tenants'));
    }

    public function create(): View
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:tenants,code',
            'company_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $tenant = Tenant::create($validated);
        ActivityLog::log('created', 'tenant', "Menambahkan cabang/tenant: {$tenant->name}");

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant / cabang berhasil ditambahkan.');
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        $name = $tenant->name;
        $tenant->delete();
        ActivityLog::log('deleted', 'tenant', "Menghapus tenant: {$name}");

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant dihapus.');
    }
}
