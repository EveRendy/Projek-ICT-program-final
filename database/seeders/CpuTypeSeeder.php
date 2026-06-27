<?php

namespace Database\Seeders;

use App\Models\Hardware;
use Illuminate\Database\Seeder;

class CpuTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Get Intel and AMD brands
        $intel = Hardware::where('category', 'cpu')->where('type', 'brand')->where('name', 'Intel')->first();
        $amd = Hardware::where('category', 'cpu')->where('type', 'brand')->where('name', 'AMD')->first();

        if (!$intel || !$amd) {
            $this->command->info('Intel or AMD brand not found!');
            return;
        }

        // CPU Types to add
        $cpuTypes = ['Entry Level', 'Mid Range', 'High End', 'Enthusiast'];

        foreach ($cpuTypes as $type) {
            // Check if already exists for Intel
            $existsIntel = Hardware::where('parent_id', $intel->id)
                ->where('category', 'cpu')
                ->where('type', 'type')
                ->where('name', $type)
                ->exists();

            if (!$existsIntel) {
                Hardware::create([
                    'parent_id' => $intel->id,
                    'category' => 'cpu',
                    'type' => 'type',
                    'name' => $type
                ]);
            }

            // Check if already exists for AMD
            $existsAmd = Hardware::where('parent_id', $amd->id)
                ->where('category', 'cpu')
                ->where('type', 'type')
                ->where('name', $type)
                ->exists();

            if (!$existsAmd) {
                Hardware::create([
                    'parent_id' => $amd->id,
                    'category' => 'cpu',
                    'type' => 'type',
                    'name' => $type
                ]);
            }
        }

        $this->command->info('CPU types added successfully!');
    }
}
