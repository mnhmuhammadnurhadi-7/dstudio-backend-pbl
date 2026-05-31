<?php

/**
 * Migration: Add Status Details to Pesanan Table
 * Menambah kolom keterangan_status dan catatan_revisi untuk tracking FIX/revisi
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration
     */
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Keterangan status: 'fix' atau 'revisi' untuk pesanan selesai
            $table->enum('keterangan_status', ['fix', 'revisi'])
                  ->nullable()
                  ->after('status_pesanan');
            
            // Catatan untuk revisi
            $table->text('catatan_revisi')
                  ->nullable()
                  ->after('keterangan_status');
            
            // Admin yang terakhir mengupdate status (track siapa yang mengerjakan)
            $table->integer('admin_updated_by')
                  ->nullable()
                  ->after('id_admin');
                  
            // Timestamp update terakhir oleh admin
            $table->timestamp('admin_updated_at')
                  ->nullable()
                  ->after('created_at');
        });
    }

    /**
     * Rollback migration
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['keterangan_status', 'catatan_revisi', 'admin_updated_by', 'admin_updated_at']);
        });
    }
};
