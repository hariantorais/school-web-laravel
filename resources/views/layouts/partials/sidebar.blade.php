<?php
// Struktur menu dengan referensi nama ikon Heroicons yang bersih
$sidebarMenu = [
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
            [
                'name' => 'Donasi',
                'icon' => 'heart',
                'active_on' => ['admin.donations.index', 'admin.donations.transactions'],
                'has_children' => true,
                'id' => 'menu-donasi',
                'children' => [
                    [
                        'name' => 'List Donasi',
                        'route' => 'admin.donations.index',
                        'active_on' => ['admin.donations.index'],
                    ],
                    [
                        'name' => 'Riwayat Donasi',
                        'route' => 'admin.donations.transactions',
                        'active_on' => ['admin.donations.transactions'],
                    ],
                ],
            ],
        ],
    ],
];
?>

<aside
    class="hidden md:flex flex-col flex-shrink-0 text-slate-300 border-r border-slate-800 transition-all duration-300 ease-in-out bg-[var(--bg-sidebar)]"
    :class="sidebarOpen ? 'w-64' : 'w-20'">

    <div
        class="h-16 flex items-center px-6 border-b border-slate-800 bg-[var(--bg-sidebar-inner)]/40 overflow-hidden whitespace-nowrap">
        <div class="flex items-center gap-3 min-w-0">
            <div
                class="p-2 rounded-lg border border-teal-900/50 flex-shrink-0 bg-[var(--accent-muted)]/80 text-[var(--accent-text)]">
                <x-heroicon-o-academic-cap class="w-6 h-6" />
            </div>
            <span class="font-bold text-lg text-slate-100 tracking-wider transition-all duration-200"
                :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 invisible'">
                DH<span class="text-[var(--accent-text)]">BPN</span>
            </span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar" x-data="{ openMenus: [] }">
        @include('layouts.partials.sidebar-links', [
            'sidebarMenu' => $sidebarMenu,
            'isMobile' => false,
        ])
    </nav>
</aside>

<div x-show="mobileSidebarOpen" x-on:click="mobileSidebarOpen = false"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs md:hidden" style="display: none;"></div>

<aside x-show="mobileSidebarOpen" x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-50 w-64 text-slate-300 flex flex-col md:hidden bg-[var(--bg-sidebar)] border-r border-slate-800"
    style="display: none;">

    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800 bg-[var(--bg-sidebar-inner)]/40">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-[var(--accent-muted)] text-[var(--accent-text)] border border-teal-900/50">
                <x-heroicon-o-academic-cap class="w-6 h-6" />
            </div>
            <span class="font-bold text-lg text-slate-100 tracking-wider">DH<span
                    class="text-[var(--accent-text)]">BPN</span></span>
        </div>
        <button x-on:click="mobileSidebarOpen = false"
            class="text-slate-400 hover:text-slate-100 cursor-pointer p-1 rounded-lg hover:bg-slate-800/50 transition-colors">
            <x-heroicon-o-x-mark class="w-6 h-6" />
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar" x-data="{ openMenus: [] }">
        @include('layouts.partials.sidebar-links', [
            'sidebarMenu' => $sidebarMenu,
            'isMobile' => true,
        ])
    </nav>
</aside>
