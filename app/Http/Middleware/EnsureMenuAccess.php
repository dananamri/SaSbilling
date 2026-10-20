<?php

namespace App\Http\Middleware;

use App\Support\AdminPermissions;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMenuAccess
{
    /**
     * Batasi akses route admin berdasarkan hak akses menu per role
     * yang diatur Super Admin melalui halaman Sistem > Hak Akses.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $routeName = $request->route()?->getName();

        if (! $user || $routeName === null || ! str_starts_with($routeName, 'admin.')) {
            return $next($request);
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Halaman pengaturan hak akses hanya untuk Super Admin,
        // meskipun role tersebut belum pernah disetel (default diizinkan semua).
        if (str_starts_with($routeName, 'admin.hak-akses')) {
            abort(403, 'Hanya Super Admin yang dapat mengatur hak akses.');
        }

        $key = AdminPermissions::resolveKey($routeName);

        // Route admin di luar daftar menu tidak dibatasi.
        if ($key === null) {
            return $next($request);
        }

        if (! AdminPermissions::can($user, $key)) {
            abort(403, 'Anda tidak memiliki hak akses untuk membuka menu ini.');
        }

        return $next($request);
    }
}
