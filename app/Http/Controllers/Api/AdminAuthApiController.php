<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * AdminAuthApiController
 * Controller untuk autentikasi admin via API (JSON)
 * Digunakan oleh React frontend untuk login/logout
 */
class AdminAuthApiController extends Controller
{
    /**
     * Proses login admin via API
     * Return JSON untuk React frontend
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $validated['username'])->first();

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
     * Logout admin via API
     */
    public function logout()
    {
        session()->forget(['admin_id', 'admin_role', 'admin_name']);
        
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    /**
     * Get current authenticated admin info
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
