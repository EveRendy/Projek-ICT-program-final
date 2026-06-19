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
        Schema::table('users', function (Blueprint $table) {
            // Flag untuk mendeteksi apakah dosen sudah lengkapi profil pertama kali
            $table->boolean('is_first_login')->default(false)->after('role');
            // Buat no_hp dan nama nullable agar dosen bisa dibuat hanya dengan email
            $table->string('nama', 100)->nullable()->change();
            $table->string('no_hp', 15)->nullable()->change();
            // no_induk tidak bisa nullable karena primary key, tapi default-kan string kosong sementara
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_first_login');
            $table->string('nama', 100)->nullable(false)->change();
            $table->string('no_hp', 15)->nullable(false)->change();
        });
    }
};
