<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * AdminOrderController
 * Controller untuk mengelola order dari sisi admin
 * Update status, upload hasil edit, konfirmasi pembayaran
 */
class AdminOrderController extends Controller
{
    /**
     * Update status order
     * Flow status: pending -> verified -> processing -> done
     * Route: PATCH /admin18908/orders/{order}/status
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Validasi status yang diperbolehkan sesuai enum di database
        $validated = $request->validate([
            'status' => 'required|in:terkirim,diproses,selesai,revisi,dibatalkan',
        ]);

        // Update status pesanan di database
        $order->update(['status_pesanan' => $validated['status']]);

        return redirect()->back()->with('success', 'Status pesanan diperbarui.');
    }

    /**
     * Upload link hasil edit foto
     * Admin paste URL Google Drive hasil edit ke form
     * Route: PATCH /admin18908/orders/{order}/result
     */
    public function uploadResult(Request $request, Order $order)
    {
        // Validasi URL hasil
        $validated = $request->validate([
            'result_link' => 'required|url',
        ]);

        // Simpan link hasil ke database
        $order->update(['link_foto_hasil' => $validated['result_link']]);

        return redirect()->back()->with('success', 'Link hasil berhasil diupload.');
    }

    /**
     * Konfirmasi pembayaran manual oleh admin
     * Set payment_status dari 'unpaid' menjadi 'paid'
     * Route: PATCH /admin18908/orders/{order}/payment
     */
    public function confirmPayment(Request $request, Order $order)
    {
        // Update status menjadi 'diproses' sebagai konfirmasi pembayaran
        $order->update(['status_pesanan' => 'diproses']);

        return redirect()->back()->with('success', 'Pembayaran dikonfirmasi dan status diubah ke Diproses.');
    }
}
