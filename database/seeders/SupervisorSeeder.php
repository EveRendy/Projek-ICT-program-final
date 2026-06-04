<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Wajib panggil Model User di sini
use Illuminate\Support\Facades\Hash;

class SupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'no_induk' => 'SPV001', // Sesuaikan format nomor induk kamu
            'nama'     => 'Supervisor',
            'email'    => 'supervisor@lab.com',
            'password' => Hash::make('supervisor'), // Mengamankan password
            'no_hp'    => '081234567890',
            'role'     => 'supervisor', // Harus pas dengan salah satu isi ENUM
        ]);
    }
    
}
