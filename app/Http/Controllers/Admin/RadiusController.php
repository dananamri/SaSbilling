<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RadiusServer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RadiusController extends Controller
{
    public function index(): View
    {
        $servers = RadiusServer::paginate(10);

        return view('admin.radius.index', compact('servers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'host' => 'required|string|max:100',
            'auth_port' => 'required|integer',
            'acct_port' => 'required|integer',
            'secret' => 'required|string|max:100',
        ]);

        RadiusServer::create($validated);

        return back()->with('success', 'Server RADIUS berhasil disimpan.');
    }
}
