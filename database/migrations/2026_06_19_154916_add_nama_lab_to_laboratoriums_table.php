<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laboratoriums', function (Blueprint $table) {
            // Tambah kolom nama_lab setelah no_lab, nullable agar data lama tidak error
            $table->string('nama_lab')->nullable()->after('no_lab');
        });
    }

    public function down(): void
    {
        Schema::table('laboratoriums', function (Blueprint $table) {
            $table->dropColumn('nama_lab');
        });
    }
};
