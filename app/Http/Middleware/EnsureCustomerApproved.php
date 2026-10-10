<?php

namespace App\Http\Middleware;

use App\Enums\CustomerStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('customer');

        if ($user && $user->status === CustomerStatus::Pending) {
            auth('customer')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('customer.pending-approval', ['customer' => $user->id])
                ->with('warning', 'Akun Anda masih menunggu persetujuan admin.');
        }

        if ($user && $user->status === CustomerStatus::Inactive) {
            auth('customer')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('customer.login')
                ->withErrors(['login' => 'Akun Anda dinonaktifkan. Hubungi admin.']);
        }

        return $next($request);
    }
}
