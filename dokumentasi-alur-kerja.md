# Dokumentasi Alur Kerja Frontend DStudio Photography

## Pendahuluan

Halo teman-teman mahasiswa semester 1! Dokumentasi ini akan menjelaskan cara kerja frontend aplikasi DStudio Photography secara keseluruhan. Kita akan mulai dari API (bagaimana frontend berkomunikasi dengan server) hingga penampilan di browser. Saya akan jelaskan setiap baris kode dari file-file utama agar kalian mudah memahami.

Frontend ini dibuat menggunakan React.js, sebuah library JavaScript untuk membuat antarmuka pengguna yang interaktif.

## Struktur Proyek

Proyek ini memiliki struktur folder sebagai berikut:
- `src/`: Kode sumber utama
  - `components/`: Komponen-komponen UI
  - `pages/`: Halaman-halaman aplikasi
  - `context/`: State management
  - `routes/`: Konfigurasi routing
  - `services/`: Fungsi untuk berkomunikasi dengan API

## Alur Kerja Frontend Secara Keseluruhan

1. **Entry Point**: Aplikasi dimulai dari `index.js`
2. **App Component**: `App.js` mengatur provider dan routing
3. **Routing**: `AppRoutes.js` menentukan halaman mana yang ditampilkan
4. **API Calls**: Melalui `services/api.js` dan file API lainnya
5. **State Management**: Menggunakan Context API (`AuthContext.js`, `OrderContext.js`)
6. **Rendering**: Komponen menampilkan data ke UI

Mari kita jelajahi setiap file satu per satu.

## 1. index.js - Titik Masuk Aplikasi

```javascript
import React from 'react';  // Mengimpor React untuk membuat komponen
import ReactDOM from 'react-dom/client';  // Mengimpor ReactDOM untuk merender ke DOM
import './index.css';  // Mengimpor CSS untuk styling
import App from './App';  // Mengimpor komponen utama App
import reportWebVitals from './reportWebVitals';  // Mengimpor fungsi untuk melaporkan performa

const root = ReactDOM.createRoot(document.getElementById('root'));  // Membuat root React di elemen dengan id 'root'
root.render(  // Merender aplikasi ke DOM
  <React.StrictMode>  // Mode strict untuk membantu mendeteksi error
    <App />  // Komponen utama aplikasi
  </React.StrictMode>
);

// Jika ingin mengukur performa aplikasi, panggil fungsi ini
// (contoh: reportWebVitals(console.log))
// atau kirim ke endpoint analytics. Pelajari lebih lanjut: https://bit.ly/CRA-vitals
reportWebVitals();  // Melaporkan metrik performa aplikasi
```

**Penjelasan**: File ini adalah pintu masuk aplikasi. Ia mengimpor React, membuat root di elemen HTML dengan id 'root', dan merender komponen App.

## 2. App.js - Komponen Utama

```javascript
import { BrowserRouter } from 'react-router-dom';  // Router untuk navigasi halaman
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';  // Library untuk mengelola query API
import { AuthProvider } from './context/AuthContext';  // Provider untuk autentikasi
import { OrderProvider } from './context/OrderContext';  // Provider untuk pesanan
import { AppRoutes } from './routes/AppRoutes';  // Komponen routing
import api from './services/api';  // Instance axios untuk API calls

// Inisialisasi cookie CSRF saat aplikasi dimuat
api.get('/api/sanctum/csrf-cookie').catch(() => {  // Mengambil cookie CSRF dari server
  // Diam-diam gagal jika backend belum siap
});

const queryClient = new QueryClient({  // Membuat instance QueryClient
  defaultOptions: {
    queries: {
      refetchOnWindowFocus: false,  // Tidak refetch saat window mendapat fokus
      retry: 1,  // Coba ulang 1 kali jika gagal
    },
  },
});

function App() {  // Komponen utama aplikasi
  return (
    <QueryClientProvider client={queryClient}>  // Provider untuk query management
      <BrowserRouter>  // Provider untuk routing
        <AuthProvider>  // Provider untuk autentikasi
          <OrderProvider>  // Provider untuk pesanan
            <AppRoutes />  // Komponen routing yang menentukan halaman
          </OrderProvider>
        </AuthProvider>
      </BrowserRouter>
    </QueryClientProvider>
  );
}

export default App;  // Mengekspor komponen App sebagai default
```

**Penjelasan**: App.js mengatur semua provider yang dibutuhkan aplikasi. Ia menggunakan React Query untuk mengelola API calls, BrowserRouter untuk routing, dan Context API untuk state management.

## 3. services/api.js - Konfigurasi API

