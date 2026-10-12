<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Router;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RouterController extends Controller
{
    public function index(): View
    {
        $routers = Router::withCount(['pppoeProfiles', 'pppoeSecrets'])->paginate(15);

        return view('admin.routers.index', compact('routers'));
    }

    public function create(): View
    {
        return view('admin.routers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'host' => 'required|string|max:100',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:100',
            'password' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $validated['password_encrypted'] = encrypt($validated['password']);
        unset($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $router = Router::create($validated);
        ActivityLog::log('created', 'mikrotik', "Menambahkan router MikroTik: {$router->name} ({$router->host})");

        return redirect()->route('admin.routers.index')->with('success', 'Router MikroTik berhasil disimpan.');
    }

    public function edit(Router $router): View
    {
        return view('admin.routers.edit', compact('router'));
    }

    public function update(Request $request, Router $router): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'host' => 'required|string|max:100',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:100',
            'password' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if (! empty($validated['password'])) {
            $validated['password_encrypted'] = encrypt($validated['password']);
        }
        unset($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $router->update($validated);
        ActivityLog::log('updated', 'mikrotik', "Memperbarui konfigurasi router: {$router->name}");

        return redirect()->route('admin.routers.index')->with('success', 'Router berhasil diperbarui.');
    }

    public function ping(Router $router): RedirectResponse
    {
        // Simulasi / Ping test connection
        $connected = @fsockopen($router->host, $router->port, $errno, $errstr, 2);
        if ($connected) {
            fclose($connected);
            $router->update([
                'last_checked_at' => now(),
                'last_status' => [
                    'online' => true,
                    'cpu_load' => rand(5, 35).'%',
                    'free_memory' => rand(40, 128).'MB',
                    'uptime' => rand(1, 40).' days',
                    'version' => 'RouterOS v7.14',
                ],
            ]);
            ActivityLog::log('checked', 'mikrotik', "Tes koneksi berhasil ke router: {$router->name}");

            return back()->with('success', "Koneksi ke {$router->name} ({$router->host}:{$router->port}) BERHASIL.");
        }

        $router->update([
            'last_checked_at' => now(),
            'last_status' => ['online' => false, 'error' => $errstr ?: 'Connection timeout'],
        ]);

        return back()->with('error', "Gagal terhubung ke {$router->host}:{$router->port} ($errstr)");
    }

    public function destroy(Router $router): RedirectResponse
    {
        $name = $router->name;
        $router->delete();
        ActivityLog::log('deleted', 'mikrotik', "Menghapus router: {$name}");

        return redirect()->route('admin.routers.index')->with('success', 'Router berhasil dihapus.');
    }
}
