<?php

/**
 * Migration: Create Rating Table
 * Tabel untuk menyimpan rating dan ulasan customer untuk pesanan
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
        Schema::create('rating', function (Blueprint $table) {
            // Primary key auto increment
            $table->integer('id_rating')->autoIncrement()->primary();
            
            // Foreign key ke pesanan (UNIQUE - satu pesanan hanya bisa satu rating)
            $table->string('kode_tiket', 20);
            
            // Data rating
            $table->tinyInteger('nilai_rating');          // Nilai rating 1-5
            $table->text('ulasan')->nullable();           // Ulasan customer
            
            // Timestamp
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            
            // Foreign key constraint
            $table->foreign('kode_tiket')
                  ->references('kode_tiket')
                  ->on('pesanan')
                  ->onDelete('cascade') // Jika pesanan dihapus, rating ikut terhapus
                  ->unique(); // Pastikan satu pesanan hanya satu rating
        });
    }

    /**
     * Rollback migration (menghapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('rating');
    }
};
