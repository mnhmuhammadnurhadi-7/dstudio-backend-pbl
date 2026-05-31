# Alur Kerja Backend

## Ringkasan Umum
Backend ini adalah aplikasi Laravel/PHP yang menyediakan API untuk frontend publik dan panel admin.

- Public API: pemesanan layanan foto, cek status, dan rating.
- Admin API: autentikasi admin, manajemen pesanan, layanan, admin, dan CMS.
- Middleware `admin.auth` memastikan hanya admin yang terautentikasi dapat mengakses route admin.
- Middleware `superadmin` membatasi akses fitur manajemen tertentu hanya untuk superadmin.

## Struktur File Utama

### Route
- `routes/api.php`
  - Mendefinisikan endpoint publik dan admin.
  - Group `admin.auth` untuk route admin yang memerlukan session login.
  - Group `superadmin` untuk route admin tertentu.

### Controller Publik
- `app/Http/Controllers/Api/HomeApiController.php`
  - Mengambil konten hero dan about dari `site_settings`.
- `app/Http/Controllers/Api/ServiceApiController.php`
  - Mengambil daftar layanan yang tersedia.
- `app/Http/Controllers/Api/OrderApiController.php`
  - Menangani proses pemesanan multi-step:
    1. `step1()` menampilkan layanan.
    2. `saveStep1()` menyimpan nama, WA, layanan, dan catatan ke session.
    3. `saveStep2()` menyimpan link foto mentah ke session.
    4. `step3()` menampilkan ringkasan service + QRIS.
    5. `saveStep3()` membuat record pesanan (`Pesanan`).
  - `show()` menampilkan detail pesanan.
  - `checkStatus()` memeriksa status berdasarkan `ticket_id`.
  - `submitRating()` menyimpan rating customer setelah pesanan selesai.

### Controller Admin
- `app/Http/Controllers/Api/AdminAuthApiController.php`
  - `login()` memeriksa username/password dan menyimpan session admin.
  - `logout()` menghapus session admin.
  - `me()` mengembalikan status autentikasi admin.
- `app/Http/Controllers/Api/AdminApiController.php`
  - `getOrders()` mengambil semua pesanan dengan filter status/search.
  - `getCompletedOrders()` mengambil pesanan selesai atau revisi.
  - `updateStatus()` memperbarui status pesanan.
  - `updateResult()` menyimpan link hasil edit.
  - `updateOrderStatus()` memperbarui status berdasarkan kode tiket.
  - `confirmPayment()` mengubah status menjadi `diproses`.
  - `confirmCompletedOrder()` mengonfirmasi pesanan selesai.
  - `getServices()`, `getService()`, `createService()`, `updateService()`, `deleteService()` untuk manajemen layanan.
  - `getAdmins()`, `getAdmin()`, `createAdmin()`, `updateAdmin()`, `deleteAdmin()` untuk manajemen akun admin.
  - `getCms()` dan `updateCms()` untuk pengaturan konten situs.

### Middleware
- `app/Http/Middleware/AdminAuth.php`
  - Memeriksa `session('admin_id')`.
  - Jika kosong, menolak request dengan `401 Unauthorized`.
- `app/Http/Middleware/SuperAdmin.php`
  - Memeriksa `session('admin_role')`.
  - Jika bukan `superadmin`, menolak request dengan `403 Forbidden`.

### Model
- `app/Models/Admin.php`
  - Model untuk tabel `admins`.
  - Menyimpan username, password terhash, nama admin, dan role.
- `app/Models/Layanan.php`
  - Model untuk tabel `layanan`.
  - Menyimpan nama layanan, deskripsi, harga, dan status aktif.
- `app/Models/Pesanan.php`
  - Model untuk tabel `pesanan`.
  - Primary key adalah `kode_tiket`.
  - Mengenerate kode tiket otomatis saat create.
  - Relasi ke layanan, admin, admin yang update, dan rating.
- `app/Models/Rating.php`
  - Model untuk tabel `rating`.
  - Menyimpan nilai rating dan ulasan.
- `app/Models/SiteSettings.php`
  - Model untuk tabel `site_settings`.
  - Menyimpan konfigurasi situs seperti teks hero dan path QRIS.

## Alur Permintaan Utama

### 1. Pengunjung melihat halaman publik
- Frontend memanggil `/api/home` dan `/api/services`.
- Backend mengembalikan data konten dan daftar layanan.

### 2. Proses pemesanan
1. Frontend panggil `/api/order/step-1` untuk daftar layanan.
2. Simpan data user step 1 ke session via `/api/order/step-1` (POST).
3. Simpan link foto mentah ke session via `/api/order/step-2` (POST).
4. Ambil ringkasan order dan QRIS via `/api/order/step-3`.
5. Submit order akhir via `/api/order/step-3` (POST), backend menyimpan record `Pesanan`.

### 3. Cek status pesanan
- Customer mengirim `ticket_id` ke `/api/order/status`.
- Backend mencari pesanan dan mengembalikan detail status.

### 4. Rating pesanan
- Setelah status `selesai`, customer dapat submit rating ke `/api/order/rate`.

### 5. Panel Admin
1. Login lewat `/api/admin/login`.
2. Cek status login lewat `/api/admin/me`.
3. Gunakan route admin dengan middleware `admin.auth`.
4. Untuk operasi manajemen layanan/admin/CMS, role harus `superadmin`.

## Catatan
- Dokumen ini dibuat sebagai ringkasan alur kerja dan dokumentasi fungsi.
- Beberapa file sekarang memiliki komentar inline yang menjelaskan fungsi utama dan logika.
