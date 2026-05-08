<?php

/**
 * Migration: Create Site Settings Table
 * Tabel untuk menyimpan pengaturan website (CMS)
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
        Schema::create('site_settings', function (Blueprint $table) {
            // Primary key auto increment
            $table->integer('id')->autoIncrement()->primary();
            
            // Data setting
            $table->string('setting_key', 100)->unique(); // Key unik
            $table->text('setting_value');                // Value setting
            $table->string('keterangan', 255)->nullable(); // Keterangan opsional
            
            // Timestamp dengan auto update
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Rollback migration (menghapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
