<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Laboratorium;

class LaboratoriumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labs = [
            ['no_lab' => 'LAB01', 'nama_lab' => 'Laboratorium Komputer 1', 'jumlah_pc' => 37, 'user_id' => 'ADM001', 'level' => '1', 'spesifikasi' => ['CPU Intel i5', 'RAM 16GB', 'SSD 512GB']],
            ['no_lab' => 'LAB02', 'nama_lab' => 'Laboratorium Komputer 2', 'jumlah_pc' => 37, 'user_id' => 'ADM002', 'level' => '2', 'spesifikasi' => ['CPU Intel i5', 'RAM 16GB', 'SSD 512GB']],
            ['no_lab' => 'LAB03', 'nama_lab' => 'Laboratorium Komputer 3', 'jumlah_pc' => 37, 'user_id' => 'ADM003', 'level' => '3', 'spesifikasi' => ['CPU Intel i7', 'RAM 32GB', 'SSD 1TB']],
            ['no_lab' => 'LAB04', 'nama_lab' => 'Artificial Intelligence', 'jumlah_pc' => 37, 'user_id' => 'ADM004', 'level' => '3', 'spesifikasi' => ['CPU Intel i9', 'RAM 64GB', 'SSD 2TB', 'GPU RTX 4090']],
            ['no_lab' => 'LAB05', 'nama_lab' => 'Data Science', 'jumlah_pc' => 37, 'user_id' => 'ADM005', 'level' => '3', 'spesifikasi' => ['CPU Intel i7', 'RAM 32GB', 'SSD 1TB']],
            ['no_lab' => 'LAB06', 'nama_lab' => 'Networking', 'jumlah_pc' => 37, 'user_id' => 'ADM006', 'level' => '1', 'spesifikasi' => ['CPU Intel i5', 'RAM 16GB', 'SSD 512GB']],
            ['no_lab' => 'LAB07', 'nama_lab' => 'Multimedia', 'jumlah_pc' => 37, 'user_id' => 'ADM007', 'level' => '3', 'spesifikasi' => ['CPU Intel i7', 'RAM 32GB', 'SSD 1TB', 'GPU RTX 4070']],
            ['no_lab' => 'LAB08', 'nama_lab' => 'Game Development', 'jumlah_pc' => 37, 'user_id' => 'ADM008', 'level' => '3', 'spesifikasi' => ['CPU Intel i9', 'RAM 64GB', 'SSD 2TB', 'GPU RTX 4080']],
            ['no_lab' => 'LAB09', 'nama_lab' => 'Mobile Development', 'jumlah_pc' => 37, 'user_id' => 'ADM009', 'level' => '2', 'spesifikasi' => ['CPU Intel i7', 'RAM 32GB', 'SSD 1TB']],
            ['no_lab' => 'LAB10', 'nama_lab' => 'Cyber Security', 'jumlah_pc' => 37, 'user_id' => 'ADM010', 'level' => '2', 'spesifikasi' => ['CPU Intel i7', 'RAM 32GB', 'SSD 1TB']],
        ];

        foreach ($labs as $lab) {
            Laboratorium::create(array_merge($lab, ['status' => 'approved', 'is_active' => true]));
        }
    }
}