@extends('layouts.app')

@section('title', 'Kirim Lamaran - ' . $lowongan->judul_posisi)

@section('content')
<div class="max-w-2xl mx-auto bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
    <h1 class="text-xl font-bold text-gray-900">Kirim Lamaran</h1>
    <p class="text-sm text-gray-600 mt-1">
        Posisi: <span class="font-semibold text-gray-800">{{ $lowongan->judul_posisi }}</span>
        &mdash; {{ $lowongan->instansi?->nama_lembaga }}
    </p>

    <form action="{{ route('pelamar.lamar.store', $lowongan->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Berkas CV Dikirim (PDF) {{ $profil->berkas_cv ? '(Opsional jika memakai CV profil)' : '*' }}
            </label>
            <input type="file" name="berkas_cv_dikirim" accept=".pdf" {{ $profil->berkas_cv ? '' : 'required' }}
                   class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg p-2">
            @if($profil->berkas_cv)
                <p class="text-xs text-gray-500 mt-1">
                    Jika dikosongkan, sistem akan otomatis melampirkan
                    <a href="{{ asset('storage/' . $profil->berkas_cv) }}" target="_blank" class="text-blue-600 underline">CV dari profil Anda</a>.
                </p>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan / Surat Pengantar Singkat</label>
            <textarea name="catatan_pelamar" rows="4" placeholder="Jelaskan minat dan kualifikasi Anda secara singkat..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('catatan_pelamar') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('lowongan.show', $lowongan->id) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Kirim Lamaran Sekarang
            </button>
        </div>
    </form>
</div>
@endsection