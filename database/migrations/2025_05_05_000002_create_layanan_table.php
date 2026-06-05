<?php

/**
 * Migration: Create Layanan Table
 * Tabel untuk menyimpan data layanan editing foto
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration (membuat tabel)
     */
    public function up(): void
    {
        Schema::create('layanan', function (Blueprint $table) {
            // Primary key dengan custom name
            $table->integer('id_layanan')->autoIncrement();
            
            // Data layanan
            $table->string('nama_layanan', 100);         // Nama layanan
            $table->text('deskripsi')->nullable();        // Deskripsi opsional
            $table->bigInteger('harga');                  // Harga dalam rupiah
            $table->tinyInteger('is_active')->default(1); // 1=aktif, 0=nonaktif
            
            // Tidak ada timestamps sesuai ERD
        });
    }

    /**
     * Rollback migration (menghapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
