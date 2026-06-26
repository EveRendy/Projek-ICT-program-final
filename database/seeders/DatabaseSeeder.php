<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SupervisorSeeder::class,
            AdminSeeder::class,
            DosenSeeder::class,
            LaboratoriumSeeder::class,
            SoftwareSeeder::class,
            HardwareSeeder::class,
        ]);
    }
}
