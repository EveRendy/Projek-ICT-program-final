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
        Schema::create('users', function (Blueprint $table) {
            // no_induk dijadikan primary key dengan panjang maksimal 20 karakter
            $table->string('no_induk', 20)->primary(); 
            
            // nama wajib diisi, panjang maksimal 100 karakter
            $table->string('nama', 100); 
            
            // email panjang maksimal 100 karakter
            $table->string('email', 100)->unique(); 
            
            // password wajib diisi, panjang 100 karakter (cukup untuk hash bcrypt/argon2)
            $table->string('password', 100); 
            
            // no_hp wajib diisi, panjang maksimal 15 karakter
            $table->string('no_hp', 15); 
            
            $table->enum('role', ['supervisor', 'admin', 'dosen']);
            $table->rememberToken(); // Default panjang dari Laravel adalah 100
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            // Sesuaikan dengan panjang email di tabel users
            $table->string('email', 100)->primary(); 
            $table->string('token', 100);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            
            // PENTING: user_id diubah menjadi string(20) agar sesuai dengan primary key 'no_induk' di tabel users
            $table->string('user_id', 20)->nullable()->index(); 
            
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};