<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DStudio</title>
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
<body class="bg-dstudio-dark min-h-screen flex items-center justify-center">
    <div class="bg-dstudio-cream rounded-lg shadow-xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-dstudio-dark">DStudio <span class="text-dstudio-gold">Admin</span></h1>
            <p class="text-gray-600 mt-2">Silakan login untuk melanjutkan</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="/admin18908/login" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Username</label>
                <input type="text" name="username" 
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
                    placeholder="Masukkan username"
                    required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" name="password" 
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
                    placeholder="Masukkan password"
                    required>
            </div>

            <button type="submit" class="w-full bg-dstudio-gold text-dstudio-dark py-3 rounded-lg font-bold hover:bg-yellow-500 transition">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </button>
        </form>
    </div>
</body>
</html>
