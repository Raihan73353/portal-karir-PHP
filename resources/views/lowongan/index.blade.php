@extends('layouts.app')

@section('title', 'Katalog Lowongan - Portal Karir')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Daftar Lowongan Tersedia</h1>
    <p class="text-sm text-gray-600 mt-1">Temukan peluang magang dan karier sesuai keahlian Anda.</p>
</div>

@if($lowongans->isEmpty())
    <div class="bg-white p-8 rounded-xl border border-gray-200 text-center text-gray-500">
        Belum ada data lowongan yang tersedia saat ini.
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($lowongans as $lowongan)
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <!-- Header Kartu: Logo & Status -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            @if($lowongan->instansi?->logo)
                                <img src="{{ asset('storage/' . $lowongan->instansi->logo) }}"
                                     alt="Logo {{ $lowongan->instansi->nama_lembaga }}"
                                     class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-lg">
                                    {{ strtoupper(substr($lowongan->instansi?->nama_lembaga ?? 'I', 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 leading-snug">
                                    <a href="{{ route('lowongan.show', $lowongan->id) }}" class="hover:text-blue-600">
                                        {{ $lowongan->judul_posisi }}
                                    </a>
                                </h2>
                                <p class="text-sm text-gray-600">
                                    {{ $lowongan->instansi?->nama_lembaga ?? 'Instansi tidak diketahui' }}
                                </p>
                            </div>
                        </div>

                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $lowongan->status === 'Dibuka' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $lowongan->status }}
                        </span>
                    </div>

                    <!-- Badge Kategori, Tipe Lowongan & Model Kerja -->
                    <div class="flex flex-wrap gap-2 mt-4">
                        <span class="px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded">
                            {{ $lowongan->kategori?->nama_kategori ?? 'Umum' }}
                        </span>
                        <span class="px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                            {{ $lowongan->tipe_lowongan }}
                        </span>
                        <span class="px-2.5 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-800 rounded">
                            {{ $lowongan->model_kerja }}
                        </span>
                    </div>

                    <!-- Info Lokasi, Kompensasi, Kuota & Deadline -->
                    <div class="mt-4 space-y-1.5 text-sm text-gray-600">
                        <p>
                            <span class="font-medium text-gray-800">Lokasi:</span>
                            {{ $lowongan->lokasi_penempatan }}
                            @if($lowongan->instansi?->kota)
                                ({{ $lowongan->instansi->kota }})
                            @endif
                        </p>
                        <p>
                            <span class="font-medium text-gray-800">Kompensasi:</span>
                            {{ $lowongan->rentang_kompensasi }}
                        </p>
                        <p>
                            <span class="font-medium text-gray-800">Kuota:</span>
                            {{ $lowongan->kuota }} orang
                        </p>
                        <p>
                            <span class="font-medium text-gray-800">Batas Lamaran:</span>
                            {{ $lowongan->tanggal_ditutup ? $lowongan->tanggal_ditutup->format('d M Y') : '-' }}
                        </p>
                    </div>
                </div>

                <!-- Tombol Detail -->
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <a href="{{ route('lowongan.show', $lowongan->id) }}"
                       class="block w-full text-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Lihat Detail Lowongan
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $lowongans->links() }}
    </div>
@endif
@endsection