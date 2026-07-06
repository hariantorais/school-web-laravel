<?php

namespace Database\Factories;

use App\Models\DonationTransaction;
use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationTransactionFactory extends Factory
{
    protected $model = DonationTransaction::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement([
            'success',
            'success',
            'success',
            'success', // 80% success
            'pending',
            'failed'
        ]);

        $createdAt = $this->faker->dateTimeBetween('-2 months', 'now');
        $isSuccess = $status === 'success';

        return [
            'donation_id'    => Donation::factory(), // fallback kalo gak di-inject seeder
            'donor_name'     => $this->faker->name(),
            'donor_email'    => $this->faker->unique()->safeEmail(),
            'donor_phone'    => '08' . $this->faker->numerify('##########'),

            // Reference unik: INV-20260617-8F3K2
            'reference_code' => 'INV-' . now()->format('Ymd') . '-' . strtoupper($this->faker->unique()->bothify('??##?')),

            'amount'         => $this->faker->randomElement([
                10000,
                20000,
                25000,
                50000,
                75000,
                100000,
                150000,
                200000,
                250000,
                500000,
                1000000
            ]),
            'status'         => $status,
            'payment_method' => $this->faker->randomElement([
                'cash',
                'bank_transfer'
            ]),
            'notes'          => $this->faker->boolean(40) ? $this->faker->sentence(6) : null,
            'created_at'     => $createdAt,
            'updated_at'     => $createdAt,


        ];
    }

    // State buat bikin transaksi success doang
    public function success(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'success',
        ]);
    }

    // State buat pending
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
        ]);
    }
}
