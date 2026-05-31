<?php

/**
 * Migration: Create Admin Table
 * Tabel untuk menyimpan data akun admin
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
        Schema::create('admins', function (Blueprint $table) {
            // Primary key dengan custom name
            $table->integer('id_admin')->autoIncrement()->primary();
            
            // Data akun
            $table->string('username', 50)->unique();      // Username login
            $table->string('password', 255);              // Password (hashed)
            $table->string('nama_admin', 100);            // Nama lengkap admin
            
            // Role admin
            $table->enum('role', ['admin', 'superadmin'])->default('admin');
            
            // Timestamp hanya created_at
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Rollback migration (menghapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
