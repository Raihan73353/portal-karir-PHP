<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfilPelamar extends Model
{
    protected $table = 'profil_pelamars';

    protected $fillable = [
        'user_id',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_domisili',
        'jenjang_pendidikan',
        'asal_sekolah_kampus',
        'ringkasan_profil',
        'daftar_keahlian',
        'berkas_cv',
        'tautan_portofolio',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lamarans(): HasMany
    {
        return $this->hasMany(Lamaran::class, 'pelamar_id');
    }
}