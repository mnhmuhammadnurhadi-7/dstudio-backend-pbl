<?php

/**
 * Migration: Create Orders Table
 * Membuat tabel untuk menyimpan data pesanan customer
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
        Schema::create('orders', function (Blueprint $table) {
            // Primary Key auto increment
            $table->id();
            
            // Kode tiket unik (DST-001, DST-002, dst)
            $table->string('ticket_id', 30)->unique();
            
            // Data customer
            $table->string('name', 100);    // Nama customer
            $table->string('phone', 20);    // Nomor WhatsApp
            
            // Foreign key ke tabel services (relasi)
            $table->foreignId('service_id')->constrained('services');
            
            // Data pesanan
            $table->text('notes')->nullable();        // Catatan untuk editor (opsional)
            $table->text('photo_link');               // Link Google Drive foto asli
            $table->text('result_link')->nullable();  // Link Google Drive hasil edit
            
            // Status pesanan (flow: pending -> verified -> processing -> done)
            $table->enum('status', ['pending', 'verified', 'processing', 'done'])
                  ->default('pending');
            
            // Rating dari customer (1-5, opsional)
            $table->tinyInteger('rating')->nullable();
            
            // Status pembayaran (unpaid -> paid)
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            
            // Total harga dalam rupiah
            $table->integer('total_price');
            
            // Timestamp: created_at & updated_at
            $table->timestamps();
        });
    }

    /**
     * Rollback migration (menghapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
