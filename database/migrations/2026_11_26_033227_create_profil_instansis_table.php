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
    Schema::create('profil_instansis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('nama_lembaga');
        $table->string('jenis_lembaga')->nullable(); // Contoh: Perusahaan, Yayasan, Kampus, UMKM
        $table->text('deskripsi')->nullable();
        $table->text('alamat_lengkap')->nullable();
        $table->string('kota')->nullable();
        $table->string('tautan_situs')->nullable();
        $table->string('logo')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_instansis');
    }
};
