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
        Schema::create('software', function (Blueprint $table) {
            $table->id();
            $table->string('id_software')->unique(); // Contoh: ADB01, AND01
            $table->string('nama_software');
            $table->json('versi');                   // Menyimpan banyak versi dalam bentuk JSON/Array
            $table->integer('keterangan');           // Level software (1, 2, atau 3) untuk cek kompatibilitas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('software');
    }
};