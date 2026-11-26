@extends('layouts.app')

@section('title', 'Kelola Kategori Lowongan - Admin')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah Kategori -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm h-fit">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Tambah Kategori Baru</h2>
        <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" required
                       placeholder="Contoh: Pengembangan Perangkat Lunak"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug') }}" required
                       placeholder="Contoh: pengembangan-perangkat-lunak"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Simpan Kategori
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-lg font-bold text-gray-900">Daftar Kategori Lowongan</h1>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Nama Kategori</th>
                        <th class="px-6 py-3 font-semibold">Slug</th>
                        <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kategoris as $kategori)
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $kategori->nama_kategori }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $kategori->slug }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-6 text-center text-gray-500">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection