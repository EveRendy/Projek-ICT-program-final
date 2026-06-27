<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hardware;

class HardwareSeeder extends Seeder
{
    public function run()
    {
        // === CPU DATA ===
        $cpuData = [
            'Intel' => [
                'Core 2 Duo / Quad' => 10,
                '1st Gen (Nehalem)' => 15,
                '2nd Gen (Sandy Bridge)' => 20,
                '3rd Gen (Ivy Bridge)' => 25,
                '4th Gen (Haswell)' => 30,
                '6th Gen (Skylake)' => 35,
                '7th Gen (Kaby Lake)' => 40,
                '8th Gen (Coffee Lake)' => 45,
                '9th Gen (Coffee Lake Refresh)' => 50,
                '10th Gen (Comet Lake)' => 60,
                '11th Gen (Rocket Lake)' => 65,
                '12th Gen (Alder Lake)' => 75,
                '13th Gen (Raptor Lake)' => 85,
                '14th Gen (Raptor Lake Refresh)' => 90,
                'Core Ultra (Meteor Lake)' => 88,
            ],
            'AMD' => [
                'Athlon / Phenom' => 10,
                'FX Series' => 20,
                'A-Series (APU)' => 25,
                'Ryzen 1000 Series (Zen)' => 40,
                'Ryzen 2000 Series (Zen+)' => 45,
                'Ryzen 3000 Series (Zen 2)' => 55,
                'Ryzen 4000 Series (Zen 2 APU)' => 50,
                'Ryzen 5000 Series (Zen 3)' => 70,
                'Ryzen 6000 Series (Zen 3+)' => 75,
                'Ryzen 7000 Series (Zen 4)' => 85,
                'Ryzen 8000 Series (Zen 4 APU)' => 82,
                'Ryzen 9000 Series (Zen 5)' => 95,
            ]
        ];

        foreach ($cpuData as $brand => $generations) {
            $brandNode = Hardware::create([
                'category' => 'cpu',
                'type' => 'brand',
                'name' => $brand,
            ]);

            foreach ($generations as $genName => $score) {
                Hardware::create([
                    'parent_id' => $brandNode->id,
                    'category' => 'cpu',
                    'type' => 'generation',
                    'name' => $genName,
                    'base_score' => $score,
                ]);
            }
        }

        // === VGA DATA ===
        $vgaData = [
            'NVIDIA' => [
                'GT 700 Series' => 10,
                'GT 1000 Series' => 15,
                'GTX 700 Series' => 25,
                'GTX 900 Series' => 35,
                'GTX 1000 Series (Pascal)' => 50,
                'GTX 1600 Series (Turing)' => 55,
                'RTX 2000 Series (Turing)' => 65,
                'RTX 3000 Series (Ampere)' => 80,
                'RTX 4000 Series (Ada Lovelace)' => 95,
                'RTX 5000 Series (Blackwell)' => 105,
            ],
            'AMD' => [
                'Radeon R7/R9 200 Series' => 20,
                'Radeon R7/R9 300 Series' => 25,
                'Radeon RX 400 Series' => 35,
                'Radeon RX 500 Series' => 40,
                'Radeon RX Vega Series' => 50,
                'Radeon RX 5000 Series (RDNA)' => 60,
                'Radeon RX 6000 Series (RDNA 2)' => 75,
                'Radeon RX 7000 Series (RDNA 3)' => 90,
            ],
            'Intel' => [
                'Arc A-Series (Alchemist)' => 65,
                'Arc B-Series (Battlemage)' => 80,
            ]
        ];

        foreach ($vgaData as $brand => $seriesList) {
            $brandNode = Hardware::create([
                'category' => 'vga',
                'type' => 'brand',
                'name' => $brand,
            ]);

            foreach ($seriesList as $seriesName => $score) {
                Hardware::create([
                    'parent_id' => $brandNode->id,
                    'category' => 'vga',
                    'type' => 'series',
                    'name' => $seriesName,
                    'base_score' => $score,
                ]);
            }
        }
    }
}
