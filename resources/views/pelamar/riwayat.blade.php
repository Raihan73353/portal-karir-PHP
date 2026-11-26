@extends('layouts.app')

@section('title', 'Riwayat Lamaran Saya - Portal Karir')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Riwayat Lamaran Saya</h1>
    <p class="text-sm text-gray-600 mt-1">Pantau progres seleksi dan umpan balik dari admin instansi.</p>
</div>

@php
    $badgeWarna = [
        'Terkirim' => 'bg-gray-100 text-gray-800',
        'Sedang Ditinjau' => 'bg-blue-100 text-blue-800',
        'Wawancara' => 'bg-yellow-100 text-yellow-800',
        'Diterima' => 'bg-green-100 text-green-800',
        'Ditolak' => 'bg-red-100 text-red-800',
    ];
@endphp

@if($lamarans->isEmpty())
    <div class="bg-white border border-gray-200 rounded-xl p-8 text-center">
        <p class="text-gray-600 mb-4">Anda belum pernah mengirim lamaran pekerjaan atau magang.</p>
        <a href="{{ route('lowongan.index') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Cari Lowongan Sekarang
        </a>
    </div>
@else
    <div class="space-y-4">
        @foreach($lamarans as $lamaran)
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('lowongan.show', $lamaran->lowongan_id) }}" class="hover:text-blue-600">
                                {{ $lamaran->lowongan?->judul_posisi ?? 'Lowongan dihapus' }}
                            </a>
                        </h2>
                        <p class="text-sm text-gray-600">
                            {{ $lamaran->lowongan?->instansi?->nama_lembaga ?? '-' }}
                            &bull; Dikirim pada {{ $lamaran->created_at?->format('d M Y, H:i') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ asset('storage/' . $lamaran->berkas_cv_dikirim) }}" target="_blank"
                           class="text-xs font-medium text-blue-600 hover:underline">
                            Lihat CV Terkirim
                        </a>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $badgeWarna[$lamaran->status_seleksi] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $lamaran->status_seleksi }}
                        </span>
                    </div>
                </div>

                @if($lamaran->catatan_pelamar)
                    <div class="mt-4 text-sm text-gray-600">
                        <span class="font-medium text-gray-800">Catatan Anda:</span> {{ $lamaran->catatan_pelamar }}
                    </div>
                @endif

                @if(!empty($lamaran->umpan_balik_admin))
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs font-semibold uppercase tracking-wide text-blue-800 mb-1">Umpan Balik Admin</p>
                        <p class="text-sm text-blue-900">{{ $lamaran->umpan_balik_admin }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
@endsection