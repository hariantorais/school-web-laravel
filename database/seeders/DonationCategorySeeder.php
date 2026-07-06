<?php

namespace Database\Seeders;

use App\Models\DonationCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DonationCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pembangunan',
                'icon' => 'building-office-2',
                'color' => '#F59E0B',
                'sort_order' => 1,
                'description' => 'Pembangunan masjid, asrama, kelas, dan fasilitas pondok'
            ],


            [
                'name' => 'Sosial',
                'icon' => 'users',
                'color' => '#8B5CF6',
                'sort_order' => 2,
                'description' => 'Santunan yatim, dhuafa, korban bencana'
            ],

            [
                'name' => 'Ramadhan',
                'icon' => 'moon',
                'color' => '#D4AF37',
                'sort_order' => 3,
                'description' => 'Buka puasa, zakat fitrah, THR guru, paket lebaran'
            ],
        ];

        foreach ($categories as $category) {
            DonationCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                    'color' => $category['color'],
                    'sort_order' => $category['sort_order'],
                    'description' => $category['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
