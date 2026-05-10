<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
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
     * Flow status: terkirim -> diproses -> selesai
     * Jika selesai, set keterangan_status = 'fix'
     * Jika revisi, set keterangan_status = 'revisi'
     * Track admin yang melakukan update
     * Route: PATCH /admin18908/orders/{order}/status
     */
    public function updateStatus(Request $request, Pesanan $order)
    {
        // Validasi status yang diperbolehkan sesuai enum di database
        $validated = $request->validate([
            'status' => 'required|in:terkirim,diproses,selesai,revisi,dibatalkan',
            'catatan_revisi' => 'nullable|string',
        ]);

        // Data yang akan diupdate
        $updateData = [
            'status_pesanan' => $validated['status'],
            'admin_updated_by' => session('admin_id'),
            'admin_updated_at' => now(),
        ];

        // Jika status selesai, set keterangan = fix dan selesai_at
        if ($validated['status'] === 'selesai') {
            $updateData['keterangan_status'] = 'fix';
            $updateData['selesai_at'] = now();
        }
        // Jika status revisi, set keterangan = revisi dan simpat catatan
        elseif ($validated['status'] === 'revisi') {
            $updateData['keterangan_status'] = 'revisi';
            if ($request->has('catatan_revisi')) {
                $updateData['catatan_revisi'] = $validated['catatan_revisi'];
            }
        }

        // Update status pesanan di database
        $order->update($updateData);

        return redirect()->back()->with('success', 'Status pesanan diperbarui.');
    }

    /**
     * Upload link hasil edit foto
     * Admin hanya bisa upload hasil jika status 'selesai'
     * Admin paste URL Google Drive hasil edit ke form
     * Route: PATCH /admin18908/orders/{order}/result
     */
    public function uploadResult(Request $request, Pesanan $order)
    {
        // Cek apakah status pesanan sudah 'selesai'
        if ($order->status_pesanan !== 'selesai') {
            return redirect()->back()->with('error', 'Upload hasil hanya diperbolehkan jika status pesanan sudah "Selesai".');
        }

        // Validasi URL hasil
        $validated = $request->validate([
            'result_link' => 'required|url',
        ]);

        // Simpan link hasil ke database dan track admin
        $order->update([
            'link_foto_hasil' => $validated['result_link'],
            'admin_updated_by' => session('admin_id'),
            'admin_updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Link hasil berhasil diupload.');
    }

    /**
     * Konfirmasi pembayaran manual oleh admin
     * Set status pesanan menjadi 'diproses' dan track admin
     * Route: PATCH /admin18908/orders/{order}/payment
     */
    public function confirmPayment(Request $request, Pesanan $order)
    {
        // Update status menjadi 'diproses' sebagai konfirmasi pembayaran
        $order->update([
            'status_pesanan' => 'diproses',
            'admin_updated_by' => session('admin_id'),
            'admin_updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran dikonfirmasi dan status diubah ke Diproses.');
    }
}
