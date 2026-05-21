<?php

namespace Database\Seeders;

use App\Models\Meja;
use Illuminate\Database\Seeder;

class MejaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            // Row A
            ['nama_meja' => 'Meja A1', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'A', 'posisi_col' => 1],
            ['nama_meja' => 'Meja A2', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'A', 'posisi_col' => 2],
            ['nama_meja' => 'Meja A3', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'A', 'posisi_col' => 3],
            ['nama_meja' => 'Meja A4', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'A', 'posisi_col' => 4],

            // Row B
            ['nama_meja' => 'Meja B1', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'B', 'posisi_col' => 1],
            ['nama_meja' => 'Meja B2', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'B', 'posisi_col' => 2],
            ['nama_meja' => 'Meja B3', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'B', 'posisi_col' => 3],
            ['nama_meja' => 'Meja B4', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'B', 'posisi_col' => 4],

            // Row C
            ['nama_meja' => 'Meja C1', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'C', 'posisi_col' => 1],
            ['nama_meja' => 'Meja C2', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'C', 'posisi_col' => 2],
            ['nama_meja' => 'Meja C3', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'C', 'posisi_col' => 3],
            ['nama_meja' => 'Meja C4', 'kategori' => 'regular', 'kapasitas' => 4, 'posisi_row' => 'C', 'posisi_col' => 4],
        ];

        foreach ($tables as $table) {
            Meja::create($table);
        }
    }
}
