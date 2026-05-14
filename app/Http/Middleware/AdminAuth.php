<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * AdminAuth Middleware
 * Cek apakah admin sudah login via session
 */
class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Admin not logged in'
            ], 401);
        }

        return $next($request);
    }
}
