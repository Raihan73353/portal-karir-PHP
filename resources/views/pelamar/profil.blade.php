@extends('layouts.app')

@section('title', 'Profil & CV Pelamar - Portal Karir')

@section('content')
<div class="max-w-4xl mx-auto bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Profil Pelamar & Berkas CV</h1>
    <p class="text-sm text-gray-600 mb-6">Lengkapi data diri dan unggah CV terbaru sebelum melamar lowongan.</p>

    <form action="{{ route('pelamar.profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Bagian 1: Data Akun (Tabel users) -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Data Akun</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil (JPG/PNG)</label>
                    <input type="file" name="foto_profil" accept="image/*"
                           class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg p-1.5">
                    @if($user->foto_profil)
                        <div class="mt-2 flex items-center gap-3">
                            <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" class="w-12 h-12 rounded-full object-cover border">
                            <span class="text-xs text-gray-500">Foto profil saat ini</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bagian 2: Data Biodata & Akademik (Tabel profil_pelamars) -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Biodata & Pendidikan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $profil?->tanggal_lahir?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $profil?->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $profil?->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang Pendidikan</label>
                    <input type="text" name="jenjang_pendidikan" placeholder="Contoh: S1 Teknik Informatika / SMK RPL"
                           value="{{ old('jenjang_pendidikan', $profil?->jenjang_pendidikan) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah / Kampus</label>
                    <input type="text" name="asal_sekolah_kampus"
                           value="{{ old('asal_sekolah_kampus', $profil?->asal_sekolah_kampus) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Domisili</label>
                    <textarea name="alamat_domisili" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('alamat_domisili', $profil?->alamat_domisili) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan Profil</label>
                    <textarea name="ringkasan_profil" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('ringkasan_profil', $profil?->ringkasan_profil) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Daftar Keahlian</label>
                    <input type="text" name="daftar_keahlian" placeholder="Contoh: Laravel, PHP, MySQL, Tailwind CSS"
                           value="{{ old('daftar_keahlian', $profil?->daftar_keahlian) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Berkas CV Default (PDF)</label>
                    <input type="file" name="berkas_cv" accept=".pdf"
                           class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg p-1.5">
                    @if($profil?->berkas_cv)
                        <a href="{{ asset('storage/' . $profil->berkas_cv) }}" target="_blank"
                           class="inline-block mt-2 text-xs font-medium text-blue-600 hover:underline">
                            Lihat CV Tersimpan (PDF) &rarr;
                        </a>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tautan Portofolio / GitHub / LinkedIn</label>
                    <input type="url" name="tautan_portofolio" placeholder="https://..."
                           value="{{ old('tautan_portofolio', $profil?->tautan_portofolio) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-200">
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Simpan Profil
            </button>
        </div>
    </form>
</div>
@endsection