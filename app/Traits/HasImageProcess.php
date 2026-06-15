<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder; // KUNCI UTAMA v4: Import Class Encoder khusus

trait HasImageProcess
{
   /**
    * Kompres dan simpan gambar ke disk publik menggunakan Intervention Image v4 resmi.
    */
   public function compressAndStore($file, string $folder = 'blog-images', int $width = 1200, int $quality = 80): string
   {
      // 1. Inisialisasi Image Manager v4 dengan driver GD
      $manager = new ImageManager(new Driver());

      // 2. Membaca file gambar (Standard v4 memakai decode)
      $image = $manager->decode($file);

      // 3. Sizing adaptif v4: Mencegah pembesaran paksa jika gambar asli sudah kecil
      $image->scaleDown(width: $width);

      // 4. Buat nama file unik dengan ekstensi webp
      $filename = Str::random(40) . '.webp';
      $targetPath = $folder . '/' . $filename;

      // 5. KUNCI PERBAIKAN v4: Gunakan method ->encode() dan masukkan instance WebpEncoder
      //    Lalu panggil ->toString() untuk mendapatkan data biner murninya.
      $encodedStream = $image->encode(new WebpEncoder($quality))->toString();

      // 6. Simpan stream biner ke Storage Disk Publik Laravel
      Storage::disk('public')->put($targetPath, $encodedStream);

      return $targetPath;
   }
}
