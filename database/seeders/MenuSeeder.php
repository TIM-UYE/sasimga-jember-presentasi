<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\KategoriMenu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil kategori
        $kambing = KategoriMenu::where('nama_kategori', 'Sate Kambing')->first()->id;
        $ayam = KategoriMenu::where('nama_kategori', 'Sate Ayam')->first()->id;
        $minuman = KategoriMenu::where('nama_kategori', 'Minuman')->first()->id;
        $pendamping = KategoriMenu::where('nama_kategori', 'Makanan Pendamping')->first()->id;

        // Helper gambar otomatis
        function getImage($nama)
        {
            if (str_contains($nama, 'Kambing')) return 'goat satay';
            if (str_contains($nama, 'Ayam')) return 'chicken satay';
            if (str_contains($nama, 'Es')) return 'cold drink';
            if (str_contains($nama, 'Kopi')) return 'coffee';
            if (str_contains($nama, 'Nasi')) return 'rice food';
            if (str_contains($nama, 'Gule') || str_contains($nama, 'Sop')) return 'soup food';

            return 'indonesian food';
        }

        $menus = [

            // SATE KAMBING
            ['nama_menu' => 'Sate Kambing Daging', 'harga' => 75000, 'kategori_id' => $kambing],
            ['nama_menu' => 'Sate Kambing Campur Ati', 'harga' => 55000, 'kategori_id' => $kambing],
            ['nama_menu' => 'Sate Kambing Biasa', 'harga' => 55000, 'kategori_id' => $kambing],
            ['nama_menu' => 'Sate Kambing Daging Pedas', 'harga' => 78000, 'kategori_id' => $kambing],
            ['nama_menu' => 'Sate Kambing Campur Ati Pedas', 'harga' => 58000, 'kategori_id' => $kambing],
            ['nama_menu' => 'Sate Kambing Biasa Pedas', 'harga' => 58000, 'kategori_id' => $kambing],

            // SATE AYAM
            ['nama_menu' => 'Sate Ayam Daging', 'harga' => 30000, 'kategori_id' => $ayam],
            ['nama_menu' => 'Sate Ayam Biasa', 'harga' => 25000, 'kategori_id' => $ayam],
            ['nama_menu' => 'Sate Ayam Daging Pedas', 'harga' => 33000, 'kategori_id' => $ayam],
            ['nama_menu' => 'Sate Ayam Biasa Pedas', 'harga' => 28000, 'kategori_id' => $ayam],

            // MAKANAN PENDAMPING
            ['nama_menu' => 'Gule Kambing', 'harga' => 35000, 'kategori_id' => $pendamping],
            ['nama_menu' => 'Gule Kacang Ijo', 'harga' => 30000, 'kategori_id' => $pendamping],
            ['nama_menu' => 'Gule Kepala Kambing', 'harga' => 100000, 'kategori_id' => $pendamping],
            ['nama_menu' => 'Sop Kikil Kambing', 'harga' => 40000, 'kategori_id' => $pendamping],

            ['nama_menu' => 'Nasi Gule Kambing', 'harga' => 35000, 'kategori_id' => $pendamping],
            ['nama_menu' => 'Nasi Gule Kacang Ijo', 'harga' => 30000, 'kategori_id' => $pendamping],
            ['nama_menu' => 'Nasi Kebuli', 'harga' => 15000, 'kategori_id' => $pendamping],
            ['nama_menu' => 'Nasi Putih', 'harga' => 6000, 'kategori_id' => $pendamping],
            ['nama_menu' => 'Lontong', 'harga' => 6000, 'kategori_id' => $pendamping],

            // PAKET
            ['nama_menu' => 'Paket Hemat Sate Kambing', 'harga' => 35000, 'kategori_id' => $pendamping],
            ['nama_menu' => 'Paket Hemat Sate Ayam', 'harga' => 20000, 'kategori_id' => $pendamping],
            ['nama_menu' => 'Nasi Kebuli + Sate Kambing (Weekend)', 'harga' => 45000, 'kategori_id' => $pendamping],
            ['nama_menu' => 'Nasi Kebuli + Sate Ayam (Weekend)', 'harga' => 30000, 'kategori_id' => $pendamping],

            // MINUMAN
            ['nama_menu' => 'Es Teh', 'harga' => 8000, 'kategori_id' => $minuman],
            ['nama_menu' => 'Es Jeruk', 'harga' => 10000, 'kategori_id' => $minuman],
            ['nama_menu' => 'Es Lemon Tea', 'harga' => 12000, 'kategori_id' => $minuman],
            ['nama_menu' => 'Es Kopi Susu', 'harga' => 12000, 'kategori_id' => $minuman],
            ['nama_menu' => 'Es Milo', 'harga' => 15000, 'kategori_id' => $minuman],

            ['nama_menu' => 'Teh Panas', 'harga' => 8000, 'kategori_id' => $minuman],
            ['nama_menu' => 'Kopi Hitam', 'harga' => 10000, 'kategori_id' => $minuman],
            ['nama_menu' => 'Kopi Susu', 'harga' => 12000, 'kategori_id' => $minuman],
            ['nama_menu' => 'Air Mineral', 'harga' => 5000, 'kategori_id' => $minuman],
        ];

        foreach ($menus as $menu) {
            Menu::create([
                'nama_menu' => $menu['nama_menu'],
                'harga' => $menu['harga'],
                'kategori_id' => $menu['kategori_id'],
                'stok' => 100,
                'deskripsi' => $menu['nama_menu'],
                'gambar' => 'https://picsum.photos/400/300?random=' . rand(1,1000),
            ]);
        }
    }
}
