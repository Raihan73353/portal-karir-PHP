<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lowongan extends Model
{
    protected $table = 'lowongans';

    protected $fillable = [
        'instansi_id',
        'kategori_id',
        'judul_posisi',
        'tipe_lowongan',
        'model_kerja',
        'lokasi_penempatan',
        'uraian_tugas',
        'persyaratan',
        'kompensasi_min',
        'kompensasi_max',
        'kuota',
        'tanggal_ditutup',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'kompensasi_min' => 'integer',
            'kompensasi_max' => 'integer',
            'kuota' => 'integer',
            'tanggal_ditutup' => 'date',
        ];
    }

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(ProfilInstansi::class, 'instansi_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriLowongan::class, 'kategori_id');
    }

    public function lamarans(): HasMany
    {
        return $this->hasMany(Lamaran::class, 'lowongan_id');
    }

    public function getRentangKompensasiAttribute(): string
    {
        if (!is_null($this->kompensasi_min) && !is_null($this->kompensasi_max)) {
            return 'Rp ' . number_format($this->kompensasi_min, 0, ',', '.') . ' - Rp ' . number_format($this->kompensasi_max, 0, ',', '.');
        }
        if (!is_null($this->kompensasi_min)) {
            return 'Mulai Rp ' . number_format($this->kompensasi_min, 0, ',', '.');
        }
        if (!is_null($this->kompensasi_max)) {
            return 'Hingga Rp ' . number_format($this->kompensasi_max, 0, ',', '.');
        }
        return 'Kompensasi dirahasiakan / Negosiasi';
    }
}