<?php

/**
 * Migration: Create Pesanan Table
 * Tabel untuk menyimpan data pesanan customer
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
        Schema::create('pesanan', function (Blueprint $table) {
            // Primary key: kode_tiket (bukan auto increment)
            $table->string('kode_tiket', 20)->primary();
            
            // Foreign keys
            $table->integer('id_layanan');
            $table->integer('id_admin')->nullable();
            
            // Data customer
            $table->string('nama_pelanggan', 100);     // Nama customer
            $table->string('no_wa', 20);               // Nomor WhatsApp (bukan INT)
            
            // Data pesanan
            $table->text('link_foto_mentah');          // Link Google Drive foto asli
            $table->text('catatan')->nullable();        // Catatan untuk editor
            $table->bigInteger('total_bayar');          // Total harga dalam rupiah
            
            // Status pesanan (flow: terkirim -> diproses -> selesai/revisi/dibatalkan)
            $table->enum('status_pesanan', ['terkirim', 'diproses', 'selesai', 'revisi', 'dibatalkan'])
                  ->default('terkirim');
            
            $table->text('link_foto_hasil')->nullable(); // Link Google Drive hasil edit
            
            // Timestamps
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('selesai_at')->nullable();
            
            // Foreign key constraints
            $table->foreign('id_layanan')
                  ->references('id_layanan')
                  ->on('layanan')
                  ->onDelete('cascade'); // Jika layanan dihapus, pesanan ikut terhapus
                  
            $table->foreign('id_admin')
                ->references('id_admin')
                ->on('admins')
                ->onDelete('set null'); // Jika admin dihapus, id_admin jadi NULL
        });
    }

    /**
     * Rollback migration (menghapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
