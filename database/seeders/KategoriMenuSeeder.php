<?php

namespace Database\Seeders;

use App\Models\KategoriMenu;
use Illuminate\Database\Seeder;

class KategoriMenuSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Sate Kambing',
                'deskripsi' => 'Berbagai jenis sate kambing khas yang lezat',
                'ikon' => 'fa-utensils',
                'is_active' => true,
            ],
            [
                'nama_kategori' => 'Sate Sapi',
                'deskripsi' => 'Sate sapi dengan daging pilihan dan bumbu special',
                'ikon' => 'fa-bowl-food',
                'is_active' => true,
            ],
            [
                'nama_kategori' => 'Sate Ayam',
                'deskripsi' => 'Sate ayam dengan berbagai variant rasa',
                'ikon' => 'fa-drumstick-bite',
                'is_active' => true,
            ],
            [
                'nama_kategori' => 'Minuman',
                'deskripsi' => 'Berbagai pilihan minuman segar',
                'ikon' => 'fa-coffee',
                'is_active' => true,
            ],
            [
                'nama_kategori' => 'Makanan Pendamping',
                'deskripsi' => 'Makanan pelengkap seperti nasi, lontong, dll',
                'ikon' => 'fa-bowl-rice',
                'is_active' => true,
            ],
        ];

        foreach ($kategoris as $kategori) {
            KategoriMenu::create($kategori);
        }
    }
}
