<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LowonganController extends Controller
{
    public function index(Request $request): View
    {
        $lowongans = Lowongan::with(['instansi', 'kategori'])
            ->latest()
            ->paginate(9);

        return view('lowongan.index', compact('lowongans'));
    }

    public function show(Lowongan $lowongan): View
    {
        $lowongan->load(['instansi', 'kategori']);

        $lamaranSaya = null;

        if (Auth::check() && Auth::user()->peran === 'pelamar') {
            $profilPelamar = Auth::user()->profilPelamar;

            if ($profilPelamar) {
                $lamaranSaya = Lamaran::where('lowongan_id', $lowongan->id)
                    ->where('pelamar_id', $profilPelamar->id)
                    ->first();
            }
        }

        return view('lowongan.show', compact('lowongan', 'lamaranSaya'));
    }
}