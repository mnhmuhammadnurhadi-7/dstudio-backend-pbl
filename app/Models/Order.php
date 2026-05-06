<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Order
 * Merepresentasikan tabel orders di database
 * Menyimpan data pesanan dari customer
 */
class Order extends Model
{
    use HasFactory;

    /**
     * $fillable: Kolom yang boleh diisi massal (mass assignment)
     * Kolom ini bisa diisi langsung via Order::create([...])
     */
    protected $fillable = [
        'ticket_id',      // Kode unik tiket (DST-001, DST-002, dll)
        'name',           // Nama customer
        'phone',          // Nomor WhatsApp
        'service_id',     // Foreign key ke tabel services
        'notes',          // Catatan untuk editor
        'photo_link',     // Link Google Drive foto asli
        'result_link',    // Link Google Drive hasil edit
        'status',         // Status order: pending, verified, processing, done
        'rating',         // Rating 1-5 dari customer (nullable)
        'payment_status', // Status bayar: unpaid, paid
        'total_price',    // Total harga dalam rupiah
    ];

    /**
     * $casts: Casting tipe data saat diambil dari database
     * Memastikan data dikonversi ke tipe yang benar
     */
    protected $casts = [
        'rating' => 'integer',      // Konversi ke integer
        'total_price' => 'integer', // Konversi ke integer
    ];

    /**
     * Relasi: Order belongs to Service
     * Setiap order terkait dengan satu layanan
     * Contoh penggunaan: $order->service (mengakses data layanan)
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
