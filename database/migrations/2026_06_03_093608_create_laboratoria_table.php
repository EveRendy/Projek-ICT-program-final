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
            $table->integer('level');           // Contoh: 1, 2, 3 (skala spek komputer)
            $table->integer('jumlah_pc');       // Jumlah komputer di dalam lab tersebut
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