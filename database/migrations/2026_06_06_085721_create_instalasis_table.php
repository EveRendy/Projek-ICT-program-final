<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instalasi', function (Blueprint $table) {
            $table->id();
            
            // Kolom Foreign Key custom
            $table->string('id_software'); 
            $table->string('no_lab');      
            $table->string('diinstal_oleh')->nullable(); // Boleh null jika admin terhapus, riwayat tetap ada
            
            // Informasi Tracker Lisensi
            $table->enum('status_lisensi', ['free_license', 'license_active', 'license_expired'])->default('free_license');
            $table->date('tgl_aktif')->nullable();
            $table->date('tgl_expired')->nullable();
            
            $table->timestamps();

            // Mendefinisikan aturan Foreign Key ke masing-masing tabel referensi
            // Pastikan kolom id_software, no_lab, dan no_induk di tabel master masing-masing sudah diset unique() atau primary()
            $table->foreign('id_software')->references('id_software')->on('software')->onDelete('cascade');
            $table->foreign('no_lab')->references('no_lab')->on('laboratoriums')->onDelete('cascade');
            
            // Jika user/admin dihapus, set nilai diinstal_oleh menjadi null (riwayat instalasi tidak hilang)
            $table->foreign('diinstal_oleh')->references('no_induk')->on('users')->onDelete('set null'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instalasis');
    }
};