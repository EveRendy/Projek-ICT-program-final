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
        // Step 1: Hapus data duplikat - simpan yang terbaru saja
        DB::statement('
            DELETE lt1 FROM license_trackings lt1
            INNER JOIN (
                SELECT laboratorium_id, software_id, pc_number, MAX(id) as max_id
                FROM license_trackings
                GROUP BY laboratorium_id, software_id, pc_number
            ) lt2 ON lt1.laboratorium_id = lt2.laboratorium_id 
                AND lt1.software_id = lt2.software_id 
                AND lt1.pc_number = lt2.pc_number 
                AND lt1.id < lt2.max_id
        ');

        // Step 2: Tambahkan unique index
        Schema::table('license_trackings', function (Blueprint $table) {
            $table->unique(['laboratorium_id', 'software_id', 'pc_number'], 'license_trackings_unique_lab_software_pc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_trackings', function (Blueprint $table) {
            $table->dropUnique('license_trackings_unique_lab_software_pc');
        });
    }
};
