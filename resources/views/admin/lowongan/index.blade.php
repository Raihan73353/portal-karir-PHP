@extends('layouts.app')

@section('title', 'Kelola Lowongan - Admin')

@section('content')
<div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm mb-8">
    <h2 class="text-lg font-bold text-gray-900 mb-4">Publikasikan Lowongan Baru</h2>
    <form action="{{ route('admin.lowongan.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Instansi *</label>
            <select name="instansi_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">-- Pilih Instansi --</option>
                @foreach($instansis as $instansi)
                    <option value="{{ $instansi->id }}">{{ $instansi->nama_lembaga }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
            <select name="kategori_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Posisi *</label>
            <input type="text" name="judul_posisi" value="{{ old('judul_posisi') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Lowongan *</label>
            <select name="tipe_lowongan" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                @foreach(['Magang', 'Penuh Waktu', 'Paruh Waktu', 'Kontrak'] as $tipe)
                    <option value="{{ $tipe }}" {{ old('tipe_lowongan') === $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Model Kerja *</label>
            <select name="model_kerja" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                @foreach(['Luring', 'Daring', 'Hybrid'] as $model)
                    <option value="{{ $model }}" {{ old('model_kerja') === $model ? 'selected' : '' }}>{{ $model }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Penempatan *</label>
            <input type="text" name="lokasi_penempatan" value="{{ old('lokasi_penempatan') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kompensasi Minimum (Rp)</label>
            <input type="number" name="kompensasi_min" value="{{ old('kompensasi_min') }}" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kompensasi Maksimum (Rp)</label>
            <input type="number" name="kompensasi_max" value="{{ old('kompensasi_max') }}" min="0"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kuota *</label>
            <input type="number" name="kuota" value="{{ old('kuota', 1) }}" min="1" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Ditutup *</label>
            <input type="date" name="tanggal_ditutup" value="{{ old('tanggal_ditutup') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
            <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="Dibuka" {{ old('status') === 'Dibuka' ? 'selected' : '' }}>Dibuka</option>
                <option value="Ditutup" {{ old('status') === 'Ditutup' ? 'selected' : '' }}>Ditutup</option>
            </select>
        </div>

        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-1">Uraian Tugas *</label>
            <textarea name="uraian_tugas" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('uraian_tugas') }}</textarea>
        </div>

        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-1">Persyaratan *</label>
            <textarea name="persyaratan" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('persyaratan') }}</textarea>
        </div>

        <div class="md:col-span-3 flex justify-end">
            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Terbitkan Lowongan
            </button>
        </div>
    </form>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h1 class="text-lg font-bold text-gray-900">Daftar Lowongan</h1>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 font-semibold">Posisi</th>
                    <th class="px-6 py-3 font-semibold">Instansi</th>
                    <th class="px-6 py-3 font-semibold">Tipe / Model</th>
                    <th class="px-6 py-3 font-semibold">Status</th>
                    <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($lowongans as $lowongan)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $lowongan->judul_posisi }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $lowongan->instansi?->nama_lembaga }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $lowongan->tipe_lowongan }} ({{ $lowongan->model_kerja }})</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $lowongan->status === 'Dibuka' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $lowongan->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.lowongan.edit', $lowongan->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.lowongan.destroy', $lowongan->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus lowongan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-500">Belum ada lowongan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection