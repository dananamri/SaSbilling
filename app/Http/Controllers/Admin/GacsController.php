<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcsDevice;
use Illuminate\View\View;

class GacsController extends Controller
{
    public function index(): View
    {
        $devices = AcsDevice::with('customer')->orderByDesc('last_inform_at')->paginate(15);

        return view('admin.gacs.index', compact('devices'));
    }
}
