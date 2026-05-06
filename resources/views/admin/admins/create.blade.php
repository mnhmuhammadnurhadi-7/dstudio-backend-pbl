@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tambah Admin</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form action="{{ route('admin.admins.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nama</label>
            <input type="text" name="name" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('name') border-red-500 @enderror"
                value="{{ old('name') }}"
                required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Username</label>
            <input type="text" name="username" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('username') border-red-500 @enderror"
                value="{{ old('username') }}"
                required>
            @error('username')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Password</label>
            <input type="password" name="password" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('password') border-red-500 @enderror"
                required>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Role</label>
            <select name="role" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('role') border-red-500 @enderror">
                <option value="admin">Admin</option>
                <option value="superadmin">Super Admin</option>
            </select>
            @error('role')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <a href="{{ route('admin.admins.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-dstudio-gold text-dstudio-dark rounded-lg font-bold hover:bg-yellow-500 transition">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
