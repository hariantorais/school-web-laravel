<?php

function navigations()
{
   return [
      [
         'group' => 'Main Menu',
         'items' => [
            [
               'name' => 'Dashboard',
               'route' => 'admin.dashboard.index',
               'icon' => 'home',
               'active_on' => ['admin.dashboard.index'],
               'has_children' => false,
            ],
         ],
      ],
      [
         'group' => 'Konten',
         'items' => [
            // Postingan
            [
               'name' => 'Postingan',
               'icon' => 'document-text',
               'active_on' => ['admin.posts.index', 'admin.posts.create', 'admin.posts.edit'],
               'has_children' => true,
               'id' => 'menu-postingan',
               'children' => [
                  [
                     'name' => 'List Postingan',
                     'route' => 'admin.posts.index',
                     'active_on' => ['admin.posts.index'],
                  ],
                  [
                     'name' => 'Buat Postingan',
                     'route' => 'admin.posts.create',
                     'active_on' => ['admin.posts.create'],
                  ],
               ],
            ],

            // Testimoni
            [
               'name' => 'Testimoni',
               'icon' => 'chat-bubble-left-right',
               'route' => 'admin.testimonials.index',
               'active_on' => ['admin.testimonials.index'],
               'has_children' => false,
            ],
         ],
      ],
      [
         'group' => 'Pengaturan',
         'items' => [

            [
               'name' => 'Pengguna',
               'icon' => 'user-group',
               'route' => 'admin.users.index',
               'active_on' => ['admin.users.index'],
               'has_children' => false,
            ],
            // Institusi
            [
               'name' => 'Institusi',
               'icon' => 'building-office',
               'route' => 'admin.institution.form',
               'active_on' => ['admin.institution.form'],
               'has_children' => false,
            ],


         ],
      ],
   ];
}
