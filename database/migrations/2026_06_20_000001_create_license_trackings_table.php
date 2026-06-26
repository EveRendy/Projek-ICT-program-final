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
        Schema::create('license_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laboratorium_id')->constrained('laboratoriums')->onDelete('cascade');
            $table->foreignId('software_id')->constrained('software')->onDelete('cascade');
            $table->string('pc_number'); // Contoh: PC01, PC02
            $table->string('license_account')->nullable();
            $table->string('license_password')->nullable();
            $table->string('unique_code')->nullable();
            $table->date('active_date');
            $table->date('expiry_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_trackings');
    }
};
