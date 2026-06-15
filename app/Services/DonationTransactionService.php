<?php

namespace App\Services;

use App\Models\DonationTransaction;
use App\Traits\HasImageProcess;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

class DonationTransactionService
{
   use HasImageProcess;

   /**
    * Mengambil seluruh riwayat transaksi donasi dengan filter multi-opsi & pencarian cerdas
    * * @param array $filters Payload filter dari form UI index
    * @param int $perPage Jumlah baris paginasi halaman
    * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
    */
   public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
   {
      return DonationTransaction::with(['donation', 'recipientPanitia'])
         ->when(!empty($filters['search']), function ($query) use ($filters) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
               $q->where('donor_name', 'like', "%{$search}%")
                  ->orWhere('reference_code', 'like', "%{$search}%")
                  ->orWhereHas('donation', function ($donationQuery) use ($search) {
                     $donationQuery->where('title', 'like', "%{$search}%");
                  });
            });
         })
         ->when(!empty($filters['donation_id']), function ($query) use ($filters) {
            $query->where('donation_id', $filters['donation_id']);
         })
         ->when(!empty($filters['status']), function ($query) use ($filters) {
            $query->where('status', $filters['status']);
         })
         ->latest()
         ->paginate($perPage);
   }

   /**
    * Mendapatkan data spesifik log transaksi berdasarkan ID beserta relasinya
    * * @param int $id
    * @return \App\Models\DonationTransaction
    */
   public function findById(int $id): DonationTransaction
   {
      return DonationTransaction::with(['donation', 'recipientPanitia'])->findOrFail($id);
   }

   /**
    * Memproses Verifikasi Manual Status Pembayaran Donasi oleh Panitia
    * Otomatis mengakumulasikan nominal dana masuk ke dalam saldo 'current_amount' di tabel donations
    * * @param int $transactionId ID log transaksi yang divalidasi
    * @param string $status Target status pengubahan ('success' atau 'failed')
    * @return bool
    * @throws \Exception
    */
   public function verifyPayment(int $transactionId, string $status): bool
   {
      // Bungkus dengan Database Transaction untuk menjaga keaslian mutasi saldo kas
      DB::beginTransaction();

      try {
         $transaction = DonationTransaction::findOrFail($transactionId);

         // Proteksi 1: Jika transaksi sudah sukses, kunci agar tidak terjadi akumulasi ganda (double-count)
         if ($transaction->status === 'success') {
            throw new Exception('Transaksi ini sudah berhasil diverifikasi sebelumnya.');
         }

         // Proteksi 2: Validasi inputan status agar tidak dimasukkan string sembarangan
         if (!in_array($status, ['success', 'failed'])) {
            throw new Exception('Status verifikasi yang diberikan tidak valid.');
         }

         // 1. Update status transaksi saat ini, pasang ID panitia penanggung jawab & cap waktu
         $transaction->update([
            'status' => $status,
            'verified_by'    => $status === 'success' ? auth()->id() : null,
         ]);

         // 2. Jika panitia menyetujui (success), suntikkan dana segar ke saldo program donasi terkait
         if ($status === 'success') {
            $donation = $transaction->donation;

            // Menggunakan metode increment bawaan untuk menghindari bug race condition di server
            $donation->increment('current_amount', $transaction->amount);
         }

         DB::commit();
         return true;
      } catch (Exception $e) {
         DB::rollBack();
         throw new Exception('Gagal memproses verifikasi log donasi: ' . $e->getMessage());
      }
   }

   public function createManualTransaction(array $data): \App\Models\DonationTransaction
   {
      DB::beginTransaction();

      try {
         // Suntikkan ID panitia yang sedang login sebagai verifikator mutlak
         $data['verified_by'] = auth()->guard('web')->id();

         // 1. Buat data transaksi sukses
         $transaction = \App\Models\DonationTransaction::create($data);

         // 2. Akumulasikan langsung ke saldo program donasi terkait
         $donation = $transaction->donation;
         $donation->increment('current_amount', $transaction->amount);

         DB::commit();
         return $transaction;
      } catch (\Exception $e) {
         DB::rollBack();
         throw new \Exception('Gagal membukukan transaksi manual: ' . $e->getMessage());
      }
   }
}
