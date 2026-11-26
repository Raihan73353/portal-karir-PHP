@extends('layouts.app')

@section('title', 'Seleksi Lamaran Masuk - Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Daftar Lamaran Masuk</h1>
    <p class="text-sm text-gray-600 mt-1">Tinjau berkas CV pelamar, ubah status seleksi, dan berikan umpan balik.</p>
</div>

<div class="space-y-4">
    @forelse($lamarans as $lamaran)
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Info Pelamar & Lowongan -->
                <div class="lg:col-span-2 space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-lg font-bold text-gray-900">
                            {{ $lamaran->pelamar?->user?->name ?? 'Pelamar tidak ditemukan' }}
                        </h2>
                        <span class="text-xs px-2.5 py-0.5 rounded bg-blue-100 text-blue-800 font-medium">
                            Melamar: {{ $lamaran->lowongan?->judul_posisi }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600">
                        <span class="font-medium text-gray-800">Instansi:</span> {{ $lamaran->lowongan?->instansi?->nama_lembaga ?? '-' }}
                        &bull; <span class="font-medium text-gray-800">Pendidikan:</span> {{ $lamaran->pelamar?->jenjang_pendidikan ?? '-' }} ({{ $lamaran->pelamar?->asal_sekolah_kampus ?? '-' }})
                    </p>

                    @if($lamaran->pelamar?->daftar_keahlian)
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-gray-800">Keahlian:</span> {{ $lamaran->pelamar->daftar_keahlian }}
                        </p>
                    @endif

                    @if($lamaran->catatan_pelamar)
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 mt-2">
                            <span class="font-medium text-gray-900">Catatan Pelamar:</span> {{ $lamaran->catatan_pelamar }}
                        </div>
                    @endif

                    <div class="pt-2 flex flex-wrap gap-4 text-sm">
                        <a href="{{ asset('storage/' . $lamaran->berkas_cv_dikirim) }}" target="_blank"
                           class="font-medium text-blue-600 hover:underline">
                            Unduh / Lihat CV Dikirim (PDF) &rarr;
                        </a>
                        @if($lamaran->pelamar?->tautan_portofolio)
                            <a href="{{ $lamaran->pelamar->tautan_portofolio }}" target="_blank"
                               class="font-medium text-indigo-600 hover:underline">
                                Lihat Portofolio &rarr;
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Form Update Status Seleksi & Umpan Balik -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <form action="{{ route('admin.lamaran.update', $lamaran->id) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Status Seleksi</label>
                            <select name="status_seleksi" required class="w-full px-3 py-2 border border-gray-300 bg-white rounded-lg text-sm">
                                @foreach(['Terkirim', 'Sedang Ditinjau', 'Wawancara', 'Diterima', 'Ditolak'] as $status)
                                    <option value="{{ $status }}" {{ $lamaran->status_seleksi === $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Umpan Balik Admin</label>
                            <textarea name="umpan_balik_admin" rows="3" placeholder="Jadwal wawancara atau alasan keputusan..."
                                      class="w-full px-3 py-2 border border-gray-300 bg-white rounded-lg text-sm">{{ old('umpan_balik_admin', $lamaran->umpan_balik_admin) }}</textarea>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Perbarui Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white border border-gray-200 rounded-xl p-8 text-center text-gray-500">
            Belum ada lamaran yang masuk.
        </div>
    @endforelse
</div>
@endsection