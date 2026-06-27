<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            // id_pengajuan menggunakan auto increment bawaan Laravel (id)
            $table->id(); 
            
            // Atribut Transaksional & Akademik
            $table->date('tgl_pengajuan'); 
            $table->string('mata_kuliah');
            $table->string('kelompok_matkul'); // Contoh: A, B, atau Reguler/Karyawan

            // Relasi Utama (Foreign Keys) - Diubah ke string(20) mengarah ke no_induk
            $table->string('user_id', 20); // Dosen pengaju
            $table->foreign('user_id')->references('no_induk')->on('users')->onDelete('cascade');
            
            // --- ATRIBUT YANG DIUBAH / DITAMBAHKAN ---
            // Mengganti foreignId tunggal menjadi array (JSON) untuk checkbox multi-lab
            $table->json('lab_ids'); 
            // Menambahkan kolom level_akses hasil kalkulasi otomatis (Low/Medium/High)
            $table->enum('level_akses', ['Low', 'Medium', 'High'])->nullable();
            // -----------------------------------------
            
            // Relasi ke Software dibuat Nullable untuk mendukung fitur 'software_lain'
            $table->foreignId('software_id')->nullable()->constrained('software')->onDelete('set null'); 
            $table->string('versi_requested')->nullable(); // Versi terpilih jika software terdaftar

            // Atribut Pengecualian (Kondisional jika software/versi belum ada di database)
            $table->string('software_lain')->nullable(); 
            $table->string('versi_lain')->nullable();

            // Atribut Proses Bisnis & Persetujuan (Supervisor)
            $table->enum('status_persetujuan', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_spv')->nullable(); // Alasan jika ditolak

            // Atribut Penugasan Teknis (Otomatis terisi saat disetujui SPV berdasarkan penjaga lab) - Diubah ke string(20) mengarah ke no_induk
            $table->string('tugaskan_admin', 20)->nullable();
            $table->foreign('tugaskan_admin')->references('no_induk')->on('users')->onDelete('set null');
            
            $table->date('tgl_penugasan')->nullable();

            // Atribut Eksekusi Lapangan (Admin)
            $table->enum('status_progress', ['progress', 'terinstal', 'gagal_terinstal'])->nullable();
            $table->string('dokumentasi')->nullable(); // Berisi URL Google Drive bukti instalasi
            $table->text('catatan_admin')->nullable(); // Alasan jika gagal terinstal

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};