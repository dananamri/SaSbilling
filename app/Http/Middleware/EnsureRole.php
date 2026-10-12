<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * @param  string  ...$roles  role values allowed (superadmin, admin, kasir, teknisi, helpdesk, noc)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Akses ditolak.');
        }

        if (empty($roles)) {
            return $next($request);
        }

        $current = $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;

        if (! in_array($current, $roles, true)) {
            abort(403, 'Hak akses tidak mencukupi.');
        }

        if (! $user->is_active) {
            abort(403, 'Akun nonaktif.');
        }

        return $next($request);
    }
}
