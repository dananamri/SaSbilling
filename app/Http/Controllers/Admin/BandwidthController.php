<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BandwidthSnapshot;
use App\Models\Router;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class BandwidthController extends Controller
{
    public function index(): View
    {
        $routers = Router::where('is_active', true)->orderBy('name')->get();

        return view('admin.bandwidth.index', compact('routers'));
    }

    public function chart(Router $router): JsonResponse
    {
        $snapshots = BandwidthSnapshot::where('router_id', $router->id)
            ->orderByDesc('recorded_at')
            ->limit(60)
            ->orderBy('recorded_at')
            ->get();

        return response()->json([
            'labels' => $snapshots->pluck('recorded_at')->map(fn ($t) => $t->format('H:i:s')),
            'rx' => $snapshots->pluck('rx_bps'),
            'tx' => $snapshots->pluck('tx_bps'),
        ]);
    }

    public function poll(Router $router): JsonResponse
    {
        $last = $router->lastStatus ?? null;
        $rx = rand(1_000_000, 90_000_000);
        $tx = rand(500_000, 50_000_000);

        BandwidthSnapshot::create([
            'router_id' => $router->id,
            'interface_name' => 'ether1-WAN',
            'rx_bytes' => $rx * 10,
            'tx_bytes' => $tx * 10,
            'rx_bps' => $rx,
            'tx_bps' => $tx,
        ]);

        return response()->json([
            'rx_mbps' => round($rx / 1_000_000, 2),
            'tx_mbps' => round($tx / 1_000_000, 2),
            'time' => now()->format('H:i:s'),
        ]);
    }
}
