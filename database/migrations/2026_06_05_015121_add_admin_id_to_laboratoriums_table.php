<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laboratoriums', function (Blueprint $table) {
            // Menambahkan foreign key yang menyambung ke id di tabel users
            // nullable() digunakan agar jika data lab lama kosong tidak error, constrained('users') mengunci relasi ke tabel users
            $table->string('user_id', 20)->nullable()->after('id');
            $table->foreign('user_id')->references('no_induk')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('laboratoriums', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};