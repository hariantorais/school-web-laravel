<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostService
{
   /**
    * Membuat data postingan baru.
    */
   public function create(array $data, ?string $imagePath): Post
   {
      return Post::create([
         'user_id' => auth()->id() ?? 1,
         'category_id' => $data['category_id'],
         'title' => $data['title'],
         'slug' => Str::slug($data['title']) . '-' . Str::random(5),
         'featured_image' => $imagePath,
         'content' => $data['content'],
         'status' => $data['status'],
         'published_at' => $data['status'] === 'published' ? now() : null,
      ]);
   }

   /**
    * Memperbarui data postingan yang sudah ada.
    */
   public function update(Post $post, array $data, ?string $imagePath): Post
   {
      $updateData = [
         'category_id' => $data['category_id'],
         'title' => $data['title'],
         'slug' => Str::slug($data['title']) . '-' . Str::random(5),
         'content' => $data['content'],
         'status' => $data['status'],
         'published_at' => $data['status'] === 'published' && !$post->published_at ? now() : $post->published_at,
      ];

      if ($imagePath) {
         // Hapus gambar lama jika ada gambar baru yang masuk
         if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
         }
         $updateData['featured_image'] = $imagePath;
      }

      $post->update($updateData);
      return $post;
   }
}
