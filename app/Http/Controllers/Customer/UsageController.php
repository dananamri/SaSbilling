<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class UsageController extends Controller
{
    public function index(): View
    {
        $customer = auth('customer')->user();

        // Data pemakaian - siap untuk integrasi MikroTik/RADIUS
        $usage = [
            'total_download' => 0,
            'total_upload' => 0,
            'today_download' => 0,
            'today_upload' => 0,
            'month_download' => 0,
            'month_upload' => 0,
            'connection_duration' => 0,
            'daily_usage' => [],
            'connection_history' => [],
        ];

        return view('customer.usage.index', compact('customer', 'usage'));
    }
}
