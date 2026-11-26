<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lamaran extends Model
{
    protected $table = 'lamarans';

    protected $fillable = [
        'lowongan_id',
        'pelamar_id',
        'berkas_cv_dikirim',
        'catatan_pelamar',
        'status_seleksi',
        'umpan_balik_admin',
    ];

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(ProfilPelamar::class, 'pelamar_id');
    }
}