@extends('layouts.app')

@section('title', 'Daftar Akun - Portal Karir')

@section('content')
<div class="max-w-lg mx-auto bg-white border border-gray-200 rounded-xl p-6 sm:p-8 shadow-sm mt-4">
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h1>
        <p class="text-sm text-gray-600 mt-1">Pilih peran Anda untuk membuat profil awal secara otomatis.</p>
    </div>

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Pilihan Peran (Enum: pelamar | admin) -->
        <div>
            <label for="peran" class="block text-sm font-medium text-gray-700 mb-1">Daftar Sebagai *</label>
            <select id="peran" name="peran" required
                    class="w-full px-3 py-2 border border-gray-300 bg-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="pelamar" {{ old('peran', 'pelamar') === 'pelamar' ? 'selected' : '' }}>
                    Pelamar (Pencari Magang / Kerja)
                </option>
                <option value="admin" {{ old('peran') === 'admin' ? 'selected' : '' }}>
                    Admin Instansi (Penyedia Lowongan)
                </option>
            </select>
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                   placeholder="Nama lengkap sesuai identitas"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       placeholder="nama@email.com"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon / WA</label>
                <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                       placeholder="0812xxxxxxxx"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Field Khusus Pelamar (profil_pelamars.asal_sekolah_kampus) -->
        <div id="field-pelamar">
            <label for="asal_sekolah_kampus" class="block text-sm font-medium text-gray-700 mb-1">
                Asal Sekolah / Kampus (Opsional)
            </label>
            <input type="text" id="asal_sekolah_kampus" name="asal_sekolah_kampus"
                   value="{{ old('asal_sekolah_kampus') }}"
                   placeholder="Contoh: Universitas Malikussaleh"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Field Khusus Admin (profil_instansis.nama_lembaga) -->
        <div id="field-admin" class="hidden">
            <label for="nama_lembaga" class="block text-sm font-medium text-gray-700 mb-1">
                Nama Lembaga / Perusahaan *
            </label>
            <input type="text" id="nama_lembaga" name="nama_lembaga"
                   value="{{ old('nama_lembaga') }}"
                   placeholder="Contoh: PT Teknologi Nusantara"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi *</label>
                <input type="password" id="password" name="password" required
                       placeholder="Minimal 8 karakter"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Sandi *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       placeholder="Ulangi kata sandi"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <button type="submit"
                class="w-full py-2.5 px-4 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 mt-2">
            Daftar & Buat Profil
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline">
            Masuk di sini
        </a>
    </p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectPeran = document.getElementById('peran');
        const fieldPelamar = document.getElementById('field-pelamar');
        const fieldAdmin = document.getElementById('field-admin');
        const inputLembaga = document.getElementById('nama_lembaga');

        function toggleRoleFields() {
            if (selectPeran.value === 'admin') {
                fieldAdmin.classList.remove('hidden');
                fieldPelamar.classList.add('hidden');
                inputLembaga.setAttribute('required', 'required');
            } else {
                fieldAdmin.classList.add('hidden');
                fieldPelamar.classList.remove('hidden');
                inputLembaga.removeAttribute('required');
            }
        }

        toggleRoleFields();
        selectPeran.addEventListener('change', toggleRoleFields);
    });
</script>
@endsection