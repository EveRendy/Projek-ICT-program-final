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
        $dosens = [
            ['no_induk' => 'DSN001', 'nama' => 'Rudi Hartono', 'email' => 'rudi.hartono@lab.com', 'password' => Hash::make('dosen'), 'no_hp' => '081312345671'],
            ['no_induk' => 'DSN002', 'nama' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@lab.com', 'password' => Hash::make('dosen'), 'no_hp' => '081312345672'],
            ['no_induk' => 'DSN003', 'nama' => 'Agus Supriyanto', 'email' => 'agus.supriyanto@lab.com', 'password' => Hash::make('dosen'), 'no_hp' => '081312345673'],
            ['no_induk' => 'DSN004', 'nama' => 'Tri Handayani', 'email' => 'tri.handayani@lab.com', 'password' => Hash::make('dosen'), 'no_hp' => '081312345674'],
            ['no_induk' => 'DSN005', 'nama' => 'Yusuf Maulana', 'email' => 'yusuf.maulana@lab.com', 'password' => Hash::make('dosen'), 'no_hp' => '081312345675'],
        ];

        foreach ($dosens as $dosen) {
            User::create(array_merge($dosen, ['role' => 'dosen']));
        }
    }
    
}