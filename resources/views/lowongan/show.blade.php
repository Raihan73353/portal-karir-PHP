@extends('layouts.app')

@section('title', $lowongan->judul_posisi . ' - Portal Karir')

@section('content')
<div class="mb-4">
    <a href="{{ route('lowongan.index') }}" class="text-sm font-medium text-blue-600 hover:underline">
        &larr; Kembali ke Katalog Lowongan
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Uraian Tugas & Persyaratan -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200 pb-6">
                <div class="flex items-center gap-4">
                    @if($lowongan->instansi?->logo)
                        <img src="{{ asset('storage/' . $lowongan->instansi->logo) }}"
                             alt="Logo {{ $lowongan->instansi->nama_lembaga }}"
                             class="w-16 h-16 rounded-xl object-cover border border-gray-200">
                    @else
                        <div class="w-16 h-16 rounded-xl bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-2xl">
                            {{ strtoupper(substr($lowongan->instansi?->nama_lembaga ?? 'I', 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $lowongan->judul_posisi }}</h1>
                        <p class="text-base font-medium text-gray-700">{{ $lowongan->instansi?->nama_lembaga }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $lowongan->lokasi_penempatan }}
                            @if($lowongan->instansi?->kota) &bull; {{ $lowongan->instansi->kota }} @endif
                        </p>
                    </div>
                </div>

                <span class="self-start px-3 py-1 text-xs font-semibold rounded-full {{ $lowongan->status === 'Dibuka' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $lowongan->status }}
                </span>
            </div>

            <!-- Ringkasan Atribut -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-4 border-b border-gray-200 text-sm">
                <div>
                    <span class="text-gray-500 block">Kategori</span>
                    <span class="font-semibold text-gray-900">{{ $lowongan->kategori?->nama_kategori ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block">Tipe Lowongan</span>
                    <span class="font-semibold text-gray-900">{{ $lowongan->tipe_lowongan }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block">Model Kerja</span>
                    <span class="font-semibold text-gray-900">{{ $lowongan->model_kerja }}</span>
                </div>
                <div>
                    <span class="text-gray-500 block">Kuota</span>
                    <span class="font-semibold text-gray-900">{{ $lowongan->kuota }} Orang</span>
                </div>
            </div>

            <!-- Uraian Tugas -->
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Uraian Tugas</h2>
                <div class="text-sm text-gray-700 leading-relaxed">
                    {!! nl2br(e($lowongan->uraian_tugas)) !!}
                </div>
            </div>

            <!-- Persyaratan -->
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Persyaratan</h2>
                <div class="text-sm text-gray-700 leading-relaxed">
                    {!! nl2br(e($lowongan->persyaratan)) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Aksi Lamar & Profil Instansi -->
    <div class="space-y-6">
        <!-- Kartu Kompensasi & Tombol Lamar -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <p class="text-xs uppercase tracking-wider text-gray-500 font-semibold">Estimasi Kompensasi</p>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $lowongan->rentang_kompensasi }}</p>

            <p class="text-sm text-gray-600 mt-3">
                Batas Pendaftaran:
                <span class="font-semibold text-gray-900">
                    {{ $lowongan->tanggal_ditutup ? $lowongan->tanggal_ditutup->format('d F Y') : '-' }}
                </span>
            </p>

            <div class="mt-6">
                @guest
                    <a href="{{ route('login') }}"
                       class="block w-full text-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Masuk untuk Melamar
                    </a>
                @else
                    @if(auth()->user()->peran === 'pelamar')
                        @if(isset($lamaranSaya) && $lamaranSaya)
                            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                                Anda sudah melamar posisi ini. Status:
                                <strong>{{ $lamaranSaya->status_seleksi }}</strong>
                            </div>
                        @elseif($lowongan->status === 'Ditutup')
                            <button disabled class="w-full px-4 py-2.5 text-sm font-medium text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed">
                                Lowongan Ditutup
                            </button>
                        @elseif(!auth()->user()->profilPelamar)
                            <a href="{{ route('pelamar.profil.edit') }}"
                               class="block w-full text-center px-4 py-2.5 text-sm font-medium text-yellow-900 bg-yellow-100 border border-yellow-300 rounded-lg hover:bg-yellow-200">
                                Lengkapi Profil Pelamar Dulu
                            </a>
                        @else
                            <a href="{{ route('pelamar.lamar.create', $lowongan->id) }}"
                               class="block w-full text-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                Lamar Posisi Ini
                            </a>
                        @endif
                    @endif
                @endguest
            </div>
        </div>

        <!-- Kartu Tentang Instansi -->
        @if($lowongan->instansi)
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900 mb-3">Tentang Instansi</h3>
                <p class="text-sm font-medium text-gray-800">{{ $lowongan->instansi->nama_lembaga }}</p>
                @if($lowongan->instansi->jenis_lembaga)
                    <p class="text-xs text-gray-500 mb-3">{{ $lowongan->instansi->jenis_lembaga }}</p>
                @endif
                @if($lowongan->instansi->deskripsi)
                    <p class="text-sm text-gray-600 leading-relaxed mb-3">{{ $lowongan->instansi->deskripsi }}</p>
                @endif
                @if($lowongan->instansi->alamat_lengkap)
                    <p class="text-xs text-gray-500 mb-2">
                        <span class="font-medium text-gray-700">Alamat:</span> {{ $lowongan->instansi->alamat_lengkap }}
                    </p>
                @endif
                @if($lowongan->instansi->tautan_situs)
                    <a href="{{ $lowongan->instansi->tautan_situs }}" target="_blank" rel="noopener noreferrer"
                       class="text-sm text-blue-600 hover:underline">
                        Kunjungi Situs Instansi &rarr;
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection