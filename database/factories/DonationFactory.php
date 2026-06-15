<?php

namespace Database\Factories;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DonationFactory extends Factory
{
    protected $model = Donation::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);
        $targetAmount = $this->faker->randomElement([10000000, 25000000, 50000000, 100000000]); // 10jt - 100jt

        return [
            'title'          => rtrim($title, '.'),
            'slug'           => Str::slug($title),
            'description'    => '<p>' . implode('</p><p>', $this->faker->paragraphs(3)) . '</p>', // Format HTML Trix
            'image_path'     => null, // Bisa diisi default jika ada file dummy
            'target_amount'  => $targetAmount,
            'current_amount' => 0, // Akan dihitung otomatis dari transaksi sukses di Seeder
            'is_active'      => $this->faker->boolean(85), // 85% aktif
            'end_date'       => $this->faker->dateTimeBetween('+1 months', '+6 months')->format('Y-m-d'),
            'created_at'     => $this->faker->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
