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
 * Controller untuk admin dashboard via API (JSON).
 * Menangani order, layanan, admins, dan CMS untuk panel admin.
 * Semua method mengembalikan response JSON.
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

        if ($status) {
            if ($status === 'revisi') {
                $query->whereRaw('LOWER(keterangan_status) = ?', ['revisi']);
            } elseif ($status === 'selesai') {
                $query->where('status_pesanan', 'selesai')
                      ->where(function($q) {
                          $q->whereRaw('LOWER(keterangan_status) != ?', ['revisi'])
                            ->orWhereNull('keterangan_status');
                      });
            } elseif (in_array($status, ['terkirim', 'diproses'])) {
                $query->where('status_pesanan', $status);
            }
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
            'selesai' => Pesanan::where('status_pesanan', 'selesai')
                        ->where(function($q) {
                            $q->whereRaw('LOWER(keterangan_status) != ?', ['revisi'])
                              ->orWhereNull('keterangan_status');
                        })->count(),
            'revisi' => Pesanan::whereRaw('LOWER(keterangan_status) = ?', ['revisi'])->count(),
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

        $query = Pesanan::with(['layanan', 'admin:id_admin,nama_admin', 'adminUpdatedBy:id_admin,nama_admin', 'rating'])
            ->whereIn('status_pesanan', ['selesai', 'revisi']);

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
            'finished_photo_link' => 'nullable|url',
        ]);

        $updateData = [
            'status_pesanan' => $validated['status'],
            'admin_updated_by' => session('admin_id'),
            'admin_updated_at' => now(),
        ];

        // Set admin pertama yang mengerjakan pesanan (jika belum ada)
        if (!$order->id_admin) {
            $updateData['id_admin'] = session('admin_id');
        }

        if ($validated['status'] === 'selesai') {
            $updateData['keterangan_status'] = 'fix';
            $updateData['selesai_at'] = now();
            // Update link foto hasil jika disediakan
            if (isset($validated['finished_photo_link'])) {
                $updateData['link_foto_hasil'] = $validated['finished_photo_link'];
            }
        } elseif ($validated['status'] === 'revisi') {
            // Status revisi tetap disimpan sebagai 'selesai' dengan keterangan_status 'Revisi'
            // agar histori tetap terjaga di tabel selesai
            $updateData['status_pesanan'] = 'selesai';
            $updateData['keterangan_status'] = 'Revisi';
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
     * Upload result link for order
     */
    public function updateResult(Request $request, Pesanan $order)
    {
        try {
            // Cek session admin
            if (!session('admin_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin tidak terautentikasi, silakan login kembali',
                ], 401);
            }

            $validated = $request->validate([
                'result_link' => 'required|string|max:500',
            ], [
                'result_link.required' => 'Link hasil wajib diisi',
                'result_link.string' => 'Link hasil harus berupa string',
                'result_link.max' => 'Link hasil maksimal 500 karakter',
            ]);

            // Basic URL validation (lebih fleksibel)
            $link = $validated['result_link'];
            if (!filter_var($link, FILTER_VALIDATE_URL) && !str_starts_with($link, 'http://') && !str_starts_with($link, 'https://')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link harus berupa URL yang valid (dimulai dengan http:// atau https://)',
                ], 422);
            }

            $order->update([
                'link_foto_hasil' => $validated['result_link'],
                'admin_updated_by' => session('admin_id'),
                'admin_updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Link hasil berhasil diupload',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update order status with validation for link_hasil
     */
    public function updateOrderStatus(Request $request, $kode)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|string|in:terkirim,diproses,selesai,revisi,dibatalkan',
                'link_hasil' => 'nullable|string|max:500',
            ]);

            // Cari pesanan berdasarkan kode
            $order = Pesanan::where('kode_tiket', $kode)->first();
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan',
                ], 404);
            }

            // Validasi link_hasil untuk status selesai atau revisi
            if ($validated['status'] === 'selesai' || $validated['status'] === 'revisi') {
                // Cek link_hasil di database
                $existingLink = $order->link_foto_hasil;
                $newLink = $validated['link_hasil'] ?? null;

                // Untuk status 'selesai', link_hasil WAJIB ada (baik dari database atau input baru)
                if ($validated['status'] === 'selesai' && empty($existingLink) && empty($newLink)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'URL foto hasil belum diisi. Isi URL terlebih dahulu sebelum mengubah status menjadi Selesai.',
                    ], 422);
                }
            }

            // Update status
            $updateData = ['status_pesanan' => $validated['status']];

            // Set link_foto_hasil jika ada link baru
            if (!empty($validated['link_hasil'])) {
                $updateData['link_foto_hasil'] = $validated['link_hasil'];
            }

            // Set admin pertama yang mengerjakan pesanan
            if (!$order->id_admin) {
                $updateData['id_admin'] = session('admin_id');
            }

            // Set keterangan_status untuk revisi
            if ($validated['status'] === 'revisi') {
                $updateData['status_pesanan'] = 'selesai'; // Tetap selesai di database
                $updateData['keterangan_status'] = 'Revisi';
            } elseif ($validated['status'] === 'selesai') {
                $updateData['keterangan_status'] = 'fix';
                $updateData['selesai_at'] = now();
            }

            // Update admin yang terakhir mengubah
            $updateData['admin_updated_by'] = session('admin_id');
            $updateData['admin_updated_at'] = now();

            $order->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diperbarui',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
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

    /**
     * Confirm completed order and update to public view
     */
    public function confirmCompletedOrder(Pesanan $order)
    {
        if ($order->status_pesanan !== 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya bisa mengkonfirmasi pesanan yang sudah selesai',
            ], 400);
        }

        if (!$order->link_foto_hasil) {
            return response()->json([
                'success' => false,
                'message' => 'Upload link hasil foto terlebih dahulu',
            ], 400);
        }

        $order->update([
            'admin_updated_by' => session('admin_id'),
            'admin_updated_at' => now(),
            'keterangan_status' => 'completed_confirmed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan selesai dikonfirmasi dan sudah dapat dilihat oleh pelanggan',
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
     * Get single admin
     */
    public function getAdmin(Admin $admin)
    {
        return response()->json($admin);
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
     * Update admin
     */
    public function updateAdmin(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'nama_admin' => 'required|string|max:100',
            // Use primary key id_admin for unique rule to exclude current record
            'username' => 'required|string|max:50|unique:admins,username,' . $admin->id_admin . ',id_admin',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,superadmin',
        ]);

        // Only update password if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        $admin->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil diperbarui',
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

    /**
     * Delete order
     */
    public function deleteOrder(Pesanan $order)
    {
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dihapus',
        ]);
    }
}
