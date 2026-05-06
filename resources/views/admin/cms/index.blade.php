@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Content Management</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ route('admin.cms.update') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Hero Title</label>
            <input type="text" name="hero_title" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
                value="{{ $contents['hero_title']->value ?? '' }}">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Hero Subtitle</label>
            <input type="text" name="hero_subtitle" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
                value="{{ $contents['hero_subtitle']->value ?? '' }}">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">About Text</label>
            <textarea name="about_text" rows="4"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold">{{ $contents['about_text']->value ?? '' }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">WhatsApp Number</label>
            <div class="flex items-center">
                <span class="px-3 py-2 bg-gray-100 border border-r-0 rounded-l-lg text-gray-600">+</span>
                <input type="text" name="whatsapp_number" 
                    class="flex-1 px-4 py-2 border rounded-r-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
                    value="{{ $contents['whatsapp_number']->value ?? '' }}"
                    placeholder="6281234567890">
            </div>
            <p class="text-gray-500 text-sm mt-1">Format: 6281234567890 (tanpa + dan spasi)</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">QRIS Image URL</label>
            <input type="url" name="qris_image" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
                value="{{ $contents['qris_image']->value ?? '' }}"
                placeholder="https://...">
            @if($contents['qris_image']->value ?? false)
                <div class="mt-2">
                    <img src="{{ $contents['qris_image']->value }}" alt="QRIS Preview" class="w-48 h-48 object-contain border rounded">
                </div>
            @endif
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Instagram URL</label>
            <input type="url" name="instagram_url" 
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
                value="{{ $contents['instagram_url']->value ?? '' }}"
                placeholder="https://instagram.com/...">
        </div>

        <button type="submit" class="px-6 py-2 bg-dstudio-gold text-dstudio-dark rounded-lg font-bold hover:bg-yellow-500 transition">
            <i class="fas fa-save mr-2"></i>Simpan Perubahan
        </button>
    </form>
</div>
@endsection
