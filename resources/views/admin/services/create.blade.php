@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tambah Layanan</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form action="{{ route('admin.services.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nama Layanan</label>
            <input type="text" name="nama_layanan" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('nama_layanan') border-red-500 @enderror"
                value="{{ old('nama_layanan') }}"
                required>
            @error('nama_layanan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Harga (Rp)</label>
            <input type="number" name="harga" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('harga') border-red-500 @enderror"
                value="{{ old('harga') }}"
                required>
            @error('harga')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" checked
                    class="w-4 h-4 text-dstudio-gold rounded focus:ring-dstudio-gold">
                <span class="ml-2 text-gray-700">Aktif</span>
            </label>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('admin.services.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-dstudio-gold text-dstudio-dark rounded-lg font-bold hover:bg-yellow-500 transition">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
