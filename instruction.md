# REACT UI AGENT — DStudio Photography
# Stack: Create React App (CRA) + JavaScript (.js) + TailwindCSS
# Prinsip: Standalone React app, UI-only, data dari API Laravel
# Versi: 5.0

---

## ⚠️ ATURAN UTAMA AGENT INI

1. **React adalah project mandiri** — tidak ada akses ke project Laravel, tidak ada folder Blade
2. **Dokumen ini adalah satu-satunya referensi** — semua halaman, komponen, warna, layout, dan data shape sudah didefinisikan di sini
3. **React hanya menangani UI + render data** — tidak ada logic bisnis, tidak ada manipulasi data
4. **Semua data dari API** — ikuti API Contract yang tercantum di setiap halaman, tidak ada data hardcode
5. **Ikuti spesifikasi di dokumen ini** — jika tidak tercantum di sini, jangan dibuat

---

## ⚙️ SETUP WAJIB

### Install Tailwind di CRA
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init
```

### tailwind.config.js
```js
module.exports = {
  content: ['./src/**/*.{js,jsx}'],
  theme: {
    extend: {
      colors: {
        'dstudio-dark':  '#1C1C1C',
        'dstudio-gold':  '#C8961F',
        'dstudio-cream': '#FFF3DC',
      },
    },
  },
  plugins: [],
};
```

### src/index.css (tambahkan di baris paling atas)
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

### Install dependencies
```bash
npm install axios react-router-dom @tanstack/react-query react-hook-form
```

### Axios base config (src/services/api.js)
```js
import axios from 'axios';
axios.defaults.baseURL = process.env.REACT_APP_API_URL ?? 'http://localhost:8000';
axios.defaults.withCredentials = true;
axios.defaults.headers.common['Accept'] = 'application/json';

export default axios;
```

### CSRF (jalankan sekali saat app load di App.js)
```js
axios.get('/sanctum/csrf-cookie');
```

---

## 🗂️ FOLDER STRUCTURE

```
src/
  components/
    common/         ← atom: Button.js, Badge.js, Input.js, Alert.js, dll
    layout/         ← PublicLayout.js, AdminLayout.js, Navbar.js, Sidebar.js
    home/           ← HeroSection.js, AboutSection.js, FeaturesSection.js
    services/       ← ServiceCard.js
    order/          ← OrderStepper.js, InfoBox.js, QrImageCard.js, dll
    admin/          ← FilterTabs.js, OrdersTable.js, OrderRow.js, dll
  context/
    AuthContext.js
    OrderContext.js
  hooks/
    useAuth.js
    useOrder.js
  services/
    api.js
    homeApi.js
    servicesApi.js
    orderApi.js
    adminApi.js
  routes/
    AppRoutes.js    ← semua route publik + admin
  App.js            ← entry point, setup QueryClient, Router
  index.js          ← ReactDOM.render
  index.css         ← Tailwind directives
```

---

## 🔀 ROUTING

```jsx
// src/routes/AppRoutes.js
import { Routes, Route } from 'react-router-dom';

// Public
<Route path="/"                        element={<HomePage />} />
<Route path="/layanan"                 element={<ServicesPage />} />
<Route path="/pesan/step-1"            element={<Step1Page />} />
<Route path="/pesan/step-2"            element={<Step2Page />} />
<Route path="/pesan/step-3"            element={<Step3Page />} />
<Route path="/pesan/selesai"           element={<SuccessPage />} />
<Route path="/cek-status"             element={<StatusFormPage />} />
<Route path="/cek-status/result/:id"  element={<StatusResultPage />} />

// Admin (dibungkus AdminGuard)
<Route path="/admin/login"             element={<AdminLoginPage />} />
<Route path="/admin/dashboard"         element={<AdminDashboardPage />} />
<Route path="/admin/completed"         element={<AdminCompletedPage />} />
<Route path="/admin/services"          element={<AdminServicesPage />} />
<Route path="/admin/services/create"   element={<AdminServiceFormPage />} />
<Route path="/admin/services/:id/edit" element={<AdminServiceFormPage />} />
<Route path="/admin/admins"            element={<AdminUsersPage />} />
<Route path="/admin/admins/create"     element={<AdminCreateFormPage />} />
<Route path="/admin/cms"              element={<AdminCmsPage />} />
```

**AdminGuard:** redirect ke `/admin/login` jika `!authState.isAuthenticated`

---

## 🌐 STATE GLOBAL

### AuthContext (src/context/AuthContext.js)
```js
// Shape state:
// {
//   isAuthenticated: boolean,
//   adminId: number | null,
//   adminName: string | null,
//   adminRole: 'admin' | 'superadmin' | null,
// }

