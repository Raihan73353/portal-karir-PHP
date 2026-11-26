<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lowongans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instansi_id')->constrained('profil_instansis')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_lowongans')->onDelete('restrict');
            $table->string('judul_posisi');
            $table->enum('tipe_lowongan', ['Magang', 'Penuh Waktu', 'Paruh Waktu', 'Kontrak']);
            $table->enum('model_kerja', ['Luring', 'Daring', 'Hybrid']);
            $table->string('lokasi_penempatan');
            $table->text('uraian_tugas');
            $table->text('persyaratan');
            $table->bigInteger('kompensasi_min')->nullable();
            $table->bigInteger('kompensasi_max')->nullable();
            $table->integer('kuota')->default(1);
            $table->date('tanggal_ditutup');
            $table->enum('status', ['Dibuka', 'Ditutup'])->default('Dibuka');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongans');
    }
};
