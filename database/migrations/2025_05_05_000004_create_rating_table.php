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
            $table->string('kode_tiket', 20)->unique();

            // Data rating
            $table->tinyInteger('nilai_rating');          // Nilai rating 1-5
            $table->text('ulasan')->nullable();           // Ulasan customer

            // Timestamps (created_at, updated_at)
            $table->timestamps();

            // Foreign key constraint (separate, tanpa chaining ->unique())
            $table->foreign('kode_tiket')
                  ->references('kode_tiket')
                  ->on('pesanan')
                  ->onDelete('cascade'); // Jika pesanan dihapus, rating ikut terhapus
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
