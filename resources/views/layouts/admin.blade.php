<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DStudio</title>
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
<body class="bg-dstudio-cream">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-dstudio-dark text-white flex flex-col">
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-xl font-bold text-dstudio-gold">DStudio Admin</h1>
                <p class="text-sm text-gray-400 mt-1">{{ session('admin_name') }}</p>
            </div>
            
            <nav class="flex-1 py-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-l-4 border-dstudio-gold' : '' }}">
                    <i class="fas fa-table-list w-6"></i>
                    <span>Tabel Antrean</span>
                </a>
                <a href="{{ route('admin.completed') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 transition {{ request()->routeIs('admin.completed') ? 'bg-gray-800 border-l-4 border-dstudio-gold' : '' }}">
                    <i class="fas fa-check-circle w-6"></i>
                    <span>Pesanan Selesai</span>
                </a>

                @if(session('admin_role') === 'superadmin')
                    <div class="mt-6 px-6 text-xs text-gray-500 uppercase font-bold">Super Admin</div>
                    <a href="{{ route('admin.services.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 transition {{ request()->routeIs('admin.services.*') ? 'bg-gray-800 border-l-4 border-dstudio-gold' : '' }}">
                        <i class="fas fa-briefcase w-6"></i>
                        <span>Layanan</span>
                    </a>
                    <a href="{{ route('admin.admins.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 transition {{ request()->routeIs('admin.admins.*') ? 'bg-gray-800 border-l-4 border-dstudio-gold' : '' }}">
                        <i class="fas fa-users w-6"></i>
                        <span>Kelola Admin</span>
                    </a>
                    <a href="{{ route('admin.cms.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 transition {{ request()->routeIs('admin.cms.*') ? 'bg-gray-800 border-l-4 border-dstudio-gold' : '' }}">
                        <i class="fas fa-cog w-6"></i>
                        <span>CMS</span>
                    </a>
                @endif
            </nav>

            <div class="p-4 border-t border-gray-700">
                <form action="/admin18908/logout" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-red-400 hover:bg-gray-800 rounded transition">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <div class="p-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
