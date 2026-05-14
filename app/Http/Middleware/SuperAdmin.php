<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * SuperAdmin Middleware
 * Cek apakah user adalah superadmin
 */
class SuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (session('admin_role') !== 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden - Superadmin access required'
            ], 403);
        }

        return $next($request);
    }
}
