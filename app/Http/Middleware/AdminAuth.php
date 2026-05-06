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
        // Jika tidak ada, artinya user belum login
        if (!session('admin_id')) {
            // Redirect ke halaman login admin
            return redirect('/admin18908');
        }
        
        // Jika sudah login, lanjutkan ke request berikutnya
        return $next($request);
    }
}
