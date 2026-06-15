<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\DonationTransaction; // Sesuaikan nama model transaksi Anda

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat 10 Program Kampanye Donasi dummy terlebih dahulu
        Donation::factory()->count(10)->create()->each(function ($donation) {

            // 2. Untuk setiap donasi, buat antara 5 sampai 15 transaksi tiruan
            $transactions = DonationTransaction::factory()
                ->count(rand(5, 15))
                ->create([
                    'donation_id' => $donation->id, // Mengunci ke ID donasi saat ini
                ]);

            // 3. Hitung total nominal dari transaksi yang berstatus 'success'
            $totalSuccessAmount = $transactions->where('status', 'success')->sum('amount');

            // 4. Update kolom current_amount di tabel donations agar sinkron murni
            $donation->update([
                'current_amount' => $totalSuccessAmount
            ]);
        });
    }
}
