<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Layanan;
use App\Models\Admin;
use App\Models\SiteSettings;
use App\Models\Rating;
use Illuminate\Http\Request;

/**
 * AdminApiController
 * Controller untuk admin dashboard via API (JSON)
 * Semua method return JSON untuk React frontend
 */
class AdminApiController extends Controller
{
    // ═══════════════════════════════════════════════════════════════
    // DASHBOARD - ORDERS
    // ═══════════════════════════════════════════════════════════════

    /**
     * Get all orders with filters and counts
     */
    public function getOrders(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $query = Pesanan::with(['layanan', 'admin', 'adminUpdatedBy'])
            ->where('status_pesanan', '!=', 'dibatalkan');

        if ($status && in_array($status, ['terkirim', 'diproses', 'selesai'])) {
            $query->where('status_pesanan', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('kode_tiket', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->get();

        $counts = [
            'all' => Pesanan::where('status_pesanan', '!=', 'dibatalkan')->count(),
            'terkirim' => Pesanan::where('status_pesanan', 'terkirim')->count(),
            'diproses' => Pesanan::where('status_pesanan', 'diproses')->count(),
            'selesai' => Pesanan::where('status_pesanan', 'selesai')->count(),
        ];

        return response()->json([
            'orders' => $orders,
            'counts' => $counts,
        ]);
    }

    /**
     * Get completed orders only
     */
    public function getCompletedOrders(Request $request)
    {
        $search = $request->get('search');

        $query = Pesanan::with(['layanan', 'admin', 'adminUpdatedBy', 'rating'])
            ->where('status_pesanan', 'selesai');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('kode_tiket', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'orders' => $query->latest()->get(),
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Pesanan $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:terkirim,diproses,selesai,revisi,dibatalkan',
            'catatan_revisi' => 'nullable|string',
        ]);

        $updateData = [
            'status_pesanan' => $validated['status'],
            'admin_updated_by' => session('admin_id'),
            'admin_updated_at' => now(),
        ];

        if ($validated['status'] === 'selesai') {
            $updateData['keterangan_status'] = 'fix';
            $updateData['selesai_at'] = now();
        } elseif ($validated['status'] === 'revisi') {
            $updateData['keterangan_status'] = 'revisi';
            if ($request->has('catatan_revisi')) {
                $updateData['catatan_revisi'] = $validated['catatan_revisi'];
            }
        }

        $order->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan diperbarui',
        ]);
    }

    /**
     * Upload result link for completed order
     */
    public function updateResult(Request $request, Pesanan $order)
    {
        if ($order->status_pesanan !== 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Upload hasil hanya diperbolehkan jika status pesanan sudah "Selesai"',
            ], 400);
        }

        $validated = $request->validate([
            'result_link' => 'required|url',
        ]);

        $order->update([
            'link_foto_hasil' => $validated['result_link'],
            'admin_updated_by' => session('admin_id'),
            'admin_updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Link hasil berhasil diupload',
        ]);
    }

    /**
     * Confirm payment and change status to 'diproses'
     */
    public function confirmPayment(Pesanan $order)
    {
        $order->update([
            'status_pesanan' => 'diproses',
            'admin_updated_by' => session('admin_id'),
            'admin_updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran dikonfirmasi dan status diubah ke Diproses',
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    // SERVICES
    // ═══════════════════════════════════════════════════════════════

    /**
     * Get all services
     */
    public function getServices()
    {
        $services = Layanan::orderBy('id_layanan', 'desc')->get();
        
        return response()->json([
            'services' => $services,
        ]);
    }

    /**
     * Get single service
     */
    public function getService(Layanan $service)
    {
        return response()->json([
            'service' => $service,
        ]);
    }

    /**
     * Create new service
     */
    public function createService(Request $request)
    {
        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->input('is_active', false);
        
        Layanan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil ditambahkan',
        ]);
    }

    /**
     * Update service
     */
    public function updateService(Request $request, Layanan $service)
    {
        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->input('is_active', false);
        
        $service->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil diperbarui',
        ]);
    }

    /**
     * Delete service
     */
    public function deleteService(Layanan $service)
    {
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil dihapus',
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    // ADMINS MANAGEMENT
    // ═══════════════════════════════════════════════════════════════

    /**
     * Get all admins
     */
    public function getAdmins()
    {
        $admins = Admin::latest()->get();
        
        return response()->json([
            'admins' => $admins,
        ]);
    }

    /**
     * Create new admin
     */
    public function createAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:admins',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,superadmin',
        ]);

        $validated['nama_admin'] = $validated['name'];
        unset($validated['name']);

        Admin::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil ditambahkan',
        ]);
    }

    /**
     * Delete admin
     */
    public function deleteAdmin(Admin $admin)
    {
        if ($admin->id_admin === session('admin_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri',
            ], 400);
        }

        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil dihapus',
        ]);
    }

    // ═══════════════════════════════════════════════════════════════
    // CMS
    // ═══════════════════════════════════════════════════════════════

    /**
     * Get all CMS contents
     */
    public function getCms()
    {
        $contents = SiteSettings::all()->keyBy('setting_key');
        
        return response()->json([
            'contents' => $contents,
        ]);
    }

    /**
     * Update CMS contents
     */
    public function updateCms(Request $request)
    {
        $keys = [
            'hero_title',
            'hero_subtitle',
            'about_text',
            'nomor_wa_bisnis',
            'qris_image_path',
            'instagram_url',
            'nama_studio',
            'visi',
            'misi',
        ];

        foreach ($keys as $key) {
            $value = $request->input($key);
            if ($request->has($key) && !empty($value)) {
                SiteSettings::updateOrCreate(
                    ['setting_key' => $key],
                    ['setting_value' => $value]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Konten berhasil diperbarui',
        ]);
    }
}
