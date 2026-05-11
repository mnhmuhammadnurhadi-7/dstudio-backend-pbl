<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SuperAdmin Middleware
 * Middleware untuk membatasi akses hanya untuk Super Admin
 * Digunakan untuk route-route sensitif seperti manajemen admin dan CMS
 */
class SuperAdmin
{
    /**
     * Handle method: cek role admin yang sedang login
     * Hanya izinkan jika role = 'superadmin'
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('admin_role') !== 'superadmin') {
            // Jika request API, return JSON 403
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Superadmin only.',
                ], 403);
            }
            
            // Untuk web, abort dengan 403
            abort(403, 'Akses ditolak.');
        }
        
        return $next($request);
    }
}
