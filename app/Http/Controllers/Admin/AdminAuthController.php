<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * AdminAuthController
 * Controller untuk autentikasi admin (login/logout)
 * Menggunakan session-based auth (bukan Laravel Auth default)
 */
class AdminAuthController extends Controller
{
    /**
     * Menampilkan halaman login admin
     */
    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Proses login admin
     * Verifikasi username dan password, simpan data ke session
     */
    public function login(Request $request)
    {
        // Validasi input form login
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari admin berdasarkan username
        $admin = Admin::where('username', $validated['username'])->first();

        // Verifikasi password menggunakan Hash::check
        // Hash::check membandingkan plaintext password dengan hash di database
        if ($admin && Hash::check($validated['password'], $admin->password)) {
            // Simpan data admin ke session setelah login berhasil
            session([
                'admin_id' => $admin->id,        // ID untuk identifikasi
                'admin_role' => $admin->role,    // Role untuk authorization
                'admin_name' => $admin->name,    // Nama untuk ditampilkan di UI
            ]);

            // Redirect ke dashboard admin
            return redirect()->route('admin.dashboard');
        }

        // Jika gagal, kembali ke login dengan pesan error
        return redirect()->back()->with('error', 'Username atau password salah.');
    }

    /**
     * Proses logout admin
     * Hapus data session admin dan redirect ke halaman login
     */
    public function logout()
    {
        // Hapus semua data admin dari session
        session()->forget(['admin_id', 'admin_role', 'admin_name']);
        
        // Redirect ke halaman login
        return redirect('/admin18908');
    }
}
