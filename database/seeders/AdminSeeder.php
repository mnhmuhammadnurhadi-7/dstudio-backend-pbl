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
        // Buat akun Super Admin (gunakan updateOrCreate untuk memastikan password di-update)
        Admin::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'password' => Hash::make('superadmin183789'),
                'nama_admin' => 'Super Admin',
                'role' => 'superadmin',
            ]
        );

        // Buat akun Admin biasa (gunakan updateOrCreate untuk memastikan password di-update)
        Admin::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('admin@123887'),
                'nama_admin' => 'Admin User',
                'role' => 'admin',
            ]
        );

        $this->command->info('✓ AdminSeeder: 2 akun admin berhasil dibuat/diperbarui');
        $this->command->info('  - Super Admin: superadmin/superadmin183789');
        $this->command->info('  - Admin: admin/admin@123887');
    }
}
