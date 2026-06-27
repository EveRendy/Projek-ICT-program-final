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
                'Core 2 Duo / Quad',
                '1st Gen (Nehalem)',
                '2nd Gen (Sandy Bridge)',
                '3rd Gen (Ivy Bridge)',
                '4th Gen (Haswell)',
                '6th Gen (Skylake)',
                '7th Gen (Kaby Lake)',
                '8th Gen (Coffee Lake)',
                '9th Gen (Coffee Lake Refresh)',
                '10th Gen (Comet Lake)',
                '11th Gen (Rocket Lake)',
                '12th Gen (Alder Lake)',
                '13th Gen (Raptor Lake)',
                '14th Gen (Raptor Lake Refresh)',
                'Core Ultra (Meteor Lake)',
            ],
            'AMD' => [
                'Athlon / Phenom',
                'FX Series',
                'A-Series (APU)',
                'Ryzen 1000 Series (Zen)',
                'Ryzen 2000 Series (Zen+)',
                'Ryzen 3000 Series (Zen 2)',
                'Ryzen 4000 Series (Zen 2 APU)',
                'Ryzen 5000 Series (Zen 3)',
                'Ryzen 6000 Series (Zen 3+)',
                'Ryzen 7000 Series (Zen 4)',
                'Ryzen 8000 Series (Zen 4 APU)',
                'Ryzen 9000 Series (Zen 5)',
            ]
        ];

        foreach ($cpuData as $brand => $generations) {
            $brandNode = Hardware::create([
                'category' => 'cpu',
                'type' => 'brand',
                'name' => $brand,
            ]);

            foreach ($generations as $genName) {
                Hardware::create([
                    'parent_id' => $brandNode->id,
                    'category' => 'cpu',
                    'type' => 'generation',
                    'name' => $genName,
                ]);
            }
        }

        // === VGA DATA ===
        $vgaData = [
            'NVIDIA' => [
                'GT Series',
                'GTX Series',
                'RTX Series',
            ],
            'AMD' => [
                'Radeon R7',
                'Radeon RX Vega Series',
                'Radeon RX Series',
            ],
            'Intel' => [
                'Arc A-Series (Alchemist)',
                'Arc B-Series (Battlemage)',
            ]
        ];

        foreach ($vgaData as $brand => $seriesList) {
            $brandNode = Hardware::create([
                'category' => 'vga',
                'type' => 'brand',
                'name' => $brand,
            ]);

            foreach ($seriesList as $seriesName) {
                Hardware::create([
                    'parent_id' => $brandNode->id,
                    'category' => 'vga',
                    'type' => 'series',
                    'name' => $seriesName,
                ]);
            }
        }
    }
}
