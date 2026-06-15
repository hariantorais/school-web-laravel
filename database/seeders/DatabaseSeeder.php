<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Ust. Rendry',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin1234')
        ]);

        // Daftar kategori resmi khas instansi sekolah
        $categories = [
            'Berita Sekolah',
            'Pengumuman Resmi',
            'Agenda Kegiatan',
            'Prestasi Siswa',
            'Artikel & Edukasi',
            'Info Kelulusan & PPDB'
        ];

        foreach ($categories as $categoryName) {
            Category::updateOrCreate(
                // Unik check berdasarkan slug untuk mencegah duplikasi jika seeder dijalankan ulang
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName]
            );
        }
    }
}
