<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
   /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
   public function definition(): array
   {
      $title = $this->faker->sentence(6, true);
      $status = $this->faker->randomElement(['draft', 'published']);
      $publishedAt = $status === 'published' ? $this->faker->dateTimeBetween('-1 year', 'now') : null;

      return [
         'user_id' => User::factory(),
         'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
         'title' => $title,
         'slug' => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 1000),
         'featured_image' => $this->faker->imageUrl(800, 600, 'nature', true, 'Post Image'),
         'content' => $this->generateContent(),
         'status' => $status,
         'views' => $this->faker->numberBetween(0, 1000),
         'published_at' => $publishedAt,
      ];
   }

   /**
    * Generate dummy content with HTML formatting berdasarkan kategori
    */
   private function generateContent(): string
   {
      $paragraphs = $this->faker->paragraphs(5);
      $content = '<h2>' . $this->faker->sentence(4) . '</h2>';

      foreach ($paragraphs as $index => $paragraph) {
         $content .= '<p>' . $paragraph . '</p>';

         // Tambahkan gambar di tengah konten
         if ($index === 2) {
            $content .= '<img src="' . $this->faker->imageUrl(800, 400, 'nature', true) . '" alt="' . $this->faker->sentence(3) . '" class="img-fluid">';
         }

         // Tambahkan list
         if ($index === 3) {
            $content .= '<ul>';
            for ($i = 0; $i < 3; $i++) {
               $content .= '<li>' . $this->faker->sentence(3) . '</li>';
            }
            $content .= '</ul>';
         }
      }

      return $content;
   }

   /**
    * State: Post sudah dipublikasikan
    */
   public function published(): static
   {
      return $this->state(fn(array $attributes) => [
         'status' => 'published',
         'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
      ]);
   }

   /**
    * State: Post masih draft
    */
   public function draft(): static
   {
      return $this->state(fn(array $attributes) => [
         'status' => 'draft',
         'published_at' => null,
      ]);
   }

   /**
    * State: Post dengan banyak views
    */
   public function popular(): static
   {
      return $this->state(fn(array $attributes) => [
         'views' => $this->faker->numberBetween(500, 5000),
      ]);
   }

   /**
    * State: Post dengan sedikit views
    */
   public function unpopular(): static
   {
      return $this->state(fn(array $attributes) => [
         'views' => $this->faker->numberBetween(0, 50),
      ]);
   }

   /**
    * State: Post dengan featured image spesifik
    */
   public function withImage(string $imageUrl = null): static
   {
      return $this->state(fn(array $attributes) => [
         'featured_image' => $imageUrl ?? $this->faker->imageUrl(800, 600, 'nature', true),
      ]);
   }

   /**
    * State: Post tanpa featured image
    */
   public function withoutImage(): static
   {
      return $this->state(fn(array $attributes) => [
         'featured_image' => null,
      ]);
   }

   /**
    * State: Post untuk kategori tertentu
    */
   public function forCategory(string $categoryName): static
   {
      $category = Category::where('name', $categoryName)->first();

      return $this->state(fn(array $attributes) => [
         'category_id' => $category?->id ?? Category::factory(),
      ]);
   }

   /**
    * State: Post Berita Sekolah
    */
   public function beritaSekolah(): static
   {
      return $this->forCategory('Berita Sekolah');
   }

   /**
    * State: Post Pengumuman Resmi
    */
   public function pengumuman(): static
   {
      return $this->forCategory('Pengumuman Resmi');
   }

   /**
    * State: Post Agenda Kegiatan
    */
   public function agenda(): static
   {
      return $this->forCategory('Agenda Kegiatan');
   }

   /**
    * State: Post Prestasi Siswa
    */
   public function prestasi(): static
   {
      return $this->forCategory('Prestasi Siswa');
   }

   /**
    * State: Post Artikel & Edukasi
    */
   public function artikel(): static
   {
      return $this->forCategory('Artikel & Edukasi');
   }

   /**
    * State: Post Info Kelulusan & PPDB
    */
   public function ppdb(): static
   {
      return $this->forCategory('Info Kelulusan & PPDB');
   }
}
