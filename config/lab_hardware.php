<?php

/**
 * Konfigurasi Hardware Laboratorium
 *
 * Sistem kalkulasi level menggunakan SCORING MATRIX, bukan nilai maksimum.
 * Level akhir ditentukan dari kombinasi semua komponen secara proporsional.
 *
 * Skema skor:
 *   CPU Tier  : i3/Ryzen3=0 | i5/Ryzen5=1 | i7/Ryzen7=2 | i9/Ryzen9=3 | Ultra 5=2 | Ultra 7=3 | Ultra 9=4
 *   Generasi  : Gen 3-4=0 | Gen 5-7=1 | Gen 8-10=2 | Gen 11-12=3 | Gen 13-14=4 | Core Ultra=5
 *              (Ryzen): 1000-3000=0 | 4000-5000=2 | 7000=4 | 8000-9000=5
 *   RAM       : 4GB=0 | 8GB=1 | 16GB=2 | 32GB=3 | 64GB=4
 *   VGA       : tanpa VGA=0 | entry (GT 710/730/1030)=0 | mid (GTX 1050-1060, RX 570-580)=1
 *              | upper-mid (GTX 1660, RX 6600)=2 | high (RTX series)=3
 *
 * Threshold Level Akhir:
 *   Skor  0–3  = Level 1 (Spesifikasi Rendah / Standar Office)
 *   Skor  4–8  = Level 2 (Spesifikasi Menengah / Programming)
 *   Skor  9+   = Level 3 (Spesifikasi Tinggi / Multimedia & Engineering)
 *
 * Contoh realistis:
 *   i7 Gen 3 + RAM 4GB        = 2+0+0 = 2  → Level 1 ✓
 *   i5 Gen 8 + RAM 8GB        = 1+2+1 = 4  → Level 2 ✓
 *   i7 Gen 10 + RAM 16GB      = 2+2+2 = 6  → Level 2 ✓
 *   i7 Gen 12 + RAM 16GB      = 2+3+2 = 7  → Level 2 ✓
 *   i9 Gen 13 + RAM 32GB      = 3+4+3 = 10 → Level 3 ✓
 *   i9 Gen 13 + RAM 32GB + RTX= 3+4+3+3=13 → Level 3 ✓
 *   Ryzen 5 5000 + RAM 16GB   = 1+2+2 = 5  → Level 2 ✓
 */

