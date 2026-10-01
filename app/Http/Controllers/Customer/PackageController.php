<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $customer = auth('customer')->user();
        $currentPackage = $customer->package;
        $availablePackages = Package::where('is_active', true)->get();

        return view('customer.packages.index', compact('customer', 'currentPackage', 'availablePackages'));
    }
}
