<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Wajib panggil Model User di sini
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'no_induk' => 'DSN001', // Sesuaikan format nomor induk kamu
            'nama'     => 'Dosen',
            'email'    => 'dosen@lab.com',
            'password' => Hash::make('dosen'), // Mengamankan password
            'no_hp'    => '081234567892',
            'role'     => 'dosen', // Harus pas dengan salah satu isi ENUM
        ]);
    }
    
}