// Methods:
// login(adminData)  → simpan dari response /api/admin/login
// logout()          → clear state + call /api/admin/logout
```

### OrderContext (src/context/OrderContext.js)
```js
// Shape state:
// {
//   name: '',
//   phone: '',
//   service_id: null,
//   notes: '',
//   photo_link: '',
//   ticket_id: null,
// }

// Methods:
// setStep1({ name, phone, service_id, notes })
// setStep2({ photo_link })
// setTicket(ticket_id)
// reset()
```

---

## 🎨 DESIGN REFERENCE

| Elemen                   | Tailwind class                                        |
|--------------------------|-------------------------------------------------------|
| Bg gelap utama           | `bg-dstudio-dark`                                     |
| Bg krem (card/admin)     | `bg-dstudio-cream`                                    |
| Aksen / CTA              | `bg-dstudio-gold`                                     |
| Teks gold                | `text-dstudio-gold`                                   |
| Hover gold               | `hover:bg-yellow-500`                                 |
| Focus ring               | `focus:ring-dstudio-gold focus:border-dstudio-gold`   |
| Ticket ID                | `font-mono font-bold text-dstudio-gold`               |
| Input standar            | `w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold` |
| Error input              | `border-red-500`                                      |
| Error text               | `text-red-500 text-sm mt-1`                           |
| Tombol primer            | `bg-dstudio-gold text-dstudio-dark hover:bg-yellow-500 transition` |
| Tombol sekunder          | `bg-gray-200 text-gray-800 hover:bg-gray-300 transition` |
| Card                     | `bg-white rounded-lg shadow-md`                       |
| Table header             | `bg-dstudio-dark text-white`                          |
| Table row hover          | `hover:bg-gray-50`                                    |
| Sidebar aktif            | `bg-gray-800 border-l-4 border-dstudio-gold`          |
| Badge aktif admin        | `bg-dstudio-gold text-dstudio-dark`                   |

### Status Badge
```
terkirim    → bg-yellow-100 text-yellow-800
diproses    → bg-purple-100 text-purple-800
selesai     → bg-green-100 text-green-800
revisi      → bg-orange-100 text-orange-800
dibatalkan  → bg-red-100 text-red-800
```

### Progress Stepper
```
selesai (step lalu) → bg-green-500 text-white
aktif (step ini)    → bg-dstudio-gold text-dstudio-dark
belum               → bg-gray-200 text-gray-400
connector aktif     → bg-dstudio-gold
connector belum     → bg-gray-300
```

---

## 📄 HALAMAN — PUBLIK

---

### `/` — Beranda

**Data:** `GET /api/home` → `{ heroTitle, heroSubtitle, aboutText }`

**Layout:**
```
PublicLayout
  ├── HeroSection       bg-dstudio-dark, text center
  ├── AboutSection      bg-white, 2-col grid md
  ├── FeaturesSection   bg-white, 3 card
  └── CtaSection        bg-dstudio-gold, text hitam
```

**HeroSection:**
- H1: `text-4xl md:text-6xl font-bold text-white` → isi dari `heroTitle`
- Subtitle: `text-xl md:text-2xl text-gray-300` → `heroSubtitle`
- CTA: tombol `Pesan Sekarang` → navigate `/pesan/step-1`
- Link: `Lihat Layanan Kami` → navigate `/layanan`
- Padding: `py-20 px-6`

**AboutSection:**
- Grid: `grid md:grid-cols-2 gap-12 items-center py-16 px-6`
- Kiri: `aboutText`
- Kanan: placeholder image `bg-gray-200 rounded-lg h-64`

**FeaturesSection:**
- Grid: `grid md:grid-cols-3 gap-8 py-16 px-6`
- 3 kartu statis: ikon + judul + deskripsi singkat
- Card: `bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition`

**CtaSection:**
- `bg-dstudio-gold py-16 text-center`
- Tombol hitam `bg-dstudio-dark text-white hover:bg-gray-800`

---

### `/layanan` — Layanan

**Data:** `GET /api/services` → array `services[]`

**Fields per item:**
```ts
{ id_layanan, nama_layanan, deskripsi, harga, is_active }
```

**Layout:**
```
PublicLayout
  ├── Hero section hitam (judul "Layanan Kami")
  └── Grid layanan: md:grid-cols-2 lg:grid-cols-3 gap-8 p-8