```javascript
import axios from 'axios';  // Mengimpor axios untuk HTTP requests

axios.defaults.baseURL = process.env.REACT_APP_API_URL ?? 'http://localhost:8000';  // Set base URL API, default localhost:8000
axios.defaults.withCredentials = true;  // Kirim cookies dengan request
axios.defaults.headers.common['Accept'] = 'application/json';  // Set header Accept ke JSON

export default axios;  // Mengekspor instance axios yang sudah dikonfigurasi
```

**Penjelasan**: File ini mengkonfigurasi axios (library untuk HTTP requests) dengan base URL, credentials, dan headers default.

## 4. routes/AppRoutes.js - Konfigurasi Routing

```javascript
import { Routes, Route, Navigate } from 'react-router-dom';  // Komponen routing dari react-router-dom
import { useAuth } from '../context/AuthContext';  // Hook untuk autentikasi

// Import halaman-halaman publik
import { HomePage } from '../pages/HomePage';
import { ServicesPage } from '../pages/ServicesPage';
// ... (import lainnya)

function AdminGuard({ children, requireSuperadmin = false }) {  // Komponen guard untuk halaman admin
  const { authState } = useAuth();  // Mengambil state autentikasi

  if (!authState.isAuthenticated) {  // Jika belum login
    return <Navigate to="/admin/login" replace />;  // Redirect ke login
  }

  if (requireSuperadmin && authState.adminRole !== 'superadmin') {  // Jika butuh superadmin tapi bukan
    return <Navigate to="/admin/dashboard" replace />;  // Redirect ke dashboard
  }

  return children;  // Jika lolos guard, tampilkan children
}

export function AppRoutes() {  // Komponen routing utama
  return (
    <Routes>  // Container untuk semua route
      {/* Public Routes */}
      <Route path="/" element={<HomePage />} />  // Route untuk halaman utama
      <Route path="/layanan" element={<ServicesPage />} />  // Route untuk halaman layanan
      // ... (route lainnya)
      
      {/* Admin Routes */}
      <Route path="/admin/login" element={<AdminLoginPage />} />  // Route login admin
      <Route
        path="/admin/dashboard"
        element={
          <AdminGuard>  // Guard untuk memastikan login
            <AdminDashboardPage />
          </AdminGuard>
        }
      />
      // ... (route admin lainnya)
      
      {/* Catch all */}
      <Route path="*" element={<Navigate to="/" replace />} />  // Redirect route tidak ditemukan ke home
    </Routes>
  );
}
```

**Penjelasan**: File ini mendefinisikan semua rute aplikasi. Ia menggunakan Routes dan Route dari react-router-dom. AdminGuard memastikan hanya admin yang login yang bisa akses halaman admin.

## 5. context/AuthContext.js - State Management Autentikasi

```javascript
import { createContext, useContext, useState, useCallback } from 'react';  // Hooks React
import { adminApi } from '../services/adminApi';  // API untuk admin

const AuthContext = createContext(null);  // Membuat context untuk autentikasi

export function AuthProvider({ children }) {  // Provider komponen
  const [authState, setAuthState] = useState({  // State untuk menyimpan data autentikasi
    isAuthenticated: false,  // Status login
    adminId: null,  // ID admin
    adminName: null,  // Nama admin
    adminRole: null,  // Role admin
  });

  const login = useCallback(async (credentials) => {  // Fungsi login
    const response = await adminApi.login(credentials);  // Panggil API login
    if (response.success && response.admin) {  // Jika berhasil
      setAuthState({  // Update state
        isAuthenticated: true,
        adminId: response.admin.id,
        adminName: response.admin.name,
        adminRole: response.admin.role,
      });
    }
    return response;  // Return response
  }, []);

  const logout = useCallback(async () => {  // Fungsi logout
    try {
      await adminApi.logout();  // Panggil API logout
    } finally {
      setAuthState({  // Reset state
        isAuthenticated: false,
        adminId: null,
        adminName: null,
        adminRole: null,
      });
    }
  }, []);

  return (
    <AuthContext.Provider value={{ authState, login, logout }}>  // Provide value ke children
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {  // Hook untuk menggunakan context
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider');  // Error jika tidak dalam provider
  }
  return context;
}
```

**Penjelasan**: Context ini mengelola state autentikasi aplikasi. Ia menyimpan data admin yang login dan menyediakan fungsi login/logout.

## 6. pages/HomePage.js - Contoh Halaman

