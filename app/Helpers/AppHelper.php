<?php

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

function get_settings($value)
{
   if ($value === 'phone') {
      return '6281216140764';
   }

   return '';
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
