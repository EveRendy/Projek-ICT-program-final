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
        // Update semua level lab yang lebih dari 3 menjadi 3
        \App\Models\Laboratorium::whereRaw('CAST(level AS UNSIGNED) > 3')->update(['level' => '3']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu reverse karena ini adalah perbaikan data
    }
};