```javascript
import { useQuery } from '@tanstack/react-query';  // Hook untuk query data
import { PublicLayout } from '../components/layout/PublicLayout';  // Layout untuk halaman publik
import { HeroSection } from '../components/home/HeroSection';  // Komponen hero
import { AboutSection } from '../components/home/AboutSection';  // Komponen about
import { FeaturesSection } from '../components/home/FeaturesSection';  // Komponen fitur
import { CtaSection } from '../components/home/CtaSection';  // Komponen call-to-action
import { AlertBanner } from '../components/common/Alert';  // Komponen alert
import { homeApi } from '../services/homeApi';  // API untuk data home

export function HomePage() {  // Komponen halaman home
  const { data, isLoading, error } = useQuery({  // Query untuk mengambil data home
    queryKey: ['home'],  // Key unik untuk cache
    queryFn: homeApi.getHome  // Fungsi untuk fetch data
  });

  return (
    <PublicLayout>  // Layout wrapper
      {error && (  // Jika ada error
        <div className="max-w-4xl mx-auto mt-4 px-4">
          <AlertBanner type="error" message="Gagal memuat data halaman" />  // Tampilkan error
        </div>
      )}
      
      <HeroSection  // Komponen hero
        title={isLoading ? 'DStudio Photography' : data?.heroTitle}  // Title, loading atau data
        subtitle={isLoading ? 'Jasa Edit Foto Profesional' : data?.heroSubtitle}  // Subtitle
      />
      
      <AboutSection text={isLoading ? 'Memuat...' : data?.aboutText} />  // Komponen about
      
      <FeaturesSection />  // Komponen fitur
      
      <CtaSection />  // Komponen CTA
    </PublicLayout>
  );
}
```

**Penjelasan**: HomePage menggunakan React Query untuk mengambil data dari API. Ia menampilkan loading state saat data belum siap, error jika gagal, dan data aktual saat tersedia.

## Alur Kerja Lengkap

1. **User membuka aplikasi**: `index.js` merender `App.js`
2. **App.js setup providers**: QueryClient, Router, Auth, Order contexts
3. **Routing menentukan halaman**: Berdasarkan URL, tampilkan komponen halaman
4. **Halaman fetch data**: Menggunakan React Query untuk memanggil API
5. **API calls**: Melalui axios yang dikonfigurasi di `api.js`
6. **Data ditampilkan**: Komponen menerima data sebagai props dan merender UI
7. **State management**: Context menyimpan state global seperti autentikasi
8. **Interaksi user**: Event handler mengupdate state dan trigger API calls

## Kesimpulan

Frontend ini menggunakan arsitektur modern React dengan:
- **React Router** untuk navigasi
- **React Query** untuk state server
- **Context API** untuk state global
- **Axios** untuk HTTP requests
- **Komponen-based architecture** untuk reusability

Semoga dokumentasi ini membantu kalian memahami cara kerja frontend aplikasi ini!

## Alur Koding Spesifik

### 1. Alur Koding Saat Ganti URL ke Admin

Ketika user mengetik URL seperti `/admin/dashboard` di browser, berikut alur yang terjadi:

1. **Browser mengirim request ke React Router**
2. **AppRoutes.js memproses route**:
   ```javascript
   <Route
     path="/admin/dashboard"
     element={
       <AdminGuard>  // Komponen guard dipanggil
         <AdminDashboardPage />
       </AdminGuard>
     }
   />
   ```

3. **AdminGuard mengecek autentikasi**:
   ```javascript
   function AdminGuard({ children, requireSuperadmin = false }) {
     const { authState } = useAuth();  // Ambil state dari context
     
     if (!authState.isAuthenticated) {  // Jika belum login
       return <Navigate to="/admin/login" replace />;  // Redirect ke login
     }
     
     if (requireSuperadmin && authState.adminRole !== 'superadmin') {  // Jika butuh superadmin
       return <Navigate to="/admin/dashboard" replace />;  // Redirect ke dashboard biasa
     }
     
     return children;  // Jika lolos, tampilkan halaman admin
   }
   ```

4. **Jika login, tampilkan AdminDashboardPage**
5. **Jika belum login, redirect ke `/admin/login`**

**Alur lengkap**:
- Browser → React Router → AppRoutes → AdminGuard → useAuth (cek context) → Redirect atau Tampilkan halaman

### 2. Alur Koding untuk Meminta API

Ketika komponen perlu data dari server, berikut alur yang terjadi:

1. **Komponen menggunakan React Query**:
   ```javascript
   const { data, isLoading, error } = useQuery({
     queryKey: ['services'],  // Key unik untuk cache
     queryFn: servicesApi.getServices  // Fungsi yang akan dipanggil
   });
   ```

2. **React Query memanggil fungsi API**:
   ```javascript
   // services/servicesApi.js
   export const servicesApi = {
     getServices: () => axios.get('/api/services').then(res => res.data),
   };
   ```

3. **Axios mengirim HTTP request**:
   ```javascript
   // services/api.js - konfigurasi axios
   axios.defaults.baseURL = process.env.REACT_APP_API_URL ?? 'http://localhost:8000';
   axios.defaults.withCredentials = true;  // Kirim cookies
   axios.defaults.headers.common['Accept'] = 'application/json';
   ```

