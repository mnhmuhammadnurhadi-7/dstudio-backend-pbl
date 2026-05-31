<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * AdminSeeder
 * Membuat data admin awal untuk sistem
 * - 1 Super Admin
 * - 1 Admin biasa
 */
class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder
     * Membuat akun admin default untuk login pertama kali
     */
    public function run(): void
    {
        // Buat akun Super Admin
        Admin::create([
            'username' => 'superadmin',
            'password' => Hash::make('superadmin123'), // Password akan di-hash otomatis
            'nama_admin' => 'Super Admin',
            'role' => 'superadmin',
        ]);

        // Buat akun Admin biasa
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'), // Password akan di-hash otomatis
            'nama_admin' => 'Admin User',
            'role' => 'admin',
        ]);

        $this->command->info('✓ AdminSeeder: 2 akun admin berhasil dibuat');
        $this->command->info('  - Super Admin: superadmin/superadmin123');
        $this->command->info('  - Admin: admin/admin123');
    }
}
