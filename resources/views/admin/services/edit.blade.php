@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Layanan</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form action="{{ route('admin.services.update', $service) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Nama Layanan</label>
            <input type="text" name="name" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('name') border-red-500 @enderror"
                value="{{ old('name', $service->name) }}"
                required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Harga (Rp)</label>
            <input type="number" name="price" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold @error('price') border-red-500 @enderror"
                value="{{ old('price', $service->price) }}"
                required>
            @error('price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
            <textarea name="description" rows="3"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold">{{ old('description', $service->description) }}</textarea>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ $service->is_active ? 'checked' : '' }}
                    class="w-4 h-4 text-dstudio-gold rounded focus:ring-dstudio-gold">
                <span class="ml-2 text-gray-700">Aktif</span>
            </label>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('admin.services.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-dstudio-gold text-dstudio-dark rounded-lg font-bold hover:bg-yellow-500 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