```

**ServiceCard:**
```
bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition
  ├── Gambar placeholder: bg-gray-200 h-48
  ├── Body p-6:
  │     nama: text-xl font-bold
  │     deskripsi: text-gray-600 text-sm mb-4
  │     harga: text-2xl font-bold text-dstudio-gold
  └── Tombol "Pesan": bg-dstudio-dark text-white px-4 py-2 rounded hover:bg-gray-800
       → navigate /pesan/step-1
```

**Empty state:** `Tidak ada layanan tersedia saat ini.` (teks abu, center)

---

### `/pesan/step-1` — Form Data Diri

**Data:**
- `GET /api/order/step-1` → `{ services[], formData }`
- `POST /api/order/step-1` → `{ success, nextStep }` atau `{ errors }`

**Layout:**
```
PublicLayout, bg-dstudio-dark min-h-screen
  ├── OrderStepper (step 1 aktif)
  └── Form card: bg-white rounded-lg shadow-md p-8 max-w-2xl mx-auto
```

**OrderStepper (3 step):**
```
[1]───[2]───[3]
Data  Foto  Bayar

Lingkaran: w-8 h-8 rounded-full font-bold text-center
  aktif:   bg-dstudio-gold text-dstudio-dark
  lainnya: bg-gray-600 text-gray-400
Connector: w-12 h-0.5 bg-gray-600
```

**Form fields:**
```
Nama Lengkap   → input text, required
No WhatsApp    → input text, required
Pilih Layanan  → <select>, required
               → option: "{nama_layanan} - Rp {harga}"
Catatan        → textarea, optional, placeholder "Catatan tambahan..."
```

**Submit:** `POST /api/order/step-1` → simpan ke `OrderContext` → navigate `/pesan/step-2`

**Validation error:** border merah + teks merah di bawah field

---

### `/pesan/step-2` — Link Foto

**Guard:** redirect `/pesan/step-1` jika `OrderContext.service_id` null

**Data:**
- `POST /api/order/step-2` → `{ success }` atau `{ errors }`

**Layout:**
```
PublicLayout, bg-dstudio-dark
  ├── OrderStepper (step 2 aktif)
  └── Card p-8 max-w-2xl mx-auto
```

**InfoBox:**
```
bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6
  ikon: fas fa-info-circle (atau Heroicon)
  teks panduan cara share Google Drive
```

**Form:**
```
Label: "Link Google Drive Foto Anda"
Input: type="url", placeholder "https://drive.google.com/..."
       prefill dari OrderContext.photo_link jika ada
```

**Tombol row:**
```
[Kembali]   → bg-gray-200, navigate /pesan/step-1
[Lanjutkan] → bg-dstudio-gold, submit form
```

---

### `/pesan/step-3` — QRIS Pembayaran

**Guard:** redirect `/pesan/step-1` jika `OrderContext.photo_link` kosong

**Data:**
- `GET /api/order/step-3?service_id={id}` → `{ service, qrisImage, priceFormatted }`
- `POST /api/order/step-3` (body: full order data dari context) → `{ success, ticket_id }`

**Layout:**
```
PublicLayout, bg-dstudio-dark
  ├── OrderStepper (step 3 aktif)
  └── Card p-8 max-w-2xl mx-auto text-center
```

**Isi card:**
```
QRIS box: bg-gray-100 rounded-lg p-4 inline-block
  img: w-64 h-64 object-contain
  fallback jika kosong: box abu + teks "QRIS Image"

Harga: text-2xl font-bold text-dstudio-gold

InstructionList: bg-yellow-50 rounded-lg p-4 text-sm text-left
  list instruksi bayar (numbered)

Tombol row:
  [Kembali]           → navigate /pesan/step-2
  [Konfirmasi Pesanan] → POST submit → simpan ticket_id → navigate /pesan/selesai
```

---

### `/pesan/selesai` — Tiket Berhasil

**Guard:** redirect `/` jika `OrderContext.ticket_id` null

**Data:** `GET /api/order/{ticket_id}` → objek pesanan lengkap

**Fields yang ditampilkan:**
```ts
{ kode_tiket, nama_pelanggan, no_wa, layanan.nama_layanan,
  total_bayar, catatan, created_at, whatsappNumber }
