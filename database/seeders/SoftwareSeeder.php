<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Software;

class SoftwareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $softwares = [
            ['id_software' => 'SW001', 'nama_software' => 'Microsoft Office', 'category' => 'Productivity', 'versi' => ['2019', '2021', '365'], 'keterangan' => 1],
            ['id_software' => 'SW002', 'nama_software' => 'Adobe Photoshop', 'category' => 'Design', 'versi' => ['CC 2023', 'CC 2024'], 'keterangan' => 3],
            ['id_software' => 'SW003', 'nama_software' => 'Visual Studio Code', 'category' => 'Development', 'versi' => ['1.80', '1.85', '1.90'], 'keterangan' => 1],
            ['id_software' => 'SW004', 'nama_software' => 'IntelliJ IDEA', 'category' => 'Development', 'versi' => ['2023.3', '2024.1'], 'keterangan' => 2],
            ['id_software' => 'SW005', 'nama_software' => 'Python', 'category' => 'Development', 'versi' => ['3.10', '3.11', '3.12'], 'keterangan' => 1],
            ['id_software' => 'SW006', 'nama_software' => 'MySQL', 'category' => 'Database', 'versi' => ['8.0', '8.4'], 'keterangan' => 2],
            ['id_software' => 'SW007', 'nama_software' => 'Adobe Illustrator', 'category' => 'Design', 'versi' => ['CC 2023', 'CC 2024'], 'keterangan' => 3],
            ['id_software' => 'SW008', 'nama_software' => 'Figma', 'category' => 'Design', 'versi' => ['Web', 'Desktop'], 'keterangan' => 2],
            ['id_software' => 'SW009', 'nama_software' => 'Git', 'category' => 'Development', 'versi' => ['2.40', '2.45'], 'keterangan' => 1],
            ['id_software' => 'SW010', 'nama_software' => 'Docker', 'category' => 'Development', 'versi' => ['24.0', '25.0'], 'keterangan' => 2],
            ['id_software' => 'SW011', 'nama_software' => 'Android Studio', 'category' => 'Development', 'versi' => ['Hedgehog', 'Iguana', 'Jellyfish'], 'keterangan' => 2],
            ['id_software' => 'SW012', 'nama_software' => 'Xcode', 'category' => 'Development', 'versi' => ['15', '16'], 'keterangan' => 3],
            ['id_software' => 'SW013', 'nama_software' => 'Unity', 'category' => 'Game Dev', 'versi' => ['2022 LTS', '2023'], 'keterangan' => 3],
            ['id_software' => 'SW014', 'nama_software' => 'Unreal Engine', 'category' => 'Game Dev', 'versi' => ['5.3', '5.4'], 'keterangan' => 3],
            ['id_software' => 'SW015', 'nama_software' => 'Wireshark', 'category' => 'Networking', 'versi' => ['4.0', '4.2'], 'keterangan' => 2],
            ['id_software' => 'SW016', 'nama_software' => 'Cisco Packet Tracer', 'category' => 'Networking', 'versi' => ['8.2', '8.3'], 'keterangan' => 2],
            ['id_software' => 'SW017', 'nama_software' => 'Nmap', 'category' => 'Security', 'versi' => ['7.93', '7.94'], 'keterangan' => 2],
            ['id_software' => 'SW018', 'nama_software' => 'Metasploit', 'category' => 'Security', 'versi' => ['6.3', '6.4'], 'keterangan' => 3],
            ['id_software' => 'SW019', 'nama_software' => 'Tableau', 'category' => 'Data Science', 'versi' => ['2023.3', '2024.1'], 'keterangan' => 2],
            ['id_software' => 'SW020', 'nama_software' => 'R Studio', 'category' => 'Data Science', 'versi' => ['2023.06', '2024.04'], 'keterangan' => 2],
        ];

        foreach ($softwares as $software) {
            Software::create($software);
        }
    }
}