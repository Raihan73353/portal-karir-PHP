<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\ProfilPelamar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PelamarController extends Controller
{
    public function editProfil(): View
    {
        $user = Auth::user();
        $profil = $user->profilPelamar;

        return view('pelamar.profil', compact('user', 'profil'));
    }

    public function updateProfil(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $profil = $user->profilPelamar;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'in:Laki-laki,Perempuan'],
            'alamat_domisili' => ['nullable', 'string'],
            'jenjang_pendidikan' => ['nullable', 'string', 'max:100'],
            'asal_sekolah_kampus' => ['nullable', 'string', 'max:255'],
            'ringkasan_profil' => ['nullable', 'string'],
            'daftar_keahlian' => ['nullable', 'string'],
            'berkas_cv' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'tautan_portofolio' => ['nullable', 'url', 'max:255'],
        ]);

        // Kelola unggahan foto_profil pada tabel users
        $fotoPath = $user->foto_profil;
        if ($request->hasFile('foto_profil')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto_profil')->store('foto-profil', 'public');
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_telepon' => $validated['no_telepon'] ?? null,
            'foto_profil' => $fotoPath,
        ]);

        // Kelola unggahan berkas_cv pada tabel profil_pelamars
        $cvPath = $profil?->berkas_cv;
        if ($request->hasFile('berkas_cv')) {
            if ($cvPath && Storage::disk('public')->exists($cvPath)) {
                Storage::disk('public')->delete($cvPath);
            }
            $cvPath = $request->file('berkas_cv')->store('berkas-cv', 'public');
        }

        ProfilPelamar::updateOrCreate(
            ['user_id' => $user->id],
            [
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                'alamat_domisili' => $validated['alamat_domisili'] ?? null,
                'jenjang_pendidikan' => $validated['jenjang_pendidikan'] ?? null,
                'asal_sekolah_kampus' => $validated['asal_sekolah_kampus'] ?? null,
                'ringkasan_profil' => $validated['ringkasan_profil'] ?? null,
                'daftar_keahlian' => $validated['daftar_keahlian'] ?? null,
                'berkas_cv' => $cvPath,
                'tautan_portofolio' => $validated['tautan_portofolio'] ?? null,
            ]
        );

        return redirect()->route('pelamar.profil.edit')
            ->with('success', 'Profil pelamar dan berkas berhasil diperbarui.');
    }

    public function createLamaran(Lowongan $lowongan): View|RedirectResponse
    {
        $lowongan->load('instansi');

        if ($lowongan->status !== 'Dibuka') {
            return redirect()->route('lowongan.show', $lowongan->id)
                ->with('error', 'Lowongan ini sudah ditutup.');
        }

        $profil = Auth::user()->profilPelamar;
        if (!$profil) {
            return redirect()->route('pelamar.profil.edit')
                ->with('error', 'Silakan lengkapi profil pelamar terlebih dahulu sebelum melamar.');
        }

        $sudahMelamar = Lamaran::where('lowongan_id', $lowongan->id)
            ->where('pelamar_id', $profil->id)
            ->exists();

        if ($sudahMelamar) {
            return redirect()->route('lowongan.show', $lowongan->id)
                ->with('error', 'Anda sudah mengirimkan lamaran untuk posisi ini.');
        }

        return view('pelamar.lamar', compact('lowongan', 'profil'));
    }

    public function storeLamaran(Request $request, Lowongan $lowongan): RedirectResponse
    {
        if ($lowongan->status !== 'Dibuka') {
            return redirect()->route('lowongan.show', $lowongan->id)
                ->with('error', 'Lowongan ini sudah ditutup.');
        }

        $profil = Auth::user()->profilPelamar;
        if (!$profil) {
            return redirect()->route('pelamar.profil.edit')
                ->with('error', 'Silakan lengkapi profil pelamar terlebih dahulu.');
        }

        $sudahMelamar = Lamaran::where('lowongan_id', $lowongan->id)
            ->where('pelamar_id', $profil->id)
            ->exists();

        if ($sudahMelamar) {
            return redirect()->route('lowongan.show', $lowongan->id)
                ->with('error', 'Anda sudah mengirimkan lamaran untuk posisi ini.');
        }

        $validated = $request->validate([
            'berkas_cv_dikirim' => [
                $profil->berkas_cv ? 'nullable' : 'required',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
            'catatan_pelamar' => ['nullable', 'string'],
        ], [
            'berkas_cv_dikirim.required' => 'Anda belum memiliki CV default di profil. Silakan unggah berkas CV (PDF).',
        ]);

        // Tentukan berkas_cv_dikirim: pakai unggahan baru atau salin dari CV profil
        if ($request->hasFile('berkas_cv_dikirim')) {
            $cvDikirimPath = $request->file('berkas_cv_dikirim')->store('lamaran-cv', 'public');
        } else {
            $salinanPath = 'lamaran-cv/' . Str::uuid() . '.pdf';
            if (Storage::disk('public')->exists($profil->berkas_cv)) {
                Storage::disk('public')->copy($profil->berkas_cv, $salinanPath);
                $cvDikirimPath = $salinanPath;
            } else {
                $cvDikirimPath = $profil->berkas_cv;
            }
        }

        Lamaran::create([
            'lowongan_id' => $lowongan->id,
            'pelamar_id' => $profil->id,
            'berkas_cv_dikirim' => $cvDikirimPath,
            'catatan_pelamar' => $validated['catatan_pelamar'] ?? null,
            'status_seleksi' => 'Terkirim',
        ]);

        return redirect()->route('pelamar.riwayat')
            ->with('success', 'Lamaran berhasil dikirim untuk posisi ' . $lowongan->judul_posisi . '.');
    }

    public function riwayat(): View
    {
        $profil = Auth::user()->profilPelamar;

        $lamarans = $profil
            ? Lamaran::with(['lowongan.instansi'])
                ->where('pelamar_id', $profil->id)
                ->latest()
                ->get()
            : collect();

        return view('pelamar.riwayat', compact('lamarans'));
    }
}