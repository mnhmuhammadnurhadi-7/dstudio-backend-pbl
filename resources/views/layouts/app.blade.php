<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DStudio Photography - @yield('title', 'Edit Foto Profesional')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dstudio: {
                            dark: '#1C1C1C',
                            gold: '#C8961F',
                            cream: '#FFF3DC',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-dstudio-cream text-dstudio-dark min-h-screen">
    <nav class="bg-dstudio-dark text-white py-4 px-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-dstudio-gold">DStudio</a>
            <div class="hidden md:flex space-x-6">
                <a href="/" class="hover:text-dstudio-gold transition">Beranda</a>
                <a href="/layanan" class="hover:text-dstudio-gold transition">Layanan</a>
                <a href="/cek-status" class="hover:text-dstudio-gold transition">Cek Status</a>
            </div>
            <a href="/pesan/step-1" class="bg-dstudio-gold text-dstudio-dark px-4 py-2 rounded font-semibold hover:bg-yellow-500 transition">
                Pesan Sekarang
            </a>
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-4 px-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="bg-dstudio-dark text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-dstudio-gold font-bold text-xl mb-2">DStudio Photography</p>
            <p class="text-gray-400">Edit Foto Profesional, Cepat & Terjangkau</p>
            <p class="text-gray-500 text-sm mt-4">&copy; {{ date('Y') }} DStudio Photography. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
