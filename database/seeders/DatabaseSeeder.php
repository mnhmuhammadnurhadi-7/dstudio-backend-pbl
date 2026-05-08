<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 * Main seeder yang menjalankan semua seeder lainnya
 * Digunakan untuk mengisi database dengan data awal
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan semua seeder
     * Memanggil seeder untuk setiap tabel yang ada
     */
    public function run(): void
    {
        // Jalankan seeder untuk setiap tabel
        $this->call([
            AdminSeeder::class,         // Data admin (haris pertama karena relasi)
            LayananSeeder::class,       // Data layanan
            SiteSettingsSeeder::class,  // Data pengaturan website
        ]);

        $this->command->info('✓ DatabaseSeeder: Semua seeder berhasil dijalankan');
        $this->command->info('  Urutan: Admin → Layanan → SiteSettings');
    }
}
