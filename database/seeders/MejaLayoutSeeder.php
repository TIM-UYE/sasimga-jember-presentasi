<?php

namespace Database\Seeders;

use App\Models\Meja;
use App\Models\Lantai;
use Illuminate\Database\Seeder;

class MejaLayoutSeeder extends Seeder
{
    public function run(): void
    {
        $lantai1 = Lantai::where('slug', 'lantai-1')->first();
        $lantai2 = Lantai::where('slug', 'lantai-2')->first();

        if (!$lantai1 || !$lantai2) {
            $this->command->warn('Lantai not found. Run migrations first.');
            return;
        }

        // Lantai 1: Meja 1-15
        $lantai1Meja = [
            // Grup A (1-3) - mergeable
            ['nama_meja' => 'Meja 1', 'nomor_meja' => '1', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => 'A', 'is_mergeable' => true, 'pos_x' => 50, 'pos_y' => 50, 'posisi_row' => 'A', 'posisi_col' => 1, 'is_active' => true],
            ['nama_meja' => 'Meja 2', 'nomor_meja' => '2', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => 'A', 'is_mergeable' => true, 'pos_x' => 150, 'pos_y' => 50, 'posisi_row' => 'A', 'posisi_col' => 2, 'is_active' => true],
            ['nama_meja' => 'Meja 3', 'nomor_meja' => '3', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => 'A', 'is_mergeable' => true, 'pos_x' => 250, 'pos_y' => 50, 'posisi_row' => 'A', 'posisi_col' => 3, 'is_active' => true],

            // Grup B (4-8) - mergeable
            ['nama_meja' => 'Meja 4', 'nomor_meja' => '4', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => 'B', 'is_mergeable' => true, 'pos_x' => 50, 'pos_y' => 150, 'posisi_row' => 'B', 'posisi_col' => 1, 'is_active' => true],
            ['nama_meja' => 'Meja 5', 'nomor_meja' => '5', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => 'B', 'is_mergeable' => true, 'pos_x' => 150, 'pos_y' => 150, 'posisi_row' => 'B', 'posisi_col' => 2, 'is_active' => true],
            ['nama_meja' => 'Meja 6', 'nomor_meja' => '6', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => 'B', 'is_mergeable' => true, 'pos_x' => 250, 'pos_y' => 150, 'posisi_row' => 'B', 'posisi_col' => 3, 'is_active' => true],
            ['nama_meja' => 'Meja 7', 'nomor_meja' => '7', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => 'B', 'is_mergeable' => true, 'pos_x' => 350, 'pos_y' => 150, 'posisi_row' => 'B', 'posisi_col' => 4, 'is_active' => true],
            ['nama_meja' => 'Meja 8', 'nomor_meja' => '8', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => 'B', 'is_mergeable' => true, 'pos_x' => 450, 'pos_y' => 150, 'posisi_row' => 'B', 'posisi_col' => 5, 'is_active' => true],

            // Grup C (9-11) - mergeable
            ['nama_meja' => 'Meja 9', 'nomor_meja' => '9', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => 'C', 'is_mergeable' => true, 'pos_x' => 50, 'pos_y' => 250, 'posisi_row' => 'C', 'posisi_col' => 1, 'is_active' => true],
            ['nama_meja' => 'Meja 10', 'nomor_meja' => '10', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => 'C', 'is_mergeable' => true, 'pos_x' => 150, 'pos_y' => 250, 'posisi_row' => 'C', 'posisi_col' => 2, 'is_active' => true],
            ['nama_meja' => 'Meja 11', 'nomor_meja' => '11', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => 'C', 'is_mergeable' => true, 'pos_x' => 250, 'pos_y' => 250, 'posisi_row' => 'C', 'posisi_col' => 3, 'is_active' => true],

            // Standalone meja (12-15) - not mergeable
            ['nama_meja' => 'Meja 12', 'nomor_meja' => '12', 'kategori' => 'vip', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => null, 'is_mergeable' => false, 'pos_x' => 50, 'pos_y' => 350, 'posisi_row' => 'D', 'posisi_col' => 1, 'is_active' => true],
            ['nama_meja' => 'Meja 13', 'nomor_meja' => '13', 'kategori' => 'vip', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => null, 'is_mergeable' => false, 'pos_x' => 150, 'pos_y' => 350, 'posisi_row' => 'D', 'posisi_col' => 2, 'is_active' => true],
            ['nama_meja' => 'Meja 14', 'nomor_meja' => '14', 'kategori' => 'vip', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => null, 'is_mergeable' => false, 'pos_x' => 250, 'pos_y' => 350, 'posisi_row' => 'D', 'posisi_col' => 3, 'is_active' => true],
            ['nama_meja' => 'Meja 15', 'nomor_meja' => '15', 'kategori' => 'vip', 'kapasitas' => 4, 'lantai_id' => $lantai1->id, 'merge_group' => null, 'is_mergeable' => false, 'pos_x' => 350, 'pos_y' => 350, 'posisi_row' => 'D', 'posisi_col' => 4, 'is_active' => true],
        ];

        // Lantai 2: Meja 16-20
        $lantai2Meja = [
            // Meja 16 - standalone
            ['nama_meja' => 'Meja 16', 'nomor_meja' => '16', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai2->id, 'merge_group' => null, 'is_mergeable' => false, 'pos_x' => 50, 'pos_y' => 50, 'posisi_row' => 'E', 'posisi_col' => 1, 'is_active' => true],

            // Grup D (17-19) - mergeable
            ['nama_meja' => 'Meja 17', 'nomor_meja' => '17', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai2->id, 'merge_group' => 'D', 'is_mergeable' => true, 'pos_x' => 150, 'pos_y' => 50, 'posisi_row' => 'E', 'posisi_col' => 2, 'is_active' => true],
            ['nama_meja' => 'Meja 18', 'nomor_meja' => '18', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai2->id, 'merge_group' => 'D', 'is_mergeable' => true, 'pos_x' => 250, 'pos_y' => 50, 'posisi_row' => 'E', 'posisi_col' => 3, 'is_active' => true],
            ['nama_meja' => 'Meja 19', 'nomor_meja' => '19', 'kategori' => 'regular', 'kapasitas' => 4, 'lantai_id' => $lantai2->id, 'merge_group' => 'D', 'is_mergeable' => true, 'pos_x' => 350, 'pos_y' => 50, 'posisi_row' => 'E', 'posisi_col' => 4, 'is_active' => true],

            // Meja 20 - standalone
            ['nama_meja' => 'Meja 20', 'nomor_meja' => '20', 'kategori' => 'vip', 'kapasitas' => 4, 'lantai_id' => $lantai2->id, 'merge_group' => null, 'is_mergeable' => false, 'pos_x' => 50, 'pos_y' => 150, 'posisi_row' => 'F', 'posisi_col' => 1, 'is_active' => true],
        ];

        foreach (array_merge($lantai1Meja, $lantai2Meja) as $mejaData) {
            Meja::firstOrCreate(
                ['nama_meja' => $mejaData['nama_meja']],
                $mejaData
            );
        }

        $this->command->info('Meja layout seeded successfully!');
    }
}
