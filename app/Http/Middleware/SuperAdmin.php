<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * SuperAdmin Middleware
 * Memeriksa apakah admin yang sedang login mempunyai role superadmin.
 */
class SuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Jika role admin bukan superadmin, tolak akses
        if (session('admin_role') !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden - Superadmin access required'
            ], 403);
        }

        // Lanjutkan request jika role valid
        return $next($request);
    }
}
