<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * AdminAuthApiController
 * Controller untuk autentikasi admin via API (JSON).
 * Mendukung login, logout, dan pengecekan auth.
 */
class AdminAuthApiController extends Controller
{
    /**
     * Proses login admin via API.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Ambil admin berdasarkan username
        $admin = Admin::where('username', $validated['username'])->first();

        // Cek password dan login
        if ($admin && Hash::check($validated['password'], $admin->password)) {
            session([
                'admin_id' => $admin->id_admin,
                'admin_role' => $admin->role,
                'admin_name' => $admin->nama_admin,
            ]);

            return response()->json([
                'success' => true,
                'admin' => [
                    'id' => $admin->id_admin,
                    'name' => $admin->nama_admin,
                    'role' => $admin->role,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah.',
        ], 401);
    }

    /**
     * Logout admin via API.
     */
    public function logout()
    {
        // Hapus data login dari session
        session()->forget(['admin_id', 'admin_role', 'admin_name']);
        
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * Dapatkan informasi admin jika sudah login.
     */
    public function me()
    {
        if (!session('admin_id')) {
            return response()->json([
                'authenticated' => false,
            ], 401);
        }

        return response()->json([
            'authenticated' => true,
            'admin' => [
                'id' => session('admin_id'),
                'name' => session('admin_name'),
                'role' => session('admin_role'),
            ],
        ]);
    }
}
