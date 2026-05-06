<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * ServiceSeeder
 * Mengisi tabel services dengan data layanan default
 * DStudio menyediakan layanan editing foto untuk berbagai keperluan
 */
class ServiceSeeder extends Seeder
{
    /**
     * Jalankan seeder
     * Insert data layanan ke database
     */
    public function run(): void
    {
        // Daftar layanan default DStudio
        // Harga dalam rupiah (tanpa pemisah ribuan)
        $services = [
            ['name' => 'Foto KTM', 'price' => 15000],      // Kartu Tanda Mahasiswa
            ['name' => 'Foto KTP', 'price' => 15000],      // Kartu Tanda Penduduk
            ['name' => 'Foto Ijazah', 'price' => 20000],   // Foto untuk ijazah
            ['name' => 'Foto Visa USA', 'price' => 25000], // Foto visa (2x2 inch)
            ['name' => 'CV/Lamaran', 'price' => 20000],    // Foto untuk CV/Resume
        ];

        // Loop dan insert ke database menggunakan model
        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
