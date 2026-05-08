<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan;

/**
 * LayananSeeder
 * Membuat data layanan awal untuk sistem
 * - Foto KTM
 * - Foto CV
 * - Foto Visa
 */
class LayananSeeder extends Seeder
{
    /**
     * Jalankan seeder
     * Membuat layanan default untuk sistem
     */
    public function run(): void
    {
        // Buat layanan Foto KTM
        Layanan::create([
            'nama_layanan' => 'Foto KTM',
            'deskripsi' => 'Jasa editing foto untuk Kartu Tanda Mahasiswa dengan background dan format sesuai standar universitas',
            'harga' => 15000,
            'is_active' => 1,
        ]);

        // Buat layanan Foto CV
        Layanan::create([
            'nama_layanan' => 'Foto CV',
            'deskripsi' => 'Jasa editing foto profesional untuk Curriculum Vitae dengan background formal dan pencahayaan optimal',
            'harga' => 20000,
            'is_active' => 1,
        ]);

        // Buat layanan Foto Visa
        Layanan::create([
            'nama_layanan' => 'Foto Visa',
            'deskripsi' => 'Jasa editing foto untuk keperluan visa dengan ukuran dan spesifikasi sesuai standar kedutaan',
            'harga' => 25000,
            'is_active' => 1,
        ]);

        $this->command->info('✓ LayananSeeder: 3 layanan berhasil dibuat');
        $this->command->info('  - Foto KTM: Rp15.000');
        $this->command->info('  - Foto CV: Rp20.000');
        $this->command->info('  - Foto Visa: Rp25.000');
    }
}
