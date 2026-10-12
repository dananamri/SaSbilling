<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\PppoeProfile;
use App\Models\PppoeSecret;
use App\Models\Router;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PppoeController extends Controller
{
    public function index(Request $request): View
    {
        $secrets = PppoeSecret::with(['router', 'customer', 'profile'])
            ->when($request->router_id, fn ($q) => $q->where('router_id', $request->router_id))
            ->when($request->search, fn ($q) => $q->where('username', 'like', "%{$request->search}%"))
            ->orderByDesc('created_at')
            ->paginate(15);

        $routers = Router::orderBy('name')->get();
        $profiles = PppoeProfile::with('router')->orderBy('name')->get();

        return view('admin.pppoe.index', compact('secrets', 'routers', 'profiles'));
    }

    public function create(): View
    {
        $routers = Router::where('is_active', true)->orderBy('name')->get();
        $profiles = PppoeProfile::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('admin.pppoe.create', compact('routers', 'profiles', 'customers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'router_id' => 'required|exists:routers,id',
            'customer_id' => 'nullable|exists:customers,id',
            'pppoe_profile_id' => 'nullable|exists:pppoe_profiles,id',
            'username' => 'required|string|max:100|unique:pppoe_secrets,username',
            'password' => 'required|string|min:3',
            'local_address' => 'nullable|string|max:50',
            'remote_address' => 'nullable|string|max:50',
            'comment' => 'nullable|string|max:255',
        ]);

        $secret = PppoeSecret::create($validated);
        ActivityLog::log('created', 'pppoe', "Menambahkan secret PPPoE: {$secret->username}");

        // TODO: Sinkronisasi real-time ke MikroTik via RouterOS API setelah ini

        return redirect()->route('admin.pppoe.index')->with('success', 'Akun PPPoE berhasil dibuat. Klik Sinkron untuk apply ke MikroTik.');
    }

    public function destroy(PppoeSecret $pppoeSecret): RedirectResponse
    {
        $u = $pppoeSecret->username;
        $pppoeSecret->delete();
        ActivityLog::log('deleted', 'pppoe', "Menghapus secret PPPoE: {$u}");

        return back()->with('success', 'Akun PPPoE dihapus.');
    }

    public function profiles(): View
    {
        $profiles = PppoeProfile::with('router')->orderByDesc('created_at')->paginate(15);
        $routers = Router::orderBy('name')->get();

        return view('admin.pppoe.profiles', compact('profiles', 'routers'));
    }

    public function storeProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'router_id' => 'required|exists:routers,id',
            'name' => 'required|string|max:100|unique:pppoe_profiles,name',
            'local_address' => 'nullable|string|max:50',
            'remote_address' => 'nullable|string|max:50',
            'rate_limit' => 'nullable|string|max:50',
            'dns_server' => 'nullable|string|max:100',
        ]);

        $p = PppoeProfile::create($validated);
        ActivityLog::log('created', 'pppoe', "Menambahkan profil PPPoE: {$p->name}");

        return redirect()->route('admin.pppoe.profiles')->with('success', 'Profile PPPoE berhasil dibuat.');
    }
}