```

**Layout:**
```
PublicLayout, bg-dstudio-dark min-h-screen
  └── Center content max-w-2xl mx-auto
```

**Struktur:**
```
Banner hijau: bg-green-500 rounded-full w-20 h-20 → ikon centang
H1 "Pesanan Berhasil!" text-white

Summary card: bg-white rounded-lg shadow-md p-8 text-center
  ├── Kode tiket: text-5xl font-bold text-dstudio-gold font-mono
  │               "Screenshot kode ini!" text-gray-500 text-sm
  │
  ├── Detail grid 2-col:
  │     label: text-gray-500 text-sm
  │     value: font-semibold
  │     rows: Nama, No HP, Layanan, Total Bayar, Catatan, Waktu
  │
  └── Buttons (stacked, block w-full py-3 rounded-lg, space-y-3 mt-8):
        [Konfirmasi via WhatsApp]  → bg-green-500 text-white
                                    href: wa.me/{whatsappNumber}?text={encoded}
                                    hidden jika whatsappNumber kosong
        [Cek Status Tiket]        → bg-dstudio-dark text-white
                                    navigate /cek-status
        [Pesan Lagi]              → bg-dstudio-gold text-dstudio-dark
                                    navigate /pesan/step-1
```

**WA message template:**
```
Halo DStudio! Saya sudah melakukan pemesanan.
Kode Tiket: #{kode_tiket}
Nama: {nama_pelanggan}
Layanan: {layanan.nama_layanan}
Total: Rp{total_bayar}
Mohon dikonfirmasi. Terima kasih!
```

---

### `/cek-status` — Form Cek Status

**Data:** `POST /api/order/status` → `{ success, order }` atau `{ error }`

**Layout:**
```
PublicLayout
  ├── Hero hitam: judul "Cek Status Pesanan"
  └── Card putih max-w-md mx-auto p-8
```

**Form:**
```
Input dengan prefix "#":
  relative wrapper
  span "#" absolute left-4 top-3 text-gray-400
  input: pl-8 text-center text-lg font-bold uppercase

Button [Cek Status]: bg-dstudio-gold w-full

Error inline: text-red-500 text-sm

Link bawah: "Belum pesan? Pesan sekarang" → /pesan/step-1
```

**On success:** navigate `/cek-status/result/{ticket_id}`

---

### `/cek-status/result/:ticket_id` — Hasil Status

**Data:** `GET /api/order/{ticket_id}` → pesanan + status

**Fields:**
```ts
{
  kode_tiket, nama_pelanggan, no_wa, layanan,
  total_bayar, catatan, created_at, selesai_at,
  status_pesanan,      // terkirim | diproses | selesai | revisi | dibatalkan
  catatan_revisi,
  keterangan_status,
  link_foto_hasil,
  rating: { nilai_rating } | null,
  whatsappNumber,
}
```

**Mapping status → progress step (3 langkah):**
```
terkirim   → step 1
diproses   → step 2
selesai    → step 3 (semua hijau)
revisi     → step 2 (+ RevisionAlert)
dibatalkan → step 1
```

**Layout:**
```
PublicLayout
  ├── Hero hitam: kode tiket besar + StatusBadge
  ├── Card: OrderStatusStepper (3 lingkaran)
  ├── Card: RevisionAlert (HANYA jika status = revisi)
  │         bg-red-100 border-2 border-red-500
  ├── Card: Detail pesanan grid 2-col (md:grid-cols-2)
  ├── Card: StatusMessageCard
  │         teks pesan + tombol WA jika ada whatsappNumber
  │         jika selesai + link_foto_hasil → tombol "Lihat Hasil Foto"
  ├── Card: RatingForm (HANYA jika status=selesai DAN rating null)
  │         5 bintang klik, kirim ke POST /api/order/rate
  ├── Teks rating (HANYA jika rating sudah ada): bintang statis
  └── Link: "← Cek Status Lainnya" → /cek-status
```

**Spinner:** jika `diproses`, tampilkan ikon dengan `animate-spin`

---

## 🔒 HALAMAN — ADMIN

---

### `/admin/login` — Login Admin

**Data:** `POST /api/admin/login` → `{ success, admin }` atau `{ error }`

**Layout:**
```
Full screen bg-dstudio-dark flex items-center justify-center
  └── Card: bg-dstudio-cream rounded-lg shadow-xl p-8 max-w-md w-full
