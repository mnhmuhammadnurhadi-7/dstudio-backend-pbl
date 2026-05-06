<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 * Entry point untuk semua seeder
 * Dijalankan dengan: php artisan db:seed atau php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Jalankan semua seeder
     * Method run() dieksekusi saat db:seed dijalankan
     */
    public function run(): void
    {
        // Panggil seeder lain secara berurutan
        $this->call([
            ServiceSeeder::class,      // Buat layanan default
            AdminSeeder::class,        // Buat akun admin default
            SiteContentSeeder::class,  // Buat konten website default
        ]);
    }
}
