<?php

namespace App\Services;

use App\Models\Donation;
use App\Traits\HasImageProcess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class DonationService
{
   use HasImageProcess;


   /**
    * Mengambil data program donasi dengan paginasi dan filter multi-opsi
    */
   public function getAllPaginated(array $filters = [], int $perPage = 10)
   {
      return Donation::query()
         ->when(!empty($filters['search']), function ($query) use ($filters) {
            $query->where('title', 'like', "%{$filters['search']}%");
         })
         ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
            $query->where('is_active', $filters['status']);
         })
         ->when(!empty($filters['target_range']), function ($query) use ($filters) {
            match ($filters['target_range']) {
               'under_10m'  => $query->where('target_amount', '<', 10000000),
               '10m_to_50m' => $query->whereBetween('target_amount', [10000000, 50000000]),
               'above_50m'  => $query->where('target_amount', '>', 50000000),
               default      => null
            };
         })
         ->latest()
         ->paginate($perPage);
   }

   public function findById(int $id): Donation
   {
      return Donation::findOrFail($id);
   }

   /**
    * Membuat program donasi baru dengan kompresi gambar otomatis & Slug unik
    */
   public function create(array $data, $imageFile = null): Donation
   {
      DB::beginTransaction();

      try {
         // REVISI 2: Otomatisasi Pembuatan Slug Unik saat insert data baru
         $slug = Str::slug($data['title']);
         if (Donation::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . Str::lower(Str::random(5));
         }
         $data['slug'] = $slug;

         if ($imageFile) {
            $imagePath = $this->compressAndStore(
               file: $imageFile,
               folder: 'donation-banners',
               width: 1200,
               quality: 80
            );

            // Menjamin key masuk sesuai field database Anda
            $data['image_path'] = $imagePath;
            unset($data['image']);
         }

         $donation = Donation::create($data);

         DB::commit();
         return $donation;
      } catch (Exception $e) {
         DB::rollBack();

         if (isset($imagePath)) {
            $this->deleteImage($imagePath);
         }

         throw $e;
      }
   }

   /**
    * Memperbarui program donasi, mengelola pergantian gambar lama & Slug dinamis
    */
   public function update(int $id, array $data, $newImageFile = null): Donation
   {
      DB::beginTransaction();

      try {
         $donation = Donation::findOrFail($id);

         // REVISI 3: Perbarui Slug jika admin mengubah judul program donasi saat edit
         $slug = Str::slug($data['title']);
         if (Donation::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $slug . '-' . Str::lower(Str::random(5));
         }
         $data['slug'] = $slug;

         if ($newImageFile) {
            $newImagePath = $this->compressAndStore(
               file: $newImageFile,
               folder: 'donation-banners',
               width: 1200,
               quality: 80
            );

            // Menjamin key masuk ke field database image_path Anda
            $data['image_path'] = $newImagePath;
            unset($data['image']);

            // Panggil fungsi deleteImage dari Trait untuk hapus gambar lama agar storage vps hemat
            if ($donation->image_path) {
               $this->deleteImage($donation->image_path);
            }
         }

         $donation->update($data);

         DB::commit();
         return $donation;
      } catch (Exception $e) {
         DB::rollBack();

         if (isset($newImagePath)) {
            $this->deleteImage($newImagePath);
         }

         throw $e;
      }
   }

   /**
    * Menghapus Program Donasi Sekaligus Gambarnya dari Storage Disk
    * @param int $id
    * @return bool
    * @throws Exception
    */
   public function delete(int $id): bool
   {
      DB::beginTransaction();

      try {
         $donation = Donation::findOrFail($id);

         // 1. Simpan path gambar sebelum baris data di database dihapus
         $imagePath = $donation->image_path;

         // 2. Hapus data dari tabel database
         $deleted = $donation->delete();

         // 3. Jika database sukses terhapus, hapus fisik gambar WebP di storage server
         if ($deleted && $imagePath) {
            $this->deleteImage($imagePath);
         }

         DB::commit();
         return true;
      } catch (Exception $e) {
         DB::rollBack();
         throw new Exception('Gagal menghapus program donasi: ' . $e->getMessage());
      }
   }
}
