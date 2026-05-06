<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * AdminManageController
 * Controller untuk Super Admin mengelola akun admin lain
 * Hanya Super Admin yang bisa akses (dilindungi middleware superadmin)
 */
class AdminManageController extends Controller
{
    /**
     * Menampilkan daftar semua admin
     * Method: GET /admin18908/admins
     */
    public function index()
    {
        $admins = Admin::latest()->get();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show form untuk tambah admin baru
     * Method: GET /admin18908/admins/create
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Simpan admin baru ke database
     * Method: POST /admin18908/admins
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:admins', // Username harus unik
            'password' => 'required|string|min:6',              // Password minimal 6 karakter
            'role' => 'required|in:admin,superadmin',           // Role harus valid
        ]);

        // Hash password sebelum disimpan ke database
        // Hash::make() mengenkripsi password menggunakan bcrypt
        $validated['password'] = Hash::make($validated['password']);

        // Simpan ke database
        Admin::create($validated);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Hapus akun admin
     * Method: DELETE /admin18908/admins/{admin}
     */
    public function destroy(Admin $admin)
    {
        // Cek: admin tidak boleh menghapus akun sendiri
        if ($admin->id === session('admin_id')) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Hapus dari database
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}
