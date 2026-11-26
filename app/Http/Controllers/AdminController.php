<?php

namespace App\Http\Controllers;

use App\Models\KategoriLowongan;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\ProfilInstansi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    // =========================================================
    // 1. MANAJEMEN KATEGORI LOWONGAN (kategori_lowongans)
    // =========================================================

    public function indexKategori(): View
    {
        $kategoris = KategoriLowongan::latest()->get();

        return view('admin.kategori.index', compact('kategoris'));
    }

    public function storeKategori(Request $request): RedirectResponse
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $request->input('nama_kategori')),
        ]);

        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:kategori_lowongans,slug'],
        ]);

        KategoriLowongan::create($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori lowongan berhasil ditambahkan.');
    }

    public function editKategori(KategoriLowongan $kategori): View
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function updateKategori(Request $request, KategoriLowongan $kategori): RedirectResponse
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $request->input('nama_kategori')),
        ]);

        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('kategori_lowongans', 'slug')->ignore($kategori->id)],
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori lowongan berhasil diperbarui.');
    }

    public function destroyKategori(KategoriLowongan $kategori): RedirectResponse
    {
        if ($kategori->lowongans()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data lowongan.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori lowongan berhasil dihapus.');
    }

    // =========================================================
    // 2. MANAJEMEN PROFIL INSTANSI (profil_instansis)
    // =========================================================

    public function indexInstansi(): View
    {
        $instansis = ProfilInstansi::with('user')->latest()->get();
        $adminUsers = User::where('peran', 'admin')->get();

        return view('admin.instansi.index', compact('instansis', 'adminUsers'));
    }

    public function storeInstansi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'nama_lembaga' => ['required', 'string', 'max:255'],
            'jenis_lembaga' => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'alamat_lengkap' => ['nullable', 'string'],
            'kota' => ['nullable', 'string', 'max:100'],
            'tautan_situs' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logo-instansi', 'public');
        }

        ProfilInstansi::create([
            'user_id' => $validated['user_id'],
            'nama_lembaga' => $validated['nama_lembaga'],
            'jenis_lembaga' => $validated['jenis_lembaga'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'alamat_lengkap' => $validated['alamat_lengkap'] ?? null,
            'kota' => $validated['kota'] ?? null,
            'tautan_situs' => $validated['tautan_situs'] ?? null,
            'logo' => $logoPath,
        ]);

        return redirect()->route('admin.instansi.index')
            ->with('success', 'Profil instansi berhasil ditambahkan.');
    }

    public function editInstansi(ProfilInstansi $instansi): View
    {
        $adminUsers = User::where('peran', 'admin')->get();

        return view('admin.instansi.edit', compact('instansi', 'adminUsers'));
    }

    public function updateInstansi(Request $request, ProfilInstansi $instansi): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'nama_lembaga' => ['required', 'string', 'max:255'],
            'jenis_lembaga' => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'alamat_lengkap' => ['nullable', 'string'],
            'kota' => ['nullable', 'string', 'max:100'],
            'tautan_situs' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $logoPath = $instansi->logo;
        if ($request->hasFile('logo')) {
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $request->file('logo')->store('logo-instansi', 'public');
        }

        $instansi->update([
            'user_id' => $validated['user_id'],
            'nama_lembaga' => $validated['nama_lembaga'],
            'jenis_lembaga' => $validated['jenis_lembaga'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'alamat_lengkap' => $validated['alamat_lengkap'] ?? null,
            'kota' => $validated['kota'] ?? null,
            'tautan_situs' => $validated['tautan_situs'] ?? null,
            'logo' => $logoPath,
        ]);

        return redirect()->route('admin.instansi.index')
            ->with('success', 'Profil instansi berhasil diperbarui.');
    }

    public function destroyInstansi(ProfilInstansi $instansi): RedirectResponse
    {
        if ($instansi->lowongans()->exists()) {
            return back()->with('error', 'Instansi tidak dapat dihapus karena masih memiliki lowongan terdaftar.');
        }

        if ($instansi->logo && Storage::disk('public')->exists($instansi->logo)) {
            Storage::disk('public')->delete($instansi->logo);
        }

        $instansi->delete();

        return redirect()->route('admin.instansi.index')
            ->with('success', 'Profil instansi berhasil dihapus.');
    }

    // =========================================================
    // 3. MANAJEMEN LOWONGAN (lowongans)
    // =========================================================

    public function indexLowongan(): View
    {
        $lowongans = Lowongan::with(['instansi', 'kategori'])->latest()->get();
        $instansis = ProfilInstansi::all();
        $kategoris = KategoriLowongan::all();

        return view('admin.lowongan.index', compact('lowongans', 'instansis', 'kategoris'));
    }

    public function storeLowongan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'instansi_id' => ['required', 'exists:profil_instansis,id'],
            'kategori_id' => ['required', 'exists:kategori_lowongans,id'],
            'judul_posisi' => ['required', 'string', 'max:255'],
            'tipe_lowongan' => ['required', 'in:Magang,Penuh Waktu,Paruh Waktu,Kontrak'],
            'model_kerja' => ['required', 'in:Luring,Daring,Hybrid'],
            'lokasi_penempatan' => ['required', 'string', 'max:255'],
            'uraian_tugas' => ['required', 'string'],
            'persyaratan' => ['required', 'string'],
            'kompensasi_min' => ['nullable', 'integer', 'min:0'],
            'kompensasi_max' => [
                'nullable',
                'integer',
                'min:0',
                Rule::when($request->filled('kompensasi_min'), ['gte:kompensasi_min']),
            ],
            'kuota' => ['required', 'integer', 'min:1'],
            'tanggal_ditutup' => ['required', 'date'],
            'status' => ['required', 'in:Dibuka,Ditutup'],
        ]);

        Lowongan::create($validated);

        return redirect()->route('admin.lowongan.index')
            ->with('success', 'Lowongan baru berhasil diterbitkan.');
    }

    public function editLowongan(Lowongan $lowongan): View
    {
        $instansis = ProfilInstansi::all();
        $kategoris = KategoriLowongan::all();

        return view('admin.lowongan.edit', compact('lowongan', 'instansis', 'kategoris'));
    }

    public function updateLowongan(Request $request, Lowongan $lowongan): RedirectResponse
    {
        $validated = $request->validate([
            'instansi_id' => ['required', 'exists:profil_instansis,id'],
            'kategori_id' => ['required', 'exists:kategori_lowongans,id'],
            'judul_posisi' => ['required', 'string', 'max:255'],
            'tipe_lowongan' => ['required', 'in:Magang,Penuh Waktu,Paruh Waktu,Kontrak'],
            'model_kerja' => ['required', 'in:Luring,Daring,Hybrid'],
            'lokasi_penempatan' => ['required', 'string', 'max:255'],
            'uraian_tugas' => ['required', 'string'],
            'persyaratan' => ['required', 'string'],
            'kompensasi_min' => ['nullable', 'integer', 'min:0'],
            'kompensasi_max' => [
                'nullable',
                'integer',
                'min:0',
                Rule::when($request->filled('kompensasi_min'), ['gte:kompensasi_min']),
            ],
            'kuota' => ['required', 'integer', 'min:1'],
            'tanggal_ditutup' => ['required', 'date'],
            'status' => ['required', 'in:Dibuka,Ditutup'],
        ]);

        $lowongan->update($validated);

        return redirect()->route('admin.lowongan.index')
            ->with('success', 'Data lowongan berhasil diperbarui.');
    }

    public function destroyLowongan(Lowongan $lowongan): RedirectResponse
    {
        if ($lowongan->lamarans()->exists()) {
            return back()->with('error', 'Lowongan tidak dapat dihapus karena sudah memiliki pelamar. Ubah status menjadi Ditutup.');
        }

        $lowongan->delete();

        return redirect()->route('admin.lowongan.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }

    // =========================================================
    // 4. MANAJEMEN SELEKSI LAMARAN (lamarans)
    // =========================================================

    public function indexLamaran(): View
    {
        $lamarans = Lamaran::with(['lowongan.instansi', 'pelamar.user'])
            ->latest()
            ->get();

        return view('admin.lamaran.index', compact('lamarans'));
    }

    public function updateLamaran(Request $request, Lamaran $lamaran): RedirectResponse
    {
        $validated = $request->validate([
            'status_seleksi' => ['required', 'in:Terkirim,Sedang Ditinjau,Wawancara,Diterima,Ditolak'],
            'umpan_balik_admin' => ['nullable', 'string'],
        ]);

        $lamaran->update($validated);

        return redirect()->route('admin.lamaran.index')
            ->with('success', 'Status seleksi dan umpan balik pelamar berhasil diperbarui.');
    }
}