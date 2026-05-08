<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

/**
 * AdminServiceController
 * Resource Controller untuk CRUD Layanan
 * Route::resource() otomatis membuat route untuk semua method ini
 */
class AdminServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     * Method: GET /admin18908/services
     */
    public function index()
    {
        // Ambil semua layanan, urutkan berdasarkan id_layanan (primary key)
        $services = Layanan::orderBy('id_layanan', 'desc')->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     * Method: GET /admin18908/services/create
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     * Method: POST /admin18908/services
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:100',    // Nama wajib, max 100 karakter
            'harga' => 'required|integer|min:0',    // Harga wajib, bilangan bulat, min 0
            'deskripsi' => 'nullable|string',  // Deskripsi opsional
            'is_active' => 'boolean',              // Checkbox boolean
        ]);

        // Checkbox: jika tidak dicentang, request tidak ada key 'is_active'
        // Maka set manual menjadi false
        $validated['is_active'] = $request->has('is_active');

        // Create: simpan ke database
        Layanan::create($validated);

        // Redirect ke index dengan pesan sukses
        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     * Method: GET /admin18908/services/{service}/edit
     */
    public function edit(Layanan $layanan)
    {
        // Route Model Binding: Laravel otomatis mencari Layanan berdasarkan ID
        return view('admin.services.edit', compact('layanan'));
    }

    /**
     * Update the specified resource in storage.
     * Method: PUT /admin18908/services/{service}
     */
    public function update(Request $request, Layanan $layanan)
    {
        // Validasi sama seperti store
        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Update: perbarui data di database
        $layanan->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     * Method: DELETE /admin18908/services/{service}
     */
    public function destroy(Layanan $layanan)
    {
        // Delete: hapus dari database
        $layanan->delete();

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
