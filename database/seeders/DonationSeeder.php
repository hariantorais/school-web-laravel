<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\DonationTransaction;
use App\Models\DonationCategory;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kategori udah ada
        if (DonationCategory::count() === 0) {
            $this->call(DonationCategorySeeder::class);
        }

        // 1. Buat 10 Program Kampanye Donasi
        Donation::factory()
            ->count(50)
            ->create()
            ->each(function ($donation) {

                // 2. Buat 5-20 transaksi per donasi
                $transactions = DonationTransaction::factory()
                    ->count(rand(5, 20))
                    ->create([
                        'donation_id' => $donation->id,
                    ]);

                // 3. Hitung total dari transaksi 'success' aja
                $totalSuccessAmount = $transactions
                    ->where('status', 'success')
                    ->sum('amount');

                // 4. Update current_amount biar sinkron
                $donation->update([
                    'current_amount' => $totalSuccessAmount
                ]);
            });
    }
}
