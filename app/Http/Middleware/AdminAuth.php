<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdminAuth Middleware
 * Middleware untuk memeriksa apakah user sudah login sebagai admin
 * Digunakan untuk melindungi route-route admin
 */
class AdminAuth
{
    /**
     * Handle method: dieksekusi untuk setiap request yang menggunakan middleware ini
     * Cek session 'admin_id' untuk memverifikasi login
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada session admin_id
        if (!session('admin_id')) {
            // Jika request expects JSON (API), return 401
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login.',
                ], 401);
            }
            
            // Untuk web, redirect ke halaman login
            return redirect('/admin18908');
        }
        
        return $next($request);
    }
}
