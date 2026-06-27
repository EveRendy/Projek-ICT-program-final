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
        $admins = [
            ['no_induk' => 'ADM001', 'nama' => 'Ahmad Rizki', 'email' => 'ahmad.rizki@lab.com', 'password' => Hash::make('admin'), 'no_hp' => '081234567891'],
            ['no_induk' => 'ADM002', 'nama' => 'Siti Aminah', 'email' => 'siti.aminah@lab.com', 'password' => Hash::make('admin'), 'no_hp' => '081234567892'],
            ['no_induk' => 'ADM003', 'nama' => 'Budi Santoso', 'email' => 'budi.santoso@lab.com', 'password' => Hash::make('admin'), 'no_hp' => '081234567893'],
            ['no_induk' => 'ADM004', 'nama' => 'Dewi Lestari', 'email' => 'dewi.lestari@lab.com', 'password' => Hash::make('admin'), 'no_hp' => '081234567894'],
            ['no_induk' => 'ADM005', 'nama' => 'Eko Prasetyo', 'email' => 'eko.prasetyo@lab.com', 'password' => Hash::make('admin'), 'no_hp' => '081234567895'],
            ['no_induk' => 'ADM006', 'nama' => 'Fitri Handayani', 'email' => 'fitri.handayani@lab.com', 'password' => Hash::make('admin'), 'no_hp' => '081234567896'],
            ['no_induk' => 'ADM007', 'nama' => 'Gilang Permana', 'email' => 'gilang.permana@lab.com', 'password' => Hash::make('admin'), 'no_hp' => '081234567897'],
            ['no_induk' => 'ADM008', 'nama' => 'Hana Putri', 'email' => 'hana.putri@lab.com', 'password' => Hash::make('admin'), 'no_hp' => '081234567898'],
            ['no_induk' => 'ADM009', 'nama' => 'Irfan Maulana', 'email' => 'irfan.maulana@lab.com', 'password' => Hash::make('admin'), 'no_hp' => '081234567899'],
            ['no_induk' => 'ADM010', 'nama' => 'Joko Susilo', 'email' => 'joko.susilo@lab.com', 'password' => Hash::make('admin'), 'no_hp' => '081234567800'],
        ];

        foreach ($admins as $admin) {
            User::create(array_merge($admin, ['role' => 'admin']));
        }
    }
    
}
