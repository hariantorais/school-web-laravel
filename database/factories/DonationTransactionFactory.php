<?php

namespace Database\Factories;

use App\Models\DonationTransaction; // Pastikan nama model sesuai
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationTransactionFactory extends Factory
{
    protected $model = DonationTransaction::class;

    public function definition(): array
    {
        return [
            // donation_id otomatis disuntikkan secara dinamis dari Seeder
            'donor_name'     => $this->faker->name(),
            'donor_email'    => $this->faker->unique()->safeEmail(),
            'donor_phone'    => $this->faker->phoneNumber(), // Tambahkan jika diperlukan

            // 🔥 SOLUSI UTAMA: Generate kode referensi invoice unik (Contoh: INV-20260616-XXXXX)
            'reference_code' => now()->format('Ymd') . '-' . strtoupper($this->faker->unique()->bothify('??###')),

            'amount'         => $this->faker->randomElement([50000, 100000, 250000, 500000, 1000000]),
            'status'         => $this->faker->randomElement(['success', 'success', 'success', 'pending', 'failed']),
            'notes'          => $this->faker->boolean(60) ? $this->faker->sentence() : null,
            'created_at'     => $this->faker->dateTimeBetween('-1 months', 'now'),
            'payment_method' => $this->faker->randomElement(['cash', 'bank_transfer']),
        ];
    }
}
