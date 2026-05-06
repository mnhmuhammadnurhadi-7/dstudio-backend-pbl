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
        // Cek session admin_role
        // abort(403) akan menampilkan halaman error 403 Forbidden
        if (session('admin_role') !== 'superadmin') {
            abort(403, 'Akses ditolak.');
        }
        
        // Jika role superadmin, lanjutkan request
        return $next($request);
    }
}
