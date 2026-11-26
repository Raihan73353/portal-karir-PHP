<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfilInstansi extends Model
{
    protected $table = 'profil_instansis';

    protected $fillable = [
        'user_id',
        'nama_lembaga',
        'jenis_lembaga',
        'deskripsi',
        'alamat_lengkap',
        'kota',
        'tautan_situs',
        'logo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lowongans(): HasMany
    {
        return $this->hasMany(Lowongan::class, 'instansi_id');
    }
}