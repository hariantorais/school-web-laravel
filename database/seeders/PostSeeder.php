<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📝 Mulai seeding posts...');

        // Pastikan ada user dan category
        $this->ensureDataExists();

        // Generate posts
        $this->generatePosts();

        $this->command->info('✅ Seeding posts selesai!');
    }

    /**
     * Ensure users and categories exist
     */
    private function ensureDataExists(): void
    {
        if (User::count() === 0) {
            $this->command->warn('⚠️  Tidak ada user, membuat user default...');
            User::factory(5)->create();
        }

        if (Category::count() === 0) {
            $this->command->warn('⚠️  Tidak ada kategori, membuat kategori default...');
            $this->createDefaultCategories();
        }
    }

    /**
     * Create default categories if not exists
     */
    private function createDefaultCategories(): void
    {
        $categories = [
            'Berita Sekolah' => 'Informasi terkini seputar kegiatan dan perkembangan sekolah',
            'Pengumuman Resmi' => 'Pengumuman resmi dari pihak sekolah untuk seluruh siswa dan orang tua',
            'Agenda Kegiatan' => 'Jadwal dan agenda kegiatan sekolah yang akan datang',
            'Prestasi Siswa' => 'Pencapaian dan prestasi siswa baik akademik maupun non-akademik',
            'Artikel & Edukasi' => 'Artikel edukatif dan informatif seputar dunia pendidikan',
            'Info Kelulusan & PPDB' => 'Informasi kelulusan dan penerimaan peserta didik baru',
        ];

        foreach ($categories as $name => $description) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $description,
                ]
            );
        }

        $this->command->info('📂 Kategori default berhasil dibuat.');
    }

    /**
     * Generate all posts
     */
    private function generatePosts(): void
    {
        // 1. Published posts dengan berbagai kategori
        $this->generatePublishedPosts();

        // 2. Draft posts
        $this->generateDraftPosts();

        // 3. Popular posts
        $this->generatePopularPosts();

        // 4. Posts tanpa gambar
        $this->generatePostsWithoutImage();

        // 5. Posts per kategori spesifik
        $this->generateCategorySpecificPosts();

        // 6. Featured posts untuk homepage
        $this->generateFeaturedPosts();
    }

    /**
     * Generate published posts
     */
    private function generatePublishedPosts(): void
    {
        $count = 30;
        $this->command->info("📄 Membuat {$count} post published...");

        Post::factory($count)
            ->published()
            ->create();

        $this->command->info("✅ {$count} post published berhasil dibuat.");
    }

    /**
     * Generate draft posts
     */
    private function generateDraftPosts(): void
    {
        $count = 10;
        $this->command->info("📄 Membuat {$count} post draft...");

        Post::factory($count)
            ->draft()
            ->create();

        $this->command->info("✅ {$count} post draft berhasil dibuat.");
    }

    /**
     * Generate popular posts
     */
    private function generatePopularPosts(): void
    {
        $count = 5;
        $this->command->info("🔥 Membuat {$count} post populer...");

        Post::factory($count)
            ->published()
            ->popular()
            ->create();

        $this->command->info("✅ {$count} post populer berhasil dibuat.");
    }

    /**
     * Generate posts without image
     */
    private function generatePostsWithoutImage(): void
    {
        $count = 5;
        $this->command->info("🖼️  Membuat {$count} post tanpa gambar...");

        Post::factory($count)
            ->published()
            ->withoutImage()
            ->create();

        $this->command->info("✅ {$count} post tanpa gambar berhasil dibuat.");
    }

    /**
     * Generate posts for each category
     */
    private function generateCategorySpecificPosts(): void
    {
        $this->command->info('📂 Membuat post per kategori...');

        $categoryConfigs = [
            'Berita Sekolah' => [
                'count' => 5,
                'views' => [100, 150, 200, 250, 300],
                'titles' => [
                    'Kegiatan Upacara Bendera Hari Senin',
                    'Sekolah Mengadakan Bakti Sosial',
                    'Kunjungan Studi Wisata ke Museum',
                    'Perayaan Hari Kemerdekaan Sekolah',
                    'Workshop Pengembangan Diri untuk Siswa'
                ]
            ],
            'Pengumuman Resmi' => [
                'count' => 5,
                'views' => [300, 350, 400, 450, 500],
                'titles' => [
                    'Pengumuman Libur Semester Ganjil',
                    'Jadwal Ujian Akhir Semester',
                    'Pemberitahuan Perubahan Seragam Sekolah',
                    'Pengumuman Penerimaan Bantuan Siswa',
                    'Info Penting: Perubahan Jadwal Pelajaran'
                ]
            ],
            'Agenda Kegiatan' => [
                'count' => 3,
                'views' => [200, 250, 300],
                'titles' => [
                    'Agenda PTS Semester Genap 2024',
                    'Kegiatan Class Meeting Akhir Tahun',
                    'Peringatan Hari Pendidikan Nasional'
                ]
            ],
            'Prestasi Siswa' => [
                'count' => 4,
                'views' => [400, 450, 500, 600],
                'titles' => [
                    'Siswa Raih Juara Olimpiade Matematika',
                    'Prestasi Gemilang di Bidang Olahraga',
                    'Siswa Berhasil Meraih Medali Emas',
                    'Tim Robotika Sekolah Juara Nasional'
                ]
            ],
            'Artikel & Edukasi' => [
                'count' => 5,
                'views' => [150, 200, 250, 300, 350],
                'titles' => [
                    'Tips Belajar Efektif untuk Siswa',
                    'Pentingnya Pendidikan Karakter di Sekolah',
                    'Mengenal Metode Pembelajaran Modern',
                    'Peran Orang Tua dalam Pendidikan Anak',
                    'Pemanfaatan Teknologi di Dunia Pendidikan'
                ]
            ],
            'Info Kelulusan & PPDB' => [
                'count' => 3,
                'views' => [500, 600, 700],
                'titles' => [
                    'Pengumuman Kelulusan Tahun Ajaran 2023/2024',
                    'Pendaftaran PPDB Gelombang 1 Dibuka',
                    'Informasi Lengkap PPDB 2024/2025'
                ]
            ]
        ];

        foreach ($categoryConfigs as $categoryName => $config) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
                $this->command->warn("⚠️  Kategori '{$categoryName}' tidak ditemukan, dilewati.");
                continue;
            }

            for ($i = 0; $i < $config['count']; $i++) {
                $title = $config['titles'][$i] ?? $this->generateTitleForCategory($categoryName);
                $views = $config['views'][$i] ?? rand(100, 500);
                $publishedAt = $this->generatePublishedDate($i);

                Post::factory()
                    ->published()
                    ->for($category)
                    ->create([
                        'title' => $title,
                        'slug' => Str::slug($title) . '-' . Str::random(6),
                        'content' => $this->generateContentForCategory($categoryName),
                        'views' => $views,
                        'published_at' => $publishedAt,
                    ]);

                $this->command->line("   ✓ Post '{$title}' untuk kategori '{$categoryName}'");
            }
        }

        $this->command->info("✅ Post per kategori berhasil dibuat.");
    }

    /**
     * Generate featured posts for homepage
     */
    private function generateFeaturedPosts(): void
    {
        $count = 3;
        $this->command->info("⭐ Membuat {$count} post featured...");

        $featuredTitles = [
            'Selamat Datang Tahun Ajaran Baru 2024/2025',
            'Prestasi Membanggakan Siswa di Kancah Internasional',
            'Transformasi Digital Pendidikan di Sekolah Kami'
        ];

        foreach ($featuredTitles as $index => $title) {
            $category = Category::inRandomOrder()->first();

            Post::factory()
                ->published()
                ->popular()
                ->for($category)
                ->create([
                    'title' => $title,
                    'slug' => Str::slug($title) . '-' . Str::random(6),
                    'content' => $this->generateFeaturedContent($title),
                    'views' => rand(800, 1500),
                    'published_at' => now()->subDays($index * 3),
                ]);

            $this->command->line("   ⭐ Post featured '{$title}'");
        }

        $this->command->info("✅ {$count} post featured berhasil dibuat.");
    }

    /**
     * Generate title for category
     */
    private function generateTitleForCategory(string $categoryName): string
    {
        $faker = Faker::create('id_ID');

        $templates = [
            'Berita Sekolah' => [
                "Kegiatan {$faker->word()} di Lingkungan Sekolah",
                "Sekolah Mengadakan {$faker->word()}",
                "Update Kegiatan {$faker->word()} Siswa"
            ],
            'Pengumuman Resmi' => [
                "Pengumuman {$faker->word()} untuk Seluruh Siswa",
                "Info Penting: {$faker->sentence(3)}",
                "Pemberitahuan {$faker->word()} Resmi"
            ],
            'Agenda Kegiatan' => [
                "Jadwal {$faker->word()} Bulan Ini",
                "Agenda {$faker->word()} yang Perlu Diketahui",
                "Rencana {$faker->word()} Mendatang"
            ],
            'Prestasi Siswa' => [
                "Prestasi {$faker->word()} Raih Juara",
                "Siswa Berhasil {$faker->word()}",
                "Keberhasilan {$faker->word()} di Kompetisi"
            ],
            'Artikel & Edukasi' => [
                "Tips {$faker->word()} untuk Siswa",
                "Pentingnya {$faker->word()} di Sekolah",
                "Edukasi {$faker->word()}"
            ],
            'Info Kelulusan & PPDB' => [
                "Info Kelulusan {$faker->year()}",
                "Pendaftaran PPDB {$faker->year()}",
                "Update Info {$faker->word()} PPDB"
            ]
        ];

        $template = $templates[$categoryName] ?? ["{$faker->sentence(3)}"];

        return $faker->randomElement($template);
    }

    /**
     * Generate content for category
     */
    private function generateContentForCategory(string $categoryName): string
    {
        $faker = Faker::create('id_ID');

        $content = '<h2>' . $faker->sentence(4) . '</h2>';
        $content .= '<p>' . $faker->paragraphs(3, true) . '</p>';

        // Tambahan konten spesifik kategori
        if (in_array($categoryName, ['Berita Sekolah', 'Pengumuman Resmi'])) {
            $content .= '<blockquote><p>' . $faker->sentence(6) . '</p></blockquote>';
        }

        if ($categoryName === 'Prestasi Siswa') {
            $content .= '<ul>';
            $achievements = [
                'Juara 1 Lomba ' . $faker->word(),
                'Medali Emas di ' . $faker->word(),
                'Siswa Berprestasi Bidang ' . $faker->word()
            ];
            foreach ($faker->randomElements($achievements, 2) as $achievement) {
                $content .= '<li>' . $achievement . '</li>';
            }
            $content .= '</ul>';
        }

        if ($categoryName === 'Agenda Kegiatan') {
            $content .= '<p><strong>Waktu:</strong> ' . $faker->dateTimeBetween('now', '+2 months')->format('d F Y') . '</p>';
            $content .= '<p><strong>Tempat:</strong> ' . $faker->address() . '</p>';
        }

        if ($categoryName === 'Artikel & Edukasi') {
            $content .= '<h3>Manfaat ' . $faker->word() . '</h3>';
            $content .= '<ul>';
            for ($i = 0; $i < 3; $i++) {
                $content .= '<li>' . $faker->sentence(5) . '</li>';
            }
            $content .= '</ul>';
        }

        $content .= '<p>' . $faker->paragraph() . '</p>';
        $content .= '<p><strong>' . $faker->name() . '</strong> - ' . $faker->jobTitle() . '</p>';

        return $content;
    }

    /**
     * Generate featured content
     */
    private function generateFeaturedContent(string $title): string
    {
        $faker = Faker::create('id_ID');

        return '<h1>' . $title . '</h1>' .
            '<p>' . $faker->paragraphs(5, true) . '</p>' .
            '<img src="' . $faker->imageUrl(800, 400, 'nature', true) . '" alt="' . $title . '">' .
            '<p>' . $faker->paragraphs(3, true) . '</p>' .
            '<h3>Kesimpulan</h3>' .
            '<p>' . $faker->paragraph() . '</p>';
    }

    /**
     * Generate published date based on index
     */
    private function generatePublishedDate(int $index): \DateTime
    {
        return now()->subDays(rand(1, 365))->subHours(rand(1, 24));
    }
}
