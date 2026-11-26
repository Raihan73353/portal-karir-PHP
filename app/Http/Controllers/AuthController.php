<?php

namespace App\Http\Controllers;

use App\Models\ProfilInstansi;
use App\Models\ProfilPelamar;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->peran === 'admin') {
                return redirect()->intended(route('admin.lowongan.index'))
                    ->with('success', 'Selamat datang kembali, Admin ' . $user->name . '.');
            }

            return redirect()->intended(route('lowongan.index'))
                ->with('success', 'Berhasil masuk sebagai ' . $user->name . '.');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'peran' => ['required', 'in:pelamar,admin'],
            'nama_lembaga' => ['required_if:peran,admin', 'nullable', 'string', 'max:255'],
            'asal_sekolah_kampus' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nama_lembaga.required_if' => 'Nama lembaga wajib diisi jika mendaftar sebagai Admin Instansi.',
        ]);

        $user = DB::transaction(function () use ($validated) {
            $newUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'no_telepon' => $validated['no_telepon'] ?? null,
                'peran' => $validated['peran'],
                'password' => $validated['password'], // Otomatis di-hash oleh cast 'password' => 'hashed' di Model User
            ]);

            if ($newUser->peran === 'pelamar') {
                ProfilPelamar::create([
                    'user_id' => $newUser->id,
                    'asal_sekolah_kampus' => $validated['asal_sekolah_kampus'] ?? null,
                ]);
            } elseif ($newUser->peran === 'admin') {
                ProfilInstansi::create([
                    'user_id' => $newUser->id,
                    'nama_lembaga' => $validated['nama_lembaga'],
                ]);
            }

            return $newUser;
        });

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->peran === 'admin') {
            return redirect()->route('admin.instansi.index')
                ->with('success', 'Akun Admin dan profil instansi awal berhasil dibuat. Silakan lengkapi detail instansi Anda.');
        }

        return redirect()->route('pelamar.profil.edit')
            ->with('success', 'Pendaftaran berhasil! Silakan lengkapi biodata dan unggah CV Anda sebelum melamar.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah keluar dari sistem.');
    }
}