<?php

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
