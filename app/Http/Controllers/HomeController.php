<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $packages = [
            [
                'name' => 'Home Basic',
                'speed' => 20,
                'price' => '150K',
                'features' => ['Unlimited FUP', 'Free Installasi', 'Support 24/7'],
                'icon' => 'wifi',
                'featured' => false,
            ],
            [
                'name' => 'Home Pro',
                'speed' => 50,
                'price' => '250K',
                'features' => ['Unlimited FUP', 'Free Installasi', 'Free Router WiFi 6', 'Support Prioritas 24/7'],
                'icon' => 'bolt',
                'featured' => true,
            ],
            [
                'name' => 'Business',
                'speed' => 100,
                'price' => '500K',
                'features' => ['Unlimited FUP', 'IP Static', 'SLA 99.9%', 'Support Dedicated 24/7'],
                'icon' => 'building',
                'featured' => false,
            ],
        ];

        return view('welcome', ['packages' => $packages]);
    }
}
