<?php

namespace Database\Seeders;

use App\Models\MenuSpecial;
use App\Models\MenuSpecialItem;
use Illuminate\Database\Seeder;

class MenuSpecialSeeder extends Seeder
{
    public function run(): void
    {
        $specials = [
            [
                'title' => 'Tumpeng Premium',
                'short_description' => 'Paket tumpeng lengkap dengan lauk premium, cocok untuk acara besar, syukuran, dan perayaan.' ,
                'banner_image' => 'menu-specials/tumpeng_premium.jpg',
                'is_active' => true,
                'items' => [
                    [
                        'name' => 'Tumpeng Premium Mini',
                        'price' => 350000,
                        'description' => 'Tumpeng mini lengkap untuk 10-15 orang dengan lauk premium dan garnish spesial.',
                        'image' => 'menu-special-items/tumpeng_premium_mini.jpg',
                        'is_available' => true,
                    ],
                    [
                        'name' => 'Tumpeng Premium Medium',
                        'price' => 550000,
                        'description' => 'Paket medium untuk 20-25 orang, hadir dengan lauk spesial dan tampilan elegan.',
                        'image' => 'menu-special-items/tumpeng_premium_medium.jpg',
                        'is_available' => true,
                    ],
                ],
            ],
            [
                'title' => 'Katering Spesial',
                'short_description' => 'Paket katering elegan untuk acara formal atau keluarga, dikemas profesional dengan pilihan menu premium.',
                'banner_image' => 'menu-specials/katering_spesial.jpg',
                'is_active' => true,
                'items' => [
                    [
                        'name' => 'Katering Spesial Standar',
                        'price' => 250000,
                        'description' => 'Pilihan menu lengkap untuk 15 orang dengan sajian hidangan utama dan dessert.',
                        'image' => 'menu-special-items/katering_standar.jpg',
                        'is_available' => true,
                    ],
                ],
            ],
        ];

        foreach ($specials as $specialData) {
            $items = $specialData['items'];
            unset($specialData['items']);
            $specialData['slug'] = \Illuminate\Support\Str::slug($specialData['title']);

            $special = MenuSpecial::firstOrCreate(
                ['slug' => $specialData['slug']],
                $specialData
            );

            foreach ($items as $item) {
                $item['menu_special_id'] = $special->id;

        MenuSpecialItem::firstOrCreate(
            [
                'menu_special_id' => $special->id,
                'name' => $item['name']
            ],
            $item
        );
            }
        }
    }
}
