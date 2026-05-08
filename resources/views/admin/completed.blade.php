@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">Pesanan Selesai</h1>

<!-- Search -->
<form class="mb-6">
    <div class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
            class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
            placeholder="Cari nama atau kode tiket...">
        <button type="submit" class="bg-dstudio-dark text-white px-4 py-2 rounded-lg hover:bg-gray-800">
            <i class="fas fa-search"></i>
        </button>
        @if(request('search'))
            <a href="{{ route('admin.completed') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                <i class="fas fa-times"></i>
            </a>
        @endif
    </div>
</form>

<!-- Orders Table -->
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="w-full">
        <thead class="bg-dstudio-dark text-white">
            <tr>
                <th class="px-4 py-3 text-left">Tiket ID</th>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Layanan</th>
                <th class="px-4 py-3 text-left">Total</th>
                <th class="px-4 py-3 text-left">Rating</th>
                <th class="px-4 py-3 text-left">Tgl Selesai</th>
                <th class="px-4 py-3 text-left">Hasil</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanan as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono font-bold">{{ $item->kode_tiket }}</td>
                    <td class="px-4 py-3">{{ $item->nama_pelanggan }}</td>
                    <td class="px-4 py-3">{{ $item->layanan->nama_layanan }}</td>
                    <td class="px-4 py-3">Rp{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        @if($item->rating)
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $item->rating->nilai_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $item->selesai_at ? $item->selesai_at->format('d M Y H:i') : $item->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">
                        @if($item->link_foto_hasil)
                            <a href="{{ $item->link_foto_hasil }}" target="_blank" class="text-dstudio-gold hover:underline">
                                <i class="fas fa-external-link-alt mr-1"></i>Lihat
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        Belum ada pesanan yang selesai
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
