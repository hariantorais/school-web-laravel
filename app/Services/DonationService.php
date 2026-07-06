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
         // Filter 1: Pencarian teks berdasarkan judul
         ->when(!empty($filters['search']), function ($query) use ($filters) {
            $query->where('title', 'like', "%{$filters['search']}%");
         })
         // Filter 2: Status Keaktifan (is_active)
         ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
            $query->where('is_active', $filters['status']);
         })
         // 🆕 Filter 3: Sifat / Tipe Program (one_time, recurring_open, recurring_subscription)
         ->when(!empty($filters['type']), function ($query) use ($filters) {
            $query->where('type', $filters['type']);
         })
         // Filter 4: Rentang Target Dana (Hanya berlaku untuk tipe one_time)
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
         // Otomatisasi Pembuatan Slug Unik saat insert data baru
         $slug = Str::slug($data['title']);
         if (Donation::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . Str::lower(Str::random(5));
         }
         $data['slug'] = $slug;

         // Kelola File Gambar melalui Trait
         if ($imageFile) {
            $imagePath = $this->compressAndStore(
               file: $imageFile,
               folder: 'donation-banners',
               width: 1200,
               quality: 80
            );

            $data['image_path'] = $imagePath;
            if (isset($data['image'])) {
               unset($data['image']);
            }
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

         // Perbarui Slug jika admin mengubah judul program donasi saat edit
         $slug = Str::slug($data['title']);
         if (Donation::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $slug . '-' . Str::lower(Str::random(5));
         }
         $data['slug'] = $slug;

         // Kelola Gambar Baru dan Hapus Gambar Lama jika ada upload baru
         if ($newImageFile) {
            $newImagePath = $this->compressAndStore(
               file: $newImageFile,
               folder: 'donation-banners',
               width: 1200,
               quality: 80
            );

            $data['image_path'] = $newImagePath;
            if (isset($data['image'])) {
               unset($data['image']);
            }

            // Hapus fisik berkas lama agar storage VPS tetap hemat
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
    * Menghapus Program Donasi (Mendukung Sistem SoftDeletes Aman)
    * @param int $id
    * @return bool
    * @throws Exception
    */
   public function delete(int $id): bool
   {
      DB::beginTransaction();

      try {
         $donation = Donation::findOrFail($id);

         // ⚠️ KOREKSI SOFT DELETES: 
         // Karena skema DB menggunakan SoftDeletes, data record di tabel tidak benar-benar hilang.
         // Berkas fisik gambar JANGAN dihapus sekarang agar saat admin melakukan RESTORE, gambar tidak rusak.
         $deleted = $donation->delete();

         DB::commit();
         return $deleted;
      } catch (Exception $e) {
         DB::rollBack();
         throw new Exception('Gagal menghapus program donasi: ' . $e->getMessage());
      }
   }

   /**
    * 🆕 PROGRAM TAMBAHAN: Hancurkan Data Permanen beserta Berkas Fisik Gambarnya
    * @param int $id
    * @return bool
    * @throws Exception
    */
   public function forceDelete(int $id): bool
   {
      DB::beginTransaction();

      try {
         // Ambil data termasuk yang sudah berstatus terhapus sementara (Soft Deleted)
         $donation = Donation::onlyTrashed()->findOrFail($id);
         $imagePath = $donation->image_path;

         $forceDeleted = $donation->forceDelete();

         // Jika data permanen hilang dari DB, baru hapus aman berkas fisik gambarnya
         if ($forceDeleted && $imagePath) {
            $this->deleteImage($imagePath);
         }

         DB::commit();
         return true;
      } catch (Exception $e) {
         DB::rollBack();
         throw new Exception('Gagal memusnahkan data program donasi: ' . $e->getMessage());
      }
   }
}
