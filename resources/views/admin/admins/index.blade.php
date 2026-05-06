@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Kelola Admin</h1>
    <a href="{{ route('admin.admins.create') }}" class="bg-dstudio-gold text-dstudio-dark px-4 py-2 rounded-lg font-bold hover:bg-yellow-500 transition">
        <i class="fas fa-plus mr-2"></i>Tambah Admin
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="w-full">
        <thead class="bg-dstudio-dark text-white">
            <tr>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Username</th>
                <th class="px-4 py-3 text-left">Role</th>
                <th class="px-4 py-3 text-left">Dibuat</th>
                <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($admins as $admin)
                <tr class="border-b hover:bg-gray-50 {{ $admin->id === session('admin_id') ? 'bg-yellow-50' : '' }}">
                    <td class="px-4 py-3 font-semibold">
                        {{ $admin->name }}
                        @if($admin->id === session('admin_id'))
                            <span class="ml-2 text-xs bg-dstudio-gold text-dstudio-dark px-2 py-0.5 rounded">You</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $admin->username }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs {{ $admin->role === 'superadmin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ strtoupper($admin->role) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $admin->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        @if($admin->id !== session('admin_id'))
                            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        Belum ada admin
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
