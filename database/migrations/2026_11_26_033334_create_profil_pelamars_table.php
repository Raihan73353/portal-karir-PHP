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
        Schema::create('profil_pelamars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('jenjang_pendidikan')->nullable(); // Contoh: SMK, D3, S1
            $table->string('asal_sekolah_kampus')->nullable();
            $table->text('ringkasan_profil')->nullable();
            $table->text('daftar_keahlian')->nullable(); // Contoh: "Laravel, Desain UI, Excel"
            $table->string('berkas_cv')->nullable();
            $table->string('tautan_portofolio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_pelamars');
    }
};
