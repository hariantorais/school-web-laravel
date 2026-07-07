<?php

use App\Models\Institution;
use App\Models\Post;
use Illuminate\Support\Str;

function statistics()
{
   return [];
}

function subjects()
{
   return [
      ['icon' => '📐', 'name' => 'Matematika', 'curriculum' => 'Kurikulum'],
      ['icon' => '🔬', 'name' => 'IPA', 'curriculum' => 'Kurikulum'],
      ['icon' => '🇮🇩', 'name' => 'Bahasa Indonesia', 'curriculum' => 'Kurikulum']
   ];
}

if (!function_exists('get_settings')) {
   function get_settings($key = null)
   {
      static $settings = null;

      if ($settings === null) {
         $institution = Institution::first();

         $settings = [
            // Informasi Dasar
            'school_name' => $institution->name ?? 'Pondok Pesantren Daarul Huffadz Balikpapan',
            'school_logo' => asset('images/logo.png'),
            'school_favicon' => asset('images/favicon.png'),
            'profile_video_url' => $institution->profile_video_url ?? null,

            // Visi & Misi
            'school_vision' => $institution->vision_list ?? [],
            'school_mission' => $institution->mission_list ?? [],
            'school_motto' => $institution->motto ?? 'Taqwa, Ilmu, dan Amal Shalih',
            'school_tagline' => $institution->tagline ?? "Mewujudkan generasi penghafal Al-Qur'an berakhlak mulia...",

            // Deskripsi & Sejarah
            'school_description' => $institution->description ?? '',
            'school_history' => $institution->history ?? '',

            // Kontak
            'school_address' => $institution->address ?? 'Jl. Flamboyan RT 64 Perum Batu Ampar Lestari, Balikpapan.',
            'school_email' => $institution->email ?? 'info@daarulhuffadzbalikpapan.com',
            'phone' => $institution->whatsapp ?? '6281216140764',
            'whatsapp' => $institution->whatsapp ?? '6281216140764',
            'whatsapp_2' => $institution->whatsapp_2 ?? null,

            // Media Sosial
            'facebook' => $institution->facebook ?? null,
            'instagram' => $institution->instagram ?? null,
            'youtube' => $institution->youtube ?? null,
            'twitter' => $institution->twitter ?? null,
            'tiktok' => $institution->tiktok ?? null,

            // Website
            'website' => $institution->website ?? null,

            // SEO
            'meta_title' => $institution->meta_title ?? $institution->name ?? 'Pondok Pesantren Daarul Huffadz',
            'meta_description' => $institution->meta_description ?? 'Pondok Pesantren Daarul Huffadz Balikpapan - Pendidikan Islam Unggul',
            'meta_keywords' => $institution->meta_keywords ?? 'pondok pesantren, tahfidz, balikpapan',

            // Pengaturan
            'timezone' => $institution->timezone ?? 'Asia/Jakarta',
            'locale' => $institution->locale ?? 'id',

            // Informasi Tambahan
            'established_year' => $institution->established_year ?? 2015,
            'accreditation' => $institution->accreditation ?? 'Terakreditasi Unggul',

            // ===== DATA STATISTIK =====
            'total_students' => $institution->total_students ?? 0,
            'total_alumni' => $institution->total_alumni ?? 0,
            'total_teachers' => $institution->total_teachers ?? 0,
         ];
      }

      if ($key !== null) {
         return $settings[$key] ?? null;
      }

      return $settings;
   }
}

function idr($value)
{
   return number_format($value, 0, ',', '.');
}

function clean_trix(?string $content, int $limit = 120, string $end = '...'): string
{
   if (blank($content)) {
      return '';
   }

   // 1. Decode entitas HTML seperti &nbsp; menjadi karakter normal
   $decoded = html_entity_decode($content, ENT_QUOTES, 'UTF-8');

   // 2. Bersihkan seluruh tag HTML murni
   $cleanText = strip_tags($decoded);

   // 3. Potong teks sesuai batas limit karakter yang diinginkan
   return Str::limit($cleanText, $limit, $end);
}


if (!function_exists('getInitials')) {
   function getInitials($name = null)
   {

      if (empty($name)) {
         return '?';
      }

      $words = explode(' ', $name);
      $initials = '';

      foreach ($words as $word) {
         if (!empty($word)) {
            $initials .= strtoupper($word[0]);
         }
      }

      // Ambil maksimal 2 huruf
      return substr($initials, 0, 2);
   }
}
