<?php

namespace App\Services;

use App\Models\Post;
use App\Traits\HasImageProcess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

class PostService
{
   use HasImageProcess;

   /**
    * Mengambil data postingan sekolah dengan paginasi, pencarian, dan filter multi-opsi
    * Diperbaiki agar pencarian 'like' fleksibel di tengah kalimat (%search%)
    */
   public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
   {
      return Post::with(['category', 'user'])
         ->when(!empty($filters['search']), function ($query) use ($filters) {
            $query->where('title', 'like', "%{$filters['search']}%");
         })
         ->when(!empty($filters['category']), function ($query) use ($filters) {
            $query->where('category_id', $filters['category']);
         })
         ->when(!empty($filters['status']), function ($query) use ($filters) {
            $query->where('status', $filters['status']);
         })
         ->latest()
         ->paginate($perPage);
   }

   /**
    * Mengambil data spesifik artikel berdasarkan ID
    */
   public function findById(int $id): Post
   {
      return Post::findOrFail($id);
   }

   /**
    * Membuat postingan baru dengan kompresi gambar otomatis & Slug unik resmi
    */
   public function create(array $data, $imageFile = null): Post
   {
      DB::beginTransaction();

      try {
         // Otomatisasi Pembuatan Slug Unik saat insert artikel baru
         $slug = Str::slug($data['title']);
         if (Post::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . Str::lower(Str::random(5));
         }
         $data['slug'] = $slug;

         if ($imageFile) {
            $imagePath = $this->compressAndStore(
               file: $imageFile,
               folder: 'post-templates',
               width: 1200,
               quality: 80
            );

            $data['featured_image'] = $imagePath;
            unset($data['image']);
         }

         // =========================================================================
         // PERBAIKAN UTAMA: Suntikkan ID Admin/User yang sedang login saat ini
         // =========================================================================
         $data['user_id'] = auth()->id();

         $post = Post::create($data);

         DB::commit();
         return $post;
      } catch (Exception $e) {
         DB::rollBack();

         if (isset($imagePath)) {
            $this->deleteImage($imagePath);
         }

         throw $e;
      }
   }
   /**
    * Memperbarui postingan, mengelola pergantian gambar lama & Slug dinamis
    */
   public function update(int $id, array $data, $newImageFile = null): Post
   {
      DB::beginTransaction();

      try {
         $post = Post::findOrFail($id);

         // Perbarui Slug jika admin mengubah judul artikel saat mode edit
         $slug = Str::slug($data['title']);
         if (Post::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $slug . '-' . Str::lower(Str::random(5));
         }
         $data['slug'] = $slug;

         if ($newImageFile) {
            $newImagePath = $this->compressAndStore(
               file: $newImageFile,
               folder: 'post-templates',
               width: 1200,
               quality: 80
            );

            // Map data ke kolom gambar sampul artikel
            $data['featured_image'] = $newImagePath;
            unset($data['image']);

            // Lenyapkan gambar usang lama dari storage server agar VPS tidak bengkak
            if ($post->featured_image) {
               $this->deleteImage($post->featured_image);
            }
         }

         $post->update($data);

         DB::commit();
         return $post;
      } catch (Exception $e) {
         DB::rollBack();

         if (isset($newImagePath)) {
            $this->deleteImage($newImagePath);
         }

         throw $e;
      }
   }

   /**
    * Menghapus Postingan Sekaligen Membersihkan Fisik Berkas Gambar di Storage Disk
    * @param int $id
    * @return bool
    * @throws Exception
    */
   public function delete(int $id): bool
   {
      DB::beginTransaction();

      try {
         $post = Post::findOrFail($id);

         // 1. Kunci path gambar sebelum baris data di database dihancurkan
         $imagePath = $post->featured_image;

         // 2. Hapus data dari tabel database
         $deleted = $post->delete();

         // 3. Jika database sukses terhapus, bersihkan biner WebP di storage server via Trait
         if ($deleted && $imagePath) {
            $this->deleteImage($imagePath);
         }

         DB::commit();
         return true;
      } catch (Exception $e) {
         DB::rollBack();
         throw new Exception('Gagal menghapus artikel postingan: ' . $e->getMessage());
      }
   }
}
