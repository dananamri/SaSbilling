<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ConnectionController extends Controller
{
    public function index(): View
    {
        $customer = auth('customer')->user();

        // Data koneksi - siap untuk integrasi MikroTik/RADIUS
        $connection = [
            'status' => $customer->status->value === 'active' ? 'online' : 'offline',
            'ip_address' => null,
            'username_pppoe' => null,
            'connected_at' => null,
            'duration' => null,
            'download_speed' => null,
            'upload_speed' => null,
            'last_online' => null,
        ];

        return view('customer.connection.index', compact('customer', 'connection'));
    }
}