```

**Isi card:**
```
Logo: "DStudio Admin" (judul besar)
Form:
  Username → input text
  Password → input password
  [Login]  → bg-dstudio-gold text-dstudio-dark w-full

Error banner: AlertBanner merah di atas form (jika error dari API)
```

**On success:** simpan ke `AuthContext` → navigate `/admin/dashboard`

---

### AdminLayout (shared semua halaman admin)

```
flex min-h-screen
  ├── AdminSidebar (w-64 bg-dstudio-dark)
  │     Logo area: padding + border-b
  │     Nav items:
  │       Tabel Antrean     → /admin/dashboard
  │       Pesanan Selesai   → /admin/completed
  │       [superadmin only]:
  │         Kelola Layanan  → /admin/services
  │         Kelola Admin    → /admin/admins
  │         CMS             → /admin/cms
  │     Active: bg-gray-800 border-l-4 border-dstudio-gold text-white
  │     Inactive: text-gray-400 hover:text-white hover:bg-gray-800
  │     Logout button bawah
  │
  └── Main area flex-1
        Header: bg-white border-b px-8 py-4
          "Selamat datang, {adminName}"
        Content: p-8
```

**Superadmin check:** `authState.adminRole === 'superadmin'`

---

### `/admin/dashboard` — Tabel Antrean

**Data:**
```
GET /api/admin/orders?status={status}&search={search}
→ { orders[], counts: { all, terkirim, diproses, selesai } }

PATCH /api/admin/orders/{kode_tiket}/status
Body: { status, catatan_revisi? }

PATCH /api/admin/orders/{kode_tiket}/result
Body: { result_link }
```

**FilterTabs:**
```
[Semua ({all})] [Terkirim ({terkirim})] [Diproses ({diproses})] [Selesai ({selesai})]

Tab aktif: border-b-2 border-dstudio-gold text-dstudio-gold font-semibold
Tab nonaktif: text-gray-500 hover:text-dstudio-gold
```

**SearchBar:**
```
Input: "Cari nama / kode tiket..."
Tombol clear (×) jika ada teks
```

**Tabel kolom:** `Tiket ID | Nama | WA | Layanan | Total | Bayar | Status | Tgl Masuk | Aksi`

**Per baris:**
```
Tiket ID   → font-mono text-dstudio-gold font-bold
WA         → link hijau ke wa.me/{no_wa}
Total      → format Rp{total_bayar}
Bayar      → badge: (tidak ada payment_status di contract ini — skip jika tidak ada di API)
Status     → StatusBadge sesuai peta warna
Tgl Masuk  → format DD/MM/YYYY

Kolom Aksi (inline, bukan modal):
  1. Select status + tombol [Update] kecil bg-dstudio-dark
  2. Input result_link + tombol [Upload] kecil bg-dstudio-gold
     (tampil HANYA jika status = selesai)
  3. Textarea catatan_revisi + tombol [Revisi] kecil bg-red-500
     (tampil HANYA jika status = diproses)
```

**Table:** `bg-white rounded-lg shadow overflow-x-auto` (scroll horizontal mobile)

---

### `/admin/completed` — Pesanan Selesai

**Data:** `GET /api/admin/orders/completed?search={search}`

**Tabel kolom:** `Tiket ID | Nama | Layanan | Total | Status | Rating | Admin | Tgl Selesai | Hasil`

**Per baris:**
```
Rating   → bintang gold statis (nilai_rating) atau "-"
Admin    → adminUpdatedBy.nama_admin
Hasil    → link "Lihat" text-dstudio-gold (buka tab baru)
           "-" jika kosong
```

**SearchBar** sama seperti dashboard

---

### `/admin/services` — Kelola Layanan *(superadmin only)*

**Data:**
```
GET  /api/admin/services → services[]
DELETE /api/admin/services/{id}
```

**Header:** Judul + tombol [Tambah Layanan] → navigate `/admin/services/create`

**Tabel kolom:** `Nama | Harga | Deskripsi | Status | Aksi`

```
Deskripsi  → truncate 50 karakter, text-sm text-gray-600
Status     → badge: aktif=bg-green-100 text-green-800, nonaktif=bg-red-100 text-red-800
Aksi       → [Edit] biru → /admin/services/{id}/edit
             [Hapus] merah → confirm dialog → DELETE API
