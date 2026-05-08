<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Order
 * Merepresentasikan tabel pesanan di database
 * Menyimpan data pesanan dari customer
 */
class Order extends Model
{
    use HasFactory;

    /**
     * Nama tabel (sesuai ERD)
     */
    protected $table = 'pesanan';

    /**
     * Primary key (sesuai ERD)
     */
    protected $primaryKey = 'kode_tiket';

    /**
     * Auto increment
     */
    public $incrementing = false;

    /**
     * Tipe primary key
     */
    protected $keyType = 'string';

    /**
     * Timestamps (ada created_at, tapi tidak ada updated_at)
     */
    const UPDATED_AT = null;

    /**
     * $fillable: Kolom yang boleh diisi massal (mass assignment)
     * Kolom ini bisa diisi langsung via Order::create([...])
     */
    protected $fillable = [
        'kode_tiket',      // Kode unik tiket (DST-001, DST-002, dll)
        'nama_pelanggan',  // Nama customer
        'no_wa',           // Nomor WhatsApp
        'id_layanan',      // Foreign key ke tabel layanan
        'catatan',         // Catatan untuk editor
        'link_foto_mentah', // Link Google Drive foto asli
        'link_foto_hasil', // Link Google Drive hasil edit
        'status_pesanan',  // Status pesanan
        'total_bayar',     // Total harga dalam rupiah
        'id_admin',        // Foreign key ke tabel admins
        'selesai_at',      // Timestamp selesai
    ];

    /**
     * $casts: Casting tipe data saat diambil dari database
     * Memastikan data dikonversi ke tipe yang benar
     */
    protected $casts = [
        'total_bayar' => 'integer', // Konversi ke integer
        'id_layanan' => 'integer',  // Foreign key ke integer
        'id_admin' => 'integer',    // Foreign key ke integer
        'selesai_at' => 'datetime', // Timestamp
    ];

    /**
     * Relasi: Order belongs to Layanan
     * Setiap pesanan terkait dengan satu layanan
     * Contoh penggunaan: $order->layanan (mengakses data layanan)
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    /**
     * Relasi: Order belongs to Admin
     * Setiap pesanan bisa ditugaskan ke satu admin
     * Contoh penggunaan: $order->admin (mengakses data admin)
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