4. **Server memproses request dan mengirim response**
5. **Axios menerima response dan return data**
6. **React Query menyimpan data di cache dan update komponen**

**Alur lengkap**:
- Komponen → useQuery → servicesApi → axios → HTTP Request → Server → Response → axios → servicesApi → useQuery → Update UI

### 3. Alur Koding untuk Penampilan Beranda, Layanan, Cek Status

#### a. Penampilan Beranda (HomePage)

```javascript
export function HomePage() {
  const { data, isLoading, error } = useQuery({  // 1. Fetch data dari API
    queryKey: ['home'],
    queryFn: homeApi.getHome
  });

  return (
    <PublicLayout>  // 2. Layout wrapper
      {error && (  // 3. Tampilkan error jika gagal
        <AlertBanner type="error" message="Gagal memuat data halaman" />
      )}
      
      <HeroSection  // 4. Komponen hero dengan data
        title={isLoading ? 'DStudio Photography' : data?.heroTitle}
        subtitle={isLoading ? 'Jasa Edit Foto Profesional' : data?.heroSubtitle}
      />
      
      <AboutSection text={isLoading ? 'Memuat...' : data?.aboutText} />
      <FeaturesSection />
      <CtaSection />
    </PublicLayout>
  );
}
```

**Alur**:
- Mount komponen → useQuery fetch data → Loading state → Data diterima → Re-render dengan data → Tampilkan komponen

#### b. Penampilan Layanan (ServicesPage)

```javascript
export function ServicesPage() {
  const { data, isLoading, error } = useQuery({  // 1. Fetch services dari API
    queryKey: ['services'], 
    queryFn: servicesApi.getServices
  });

  const services = Array.isArray(data) ? data : data?.services || [];  // 2. Normalisasi data

  return (
    <PublicLayout>
      {/* Hero section - static */}
      
      <section className="py-16 px-6 bg-gray-50">
        <div className="max-w-6xl mx-auto">
          {error && <AlertBanner type="error" message="Gagal memuat layanan" />}  // 3. Error handling
          
          {isLoading ? (  // 4. Loading state
            <div className="text-center py-12">Memuat layanan...</div>
          ) : services.length === 0 ? (  // 5. Empty state
            <p className="text-gray-500 text-center py-12">
              Tidak ada layanan tersedia saat ini.
            </p>
          ) : (  // 6. Data state - render services
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
              {services.map((service) => (
                <ServiceCard key={service.id_layanan} service={service} />
              ))}
            </div>
          )}
        </div>
      </section>
    </PublicLayout>
  );
}
```

**Alur**:
- Mount → Fetch services → Loading → Data array → Map ke ServiceCard → Render grid

#### c. Penampilan Cek Status (StatusFormPage)

```javascript
export function StatusFormPage() {
  const navigate = useNavigate();  // 1. Hook untuk navigasi
  const [ticketCode, setTicketCode] = useState('');  // 2. State untuk input
  const [error, setError] = useState(null);  // 3. State untuk error

  const mutation = useMutation({  // 4. Mutation untuk submit
    mutationFn: orderApi.checkStatus,
    onSuccess: (order) => {  // 5. Handler success
      if (order && order.kode_tiket) {
        navigate(`/cek-status/result/${order.kode_tiket}`);  // Redirect ke result
      } else {
        setError('Kode tiket tidak ditemukan');
      }
    },
    onError: (err) => {  // 6. Handler error
      setError(err.response?.data?.error || 'Terjadi kesalahan');
    },
  });

  const handleSubmit = (e) => {  // 7. Handler form submit
    e.preventDefault();
    setError(null);
    mutation.mutate({ ticket_id: ticketCode });  // 8. Panggil API
  };

  return (
    <PublicLayout>
      {/* Hero section - static */}
      
      <section className="py-16 px-6 bg-gray-50">
        <div className="max-w-md mx-auto">
          <div className="bg-white rounded-lg shadow-md p-8">
            {error && <AlertBanner type="error" message={error} />}  // 9. Tampilkan error
            
            <form onSubmit={handleSubmit} className="space-y-4">  // 10. Form dengan handler
              <input
                type="text"
                value={ticketCode}
                onChange={(e) => setTicketCode(e.target.value.toUpperCase())}  // 11. Update state
                // ... props lainnya
              />
              
              <ButtonPrimary type="submit" disabled={mutation.isLoading}>  // 12. Button submit
                {mutation.isLoading ? 'Mencari...' : 'Cek Status'}
              </ButtonPrimary>
            </form>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
```

**Alur**:
- Mount → User input kode → Submit form → useMutation call API → Success/Error → Navigate atau tampilkan error</content>
<parameter name="filePath">c:\MY FILES\KULIAH\Projek kuliah\frontend dstudio\front\dstudio-frontend\dokumentasi-alur-kerja.md