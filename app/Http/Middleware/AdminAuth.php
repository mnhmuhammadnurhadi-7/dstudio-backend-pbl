<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * AdminAuth Middleware
 * Memeriksa session admin sebelum melanjutkan request.
 * Jika admin belum login, kembalikan HTTP 401 JSON.
 */
class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Cek session admin_id untuk memastikan admin sudah login
        if (!session('admin_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Admin not logged in'
            ], 401);
        }

        // Jika sudah login, lanjutkan request ke controller berikutnya
        return $next($request);
    }
}