```

---

### `/admin/services/create` & `/admin/services/:id/edit` — Form Layanan

**Data:**
```
GET  /api/admin/services/{id}    (hanya untuk edit, prefill form)
POST /api/admin/services         (create)
PUT  /api/admin/services/{id}    (update)
```

**Form fields:**
```
Nama Layanan  → input text, required, max 100
Harga         → input number, required, min 0
Deskripsi     → textarea, optional
Aktif         → checkbox, default checked
              → class: text-dstudio-gold rounded
```

**Tombol:**
```
[Batal]  → bg-gray-200, navigate /admin/services
[Simpan] → bg-dstudio-gold
```

**Card:** `bg-white rounded-lg shadow p-8 max-w-2xl`

---

### `/admin/admins` — Kelola Admin *(superadmin only)*

**Data:**
```
GET    /api/admin/admins → admins[]
DELETE /api/admin/admins/{id}
```

**Header:** Judul + tombol [Tambah Admin] → navigate `/admin/admins/create`

**Tabel kolom:** `Nama | Username | Role | Dibuat | Aksi`

```
Role badge:
  superadmin → bg-purple-100 text-purple-800
  admin      → bg-blue-100 text-blue-800

Baris admin saat ini (id = authState.adminId):
  → bg-yellow-50 (highlight)
  → badge tambahan: bg-dstudio-gold text-dstudio-dark "Anda"

Kolom Aksi:
  [Hapus] merah → HANYA tampil jika bukan admin saat ini
```

---

### `/admin/admins/create` — Tambah Admin *(superadmin only)*

**Data:** `POST /api/admin/admins` → `{ name, username, password, role }`

**Form fields:**
```
Nama      → input text
Username  → input text (unique, validasi dari API)
Password  → input password
Role      → select: admin (default) | superadmin
```

**Tombol:** [Batal] + [Simpan] (pola sama seperti form layanan)

---

### `/admin/cms` — CMS *(superadmin only)*

**Data:**
```
GET  /api/admin/cms → { hero_title, hero_subtitle, about_text,
                        nomor_wa_bisnis, qris_image_path, instagram_url }
POST /api/admin/cms → body same keys
```

**Layout:** Card `bg-white rounded-lg shadow p-8 max-w-3xl`

**Form fields:**
```
hero_title       → input text,    label "Judul Hero"
hero_subtitle    → input text,    label "Subjudul Hero"
about_text       → textarea,      label "Teks Tentang Kami"
nomor_wa_bisnis  → input text,    label "Nomor WhatsApp Bisnis"
qris_image_path  → input text,    label "URL Gambar QRIS"
                   → <img> preview w-48 h-48 object-contain border rounded
                     tampil HANYA jika URL valid
instagram_url    → input text,    label "URL Instagram"
```

**Tombol:** [Simpan Perubahan] → bg-dstudio-gold, POST API

---

## 🧩 KOMPONEN REUSABLE

### StatusBadge (src/components/common/Badge.js)
```js
const statusMap = {
  terkirim:   'bg-yellow-100 text-yellow-800',
  diproses:   'bg-purple-100 text-purple-800',
  selesai:    'bg-green-100 text-green-800',
  revisi:     'bg-orange-100 text-orange-800',
  dibatalkan: 'bg-red-100 text-red-800',
};

export function StatusBadge({ status }) {
  const cls = statusMap[status] ?? 'bg-gray-100 text-gray-800';
  return (
    <span className={`inline-block px-2 py-1 rounded-full text-xs font-semibold uppercase ${cls}`}>
      {status}
    </span>
  );
}
```

### OrderStepper (src/components/order/OrderStepper.js)
```js
// Props: current (1 | 2 | 3)
// steps: ['Data Diri', 'Upload Foto', 'Bayar']
// Lingkaran w-8 h-8 rounded-full font-bold + connector w-12 h-0.5
// aktif:   bg-dstudio-gold text-dstudio-dark
// lainnya: bg-gray-600 text-gray-400
```

### AlertBanner (src/components/common/Alert.js)
```js
// Props: type ('success' | 'error'), message
// success: bg-green-100 border border-green-400 text-green-800
// error:   bg-red-100 border border-red-400 text-red-800
// Tampil di atas form, dismiss opsional
```

### RatingStars (src/components/common/RatingStars.js)
```js
// Props: onSubmit (function), disabled (boolean)
// 5 bintang, klik untuk pilih nilai
// Warna aktif: text-dstudio-gold
// Warna kosong: text-gray-300
// Disable setelah submit
```

### ButtonPrimary / ButtonSecondary (src/components/common/Button.js)
```js
// ButtonPrimary:   bg-dstudio-gold text-dstudio-dark hover:bg-yellow-500 transition px-6 py-2 rounded-lg
// ButtonSecondary: bg-gray-200 text-gray-800 hover:bg-gray-300 transition px-6 py-2 rounded-lg
// Variant full-width: tambahkan prop fullWidth → w-full block py-3
```

---

## ⚡ DATA FETCHING PATTERN

Gunakan **TanStack Query (React Query)** untuk semua fetch:

```js
// GET
const { data, isLoading, error } = useQuery(
  ['key', params],
  () => axios.get('/api/...').then(res => res.data)
);

