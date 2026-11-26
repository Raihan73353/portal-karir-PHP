@extends('layouts.app')

@section('title', 'Kelola Profil Instansi - Admin')

@section('content')
<div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm mb-8">
    <h2 class="text-lg font-bold text-gray-900 mb-4">Tambah Profil Instansi</h2>
    <form action="{{ route('admin.instansi.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Akun Pengelola (User Admin) *</label>
            <select name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                @foreach($adminUsers as $admin)
                    <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lembaga *</label>
            <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Lembaga</label>
            <input type="text" name="jenis_lembaga" placeholder="Contoh: Perusahaan Teknologi / Instansi Pemerintah"
                   value="{{ old('jenis_lembaga') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
            <input type="text" name="kota" value="{{ old('kota') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tautan Situs</label>
            <input type="url" name="tautan_situs" placeholder="https://..." value="{{ old('tautan_situs') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Logo Instansi</label>
            <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg p-1.5">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
            <input type="text" name="alamat_lengkap" value="{{ old('alamat_lengkap') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Instansi</label>
            <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="md:col-span-2 flex justify-end">
            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Simpan Instansi
            </button>
        </div>
    </form>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h1 class="text-lg font-bold text-gray-900">Daftar Instansi Terdaftar</h1>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 font-semibold">Lembaga</th>
                    <th class="px-6 py-3 font-semibold">Jenis</th>
                    <th class="px-6 py-3 font-semibold">Kota</th>
                    <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($instansis as $instansi)
                    <tr>
                        <td class="px-6 py-4 flex items-center gap-3">
                            @if($instansi->logo)
                                <img src="{{ asset('storage/' . $instansi->logo) }}" class="w-10 h-10 rounded object-cover border" alt="Logo">
                            @endif
                            <span class="font-medium text-gray-900">{{ $instansi->nama_lembaga }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $instansi->jenis_lembaga ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $instansi->kota ?? '-' }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.instansi.edit', $instansi->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.instansi.destroy', $instansi->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus instansi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-gray-500">Belum ada profil instansi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection