<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Odp;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MapController extends Controller
{
    public function index(): View
    {
        $odps = Odp::all();
        $customers = Customer::whereNotNull('latitude')->whereNotNull('longitude')->get();

        return view('admin.map.index', compact('odps', 'customers'));
    }

    public function geojson(): JsonResponse
    {
        $odps = Odp::all()->map(fn ($o) => [
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [(float) $o->longitude, (float) $o->latitude],
            ],
            'properties' => [
                'type' => 'odp',
                'title' => $o->name.' ('.$o->code.')',
                'capacity' => "{$o->used_ports}/{$o->capacity}",
                'status' => $o->status,
            ],
        ]);

        $customers = Customer::whereNotNull('latitude')->whereNotNull('longitude')->get()->map(fn ($c) => [
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [(float) $c->longitude, (float) $c->latitude],
            ],
            'properties' => [
                'type' => 'customer',
                'name' => $c->name,
                'phone' => $c->phone,
                'status' => $c->status->value,
                'package' => $c->package?->name ?? '-',
            ],
        ]);

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $odps->concat($customers),
        ]);
    }
}