// POST / PATCH / DELETE
const mutation = useMutation(
  (payload) => axios.post('/api/...', payload),
  {
    onSuccess: () => {
      queryClient.invalidateQueries(['key']);
      navigate('/...');
    },
    onError: (err) => {
      setErrors(err.response?.data?.errors ?? {});
    },
  }
);
```

**Loading state:** tampilkan skeleton atau spinner, bukan halaman kosong
**Error state:** tampilkan `AlertBanner`, bukan crash / blank page

---

## 📱 RESPONSIVE

| Breakpoint | Perilaku                                          |
|------------|---------------------------------------------------|
| < md       | Navbar: hanya logo + tombol CTA, link nav hidden  |
| < md       | Grid section stack 1 kolom                        |
| < md       | Tabel admin: `overflow-x-auto` scroll horizontal  |
| < md       | Form: full width tetap                            |
| < md       | Sidebar admin: hidden (toggle hamburger opsional) |

---

## 🚫 LARANGAN AGENT

### UI & Halaman
- ❌ Jangan buat halaman yang tidak terdaftar di bagian **ROUTING** dokumen ini
- ❌ Jangan gunakan `modal` / `dialog` / `drawer` — semua aksi pakai inline form atau navigasi halaman penuh
- ❌ Jangan tambah fitur baru (contoh: notifikasi push, dark mode toggle, pagination) yang tidak disebut di dokumen ini
- ❌ Jangan ubah urutan tombol dari yang sudah didefinisikan per halaman

### Styling
- ❌ Jangan gunakan warna Tailwind default untuk elemen branding — pakai `dstudio-dark`, `dstudio-gold`, `dstudio-cream`
- ❌ Jangan tambah animasi di luar: `hover:`, `transition`, `animate-spin` (hanya pada ikon loading diproses)
- ❌ Jangan ubah nilai spacing, padding, atau ukuran font dari yang sudah tercantum di setiap halaman

### Data
- ❌ Jangan hardcode data apapun (nama, harga, konten) — semua dari API
- ❌ Jangan manipulasi atau transform data di luar format tampilan (format Rupiah, tanggal, encode URL WA diperbolehkan)
- ❌ Jangan simpan data sensitif (password, token) di `localStorage` — gunakan cookie via `withCredentials`

### Komponen
- ❌ Jangan buat komponen yang tidak terdaftar di bagian **KOMPONEN REUSABLE** dan **COMPONENT LIST** per halaman
- ❌ Jangan import library UI eksternal (MUI, Ant Design, Chakra, dll) — hanya Tailwind CSS
- ❌ Jangan gunakan TypeScript (`.ts` / `.tsx`) — semua file harus `.js` / `.jsx`
- ❌ Jangan gunakan PropTypes wajib — boleh dilewati untuk kesederhanaan

---

## ✅ YANG BOLEH DILAKUKAN AGENT

- ✅ Membuat loading skeleton / spinner saat data sedang difetch
- ✅ Menampilkan `AlertBanner` saat terjadi error API
- ✅ Format tampilan: Rupiah (`Rp15.000`), tanggal (`DD/MM/YYYY`), encode WhatsApp message
- ✅ Guard redirect: jika user belum login → `/admin/login`, jika step belum lengkap → step sebelumnya
- ✅ Invalidate query setelah mutation berhasil
- ✅ Menggunakan `animate-spin` pada ikon spinner untuk status `diproses`

---

*Dokumen ini adalah satu-satunya referensi untuk React UI agent DStudio.*
*Project React berdiri sendiri — tidak ada akses ke project Laravel.*
*Backend tetap Laravel, React hanya render dan kirim/terima data lewat API.*
