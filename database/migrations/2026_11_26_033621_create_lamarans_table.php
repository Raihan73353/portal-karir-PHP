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
        Schema::create('lamarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lowongan_id')->constrained('lowongans')->onDelete('cascade');
            $table->foreignId('pelamar_id')->constrained('profil_pelamars')->onDelete('cascade');
            $table->string('berkas_cv_dikirim');
            $table->text('catatan_pelamar')->nullable();
            $table->enum('status_seleksi', ['Terkirim','Sedang Ditinjau','Wawancara','Diterima','Ditolak'])->default('Terkirim');
            $table->text('umpan_balik_admin')->nullable();
            $table->timestamps();

    $table->unique(['lowongan_id', 'pelamar_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lamarans');
    }
};
