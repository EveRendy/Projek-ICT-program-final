<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Wajib panggil Model User di sini
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'no_induk' => 'ADM001', // Sesuaikan format nomor induk kamu
            'nama'     => 'Admin',
            'email'    => 'admin@lab.com',
            'password' => Hash::make('admin'), // Mengamankan password
            'no_hp'    => '081234567891',
            'role'     => 'admin', // Harus pas dengan salah satu isi ENUM
        ]);
    }
    
}