return [

    // -------------------------------------------------------------------------
    // CPU TIER SCORES
    // -------------------------------------------------------------------------
    'intel_tiers' => [
        'Intel Core i3'       => 0,
        'Intel Core i5'       => 1,
        'Intel Core i7'       => 2,
        'Intel Core i9'       => 3,
        'Intel Core Ultra 5'  => 2,
        'Intel Core Ultra 7'  => 3,
        'Intel Core Ultra 9'  => 4,
    ],

    'amd_tiers' => [
        'AMD Ryzen 3' => 0,
        'AMD Ryzen 5' => 1,
        'AMD Ryzen 7' => 2,
        'AMD Ryzen 9' => 3,
    ],

    // -------------------------------------------------------------------------
    // GENERASI / SERI — skor mencerminkan usia & arsitektur
    // -------------------------------------------------------------------------
    'intel_generations' => [
        // Gen 3-4 (Ivy Bridge / Haswell, 2012-2013) — sangat tua
        'Intel Gen 3'  => 0,
        'Intel Gen 4'  => 0,
        // Gen 5-7 (Broadwell / Skylake / Kaby Lake, 2014-2017) — tua, masih bisa pakai
        'Intel Gen 5'  => 1,
        'Intel Gen 6'  => 1,
        'Intel Gen 7'  => 1,
        // Gen 8-10 (Coffee Lake / Comet Lake, 2017-2020) — menengah, masih relevan
        'Intel Gen 8'  => 2,
        'Intel Gen 9'  => 2,
        'Intel Gen 10' => 2,
        // Gen 11 (Tiger Lake, 2020) — baik, efisien
        'Intel Gen 11' => 3,
        // Gen 12-14 (Alder Lake / Raptor Lake, 2021-2023) — modern, performa tinggi
        'Intel Gen 12' => 4,
        'Intel Gen 13' => 4,
        'Intel Gen 14' => 4,
        // Core Ultra (Meteor Lake / Arrow Lake, 2024+) — terbaru
        'Intel Core Ultra Series 1' => 5,
        'Intel Core Ultra Series 2' => 5,
    ],

    'amd_series' => [
        // Zen / Zen+ (2017-2018) — tua
        'AMD Ryzen 1000 Series (Zen)'       => 0,
        'AMD Ryzen 2000 Series (Zen+)'      => 0,
        // Zen 2 (2019-2020) — cukup, mirip Intel Gen 8-9
        'AMD Ryzen 3000 Series (Zen 2)'     => 1,
        'AMD Ryzen 4000 Series (Zen 2 APU)' => 2,
        // Zen 3 (2020-2021) — setara Intel Gen 11-12
        'AMD Ryzen 5000 Series (Zen 3)'     => 3,
        // Zen 4 (2022-2023) — modern, setara Intel Gen 13-14
        'AMD Ryzen 7000 Series (Zen 4)'     => 4,
        'AMD Ryzen 8000 Series (Zen 4 APU)' => 4,
        // Zen 5 (2024+) — terbaru
        'AMD Ryzen 9000 Series (Zen 5)'     => 5,
    ],

    // -------------------------------------------------------------------------
    // RAM — bottleneck nyata; 4GB hampir tidak bisa multitasking di 2024
    // -------------------------------------------------------------------------
    'ram_options' => [
        'RAM 4GB'  => 0,   // Sangat terbatas, hanya Office dasar
        'RAM 8GB'  => 1,   // Minimum wajar untuk programming ringan
        'RAM 16GB' => 2,   // Standar 2024 untuk lab programming/multimedia
        'RAM 32GB' => 3,   // Nyaman untuk engineering, VM, rendering
        'RAM 64GB' => 4,   // Workstation — video editing, simulasi
    ],

    // -------------------------------------------------------------------------
    // VGA — skor tambahan, bukan penentu utama level
    // entry VGA tidak menambah skor karena tidak signifikan vs iGPU modern
    // -------------------------------------------------------------------------
    'vga_options' => [
        // Entry lama — tidak lebih baik dari iGPU modern, skor 0
        'GeForce GT 210 (1GB VRAM)'              => 0,
        'GeForce GT 710 (2GB VRAM)'              => 0,
        'GeForce GT 730 (2GB/4GB VRAM)'          => 0,
        'GeForce GT 1030 (2GB VRAM)'             => 0,
        'GeForce GTX 750 Ti (2GB/4GB VRAM)'      => 0,

        // Mid-range lama / entry modern — skor 1
        'GeForce GTX 950 (2GB VRAM)'             => 1,
        'GeForce GTX 960 (2GB/4GB VRAM)'         => 1,
        'GeForce GTX 970 (4GB VRAM)'             => 1,
        'GeForce GTX 1050 (2GB VRAM)'            => 1,
        'GeForce GTX 1050 Ti (4GB VRAM)'         => 1,
        'GeForce GTX 1060 (3GB/6GB VRAM)'        => 1,
        'GeForce GTX 1650 (4GB VRAM)'            => 1,
        'Radeon R7 240 (2GB VRAM)'               => 0,
        'Radeon RX 550 (2GB/4GB VRAM)'           => 0,
        'Radeon RX 570 (4GB/8GB VRAM)'           => 1,
        'Radeon RX 580 (4GB/8GB VRAM)'           => 1,

        // Upper-mid — skor 2
        'GeForce GTX 980 (4GB VRAM)'             => 2,
        'GeForce GTX 1070 (8GB VRAM)'            => 2,
        'GeForce GTX 1080 (8GB VRAM)'            => 2,
        'GeForce GTX 1080 Ti (11GB VRAM)'        => 2,
        'GeForce GTX 1660 (6GB VRAM)'            => 2,
        'GeForce GTX 1660 Super (6GB VRAM)'      => 2,
        'GeForce GTX 1660 Ti (6GB VRAM)'         => 2,
        'Radeon RX 6500 XT (4GB VRAM)'           => 1,
        'Radeon RX 6600 (8GB VRAM)'              => 2,
        'Radeon RX 6600 XT (8GB VRAM)'           => 2,

        // High-end (RTX) — skor 3
        'GeForce RTX 2060 (6GB VRAM)'            => 3,
        'GeForce RTX 2070 (8GB VRAM)'            => 3,
        'GeForce RTX 2080 (8GB VRAM)'            => 3,
        'GeForce RTX 3050 (8GB VRAM)'            => 3,
        'GeForce RTX 3060 (8GB/12GB VRAM)'       => 3,
        'GeForce RTX 3060 Ti (8GB VRAM)'         => 3,
        'GeForce RTX 3070 (8GB VRAM)'            => 3,
        'GeForce RTX 3080 (10GB/12GB VRAM)'      => 3,
        'GeForce RTX 4060 (8GB VRAM)'            => 3,
        'GeForce RTX 4060 Ti (8GB/16GB VRAM)'    => 3,
        'GeForce RTX 4070 (12GB VRAM)'           => 3,
        'GeForce RTX 4080 (16GB VRAM)'           => 3,
        'GeForce RTX 4090 (24GB VRAM)'           => 3,
        'Radeon RX 6700 XT (12GB VRAM)'          => 3,
        'Radeon RX 6800 XT (16GB VRAM)'          => 3,
        'Radeon RX 7600 (8GB VRAM)'              => 3,
        'Radeon RX 7700 XT (12GB VRAM)'          => 3,
        'Radeon RX 7900 XT (20GB VRAM)'          => 3,
    ],

    // -------------------------------------------------------------------------
    // THRESHOLD LEVEL AKHIR (total skor semua komponen)
    // -------------------------------------------------------------------------
    'level_thresholds' => [
        'level_1_max' => 3,   // Skor 0-3 = Level 1
        'level_2_max' => 8,   // Skor 4-8 = Level 2
        // Skor 9+    = Level 3
    ],

];
