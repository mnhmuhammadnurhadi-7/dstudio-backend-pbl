<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * AdminSeeder
 * Membuat akun admin default untuk testing dan pertama kali setup
 * Super Admin: bisa akses semua fitur termasuk manajemen admin
 * Admin biasa: hanya bisa akses dashboard dan kelola order
 */
class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder
     * Buat 2 akun default: Super Admin dan Admin biasa
     */
    public function run(): void
    {
        // Akun Super Admin - punya akses penuh termasuk CMS dan manajemen admin
        Admin::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',           // Username untuk login
            'password' => Hash::make('superadmin123'), // Password di-hash dengan bcrypt
            'role' => 'superadmin',                // Role dengan hak akses tertinggi
        ]);

        // Akun Admin biasa - hanya kelola order dan verifikasi pembayaran
        Admin::create([
            'name' => 'Admin DStudio',
            'username' => 'admin',                 // Username untuk login
            'password' => Hash::make('admin123'),  // Password di-hash dengan bcrypt
            'role' => 'admin',                     // Role biasa (terbatas)
        ]);
    }
}
