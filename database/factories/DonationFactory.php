<?php

namespace Database\Factories;

use App\Models\DonationCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DonationFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        $type = $this->faker->randomElement(['one_time', 'one_time', 'recurring_open']); // 66.6% one_time
        $isOneTime = $type === 'one_time';

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->randomNumber(4),
            'description' => $this->faker->paragraph(4),
            'image_path' => null,

            // 🔥 SOLUSI SMART: Jika di DB ada kategori, pakai yang acak. Jika kosong, OTOMATIS buatkan 1 kategori baru.
            'donation_category_id' => DonationCategory::inRandomOrder()->first()?->id
                ?? DonationCategory::factory(),

            'type' => $type,
            'target_amount' => $isOneTime ? $this->faker->numberBetween(10_000_000, 500_000_000) : 0,
            'current_amount' => 0,
            'is_active' => true,
            'end_date' => $isOneTime ? $this->faker->dateTimeBetween('+1 week', '+6 months') : null,
        ];
    }
}
