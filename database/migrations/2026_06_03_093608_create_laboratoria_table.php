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
        Schema::create('laboratoriums', function (Blueprint $table) {
            $table->id(); // Auto increment ID bawaan Laravel
            $table->string('no_lab')->unique(); // Contoh: LAB01, LAB02 (tidak boleh kembar)
            $table->integer('jumlah_pc'); // Jumlah komputer di dalam lab tersebut
            
            // --- ATRIBUT YANG DISESUAIKAN UNTUK FORM & JAVASCRIPT ---
            // Diubah ke JSON agar bisa menyimpan data array dari checkbox spesifikasi
            $table->json('spesifikasi'); 
            
            // Diubah ke integer/string agar sinkron dengan output JavaScript (1, 2, 3)
            $table->integer('level'); 

            // --- ATRIBUT BARU UNTUK ALUR PERSETUJUAN SUPERVISOR ---
            // Otomatis 'pending' saat admin membuat pengajuan baru
            $table->string('status')->default('pending'); // Nilai: 'pending', 'approved', 'rejected'
            // -----------------------------------------------------

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratoriums');
    }
};