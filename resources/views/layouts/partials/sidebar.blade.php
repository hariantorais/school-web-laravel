<?php
// Definisikan struktur menu bersarang (nested menu) sesuai kebutuhan proyek Anda
$sidebarMenu = [
    [
        'group' => 'Main Menu',
        'items' => [
            [
                'name' => 'Dashboard Overview',
                'route' => 'admin.dashboard.index',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>',
                'active_on' => ['admin.dashboard.index'],
                'has_children' => false,
            ],
        ],
    ],
    [
        'group' => 'Konten Akademik',
        'items' => [
            [
                'name' => 'Postingan',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-5M19 20H9m5-14H5m4 4H5m7 4H5"></path>',
                'active_on' => ['admin.posts.index', 'admin.posts.create', 'admin.posts.edit'], // Sinkron rute name lama & baru Anda
                'has_children' => true,
                'id' => 'menu-postingan',
                'children' => [['name' => 'List Postingan', 'route' => 'admin.posts.index', 'active_on' => ['admin.posts.index']], ['name' => 'Buat Postingan', 'route' => 'admin.posts.create', 'active_on' => ['admin.posts.create']]],
            ],
            // [
            //     'name' => 'Donasi',
            //     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
            //     'active_on' => ['donasi.campaign', 'donasi.laporan'],
            //     'has_children' => true,
            //     'id' => 'menu-donasi',
            //     'children' => [['name' => 'Buat Campaign', 'route' => 'donasi.campaign', 'active_on' => ['donasi.campaign']], ['name' => 'Laporan Keuangan', 'route' => 'donasi.laporan', 'active_on' => ['donasi.laporan']]],
            // ],
        ],
    ],
];
?>

<aside
    class="hidden md:flex flex-col flex-shrink-0 text-slate-300 border-r border-slate-800 transition-all duration-300 ease-in-out bg-[var(--bg-sidebar)]"
    :class="sidebarOpen ? 'w-64' : 'w-20'">

    <div class="h-16 flex items-center px-6 border-b border-slate-800 bg-[var(--bg-sidebar-inner)]/40">
        <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
            <div
                class="p-2 rounded-lg border border-teal-900/50 flex-shrink-0 bg-[var(--accent-muted)]/80 text-[var(--accent-text)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                    </path>
                </svg>
            </div>
            <span class="font-bold text-lg text-slate-100 tracking-wider transition-opacity duration-200"
                :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0'">
                COOL<span class="text-[var(--accent-text)]">CORE</span>
            </span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar" x-data="{ openMenus: [] }">

        @foreach ($sidebarMenu as $menuGroup)
            <div class="px-3 mb-2 text-[10px] font-semibold text-slate-500 uppercase tracking-widest block whitespace-nowrap overflow-hidden pt-4 first:pt-0"
                x-show="sidebarOpen" x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0">
                {{ $menuGroup['group'] }}
            </div>

            @foreach ($menuGroup['items'] as $item)
                <?php $isGroupActive = request()->routeIs($item['active_on']); ?>

                @if ($item['has_children'])
                    <div class="space-y-1" x-init="if ({{ $isGroupActive ? 'true' : 'false' }}) { openMenus.push('{{ $item['id'] }}') }">

                        <button
                            @click="openMenus.includes('{{ $item['id'] }}') ? openMenus = openMenus.filter(m => m !== '{{ $item['id'] }}') : openMenus.push('{{ $item['id'] }}')"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all group overflow-hidden whitespace-nowrap text-left {{ $isGroupActive ? 'text-[var(--accent-text)] bg-[var(--accent-muted)]/30 border border-teal-900/10' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100 border border-transparent' }}"
                            title="{{ $item['name'] }}">

                            <div class="flex items-center gap-3 min-w-0">
                                <svg class="w-5 h-5 flex-shrink-0 {{ $isGroupActive ? 'text-[var(--accent-text)]' : 'text-slate-500 group-hover:text-slate-300' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $item['icon'] !!}
                                </svg>
                                <span x-show="sidebarOpen"
                                    class="transition-opacity duration-200 truncate">{{ $item['name'] }}</span>
                            </div>

                            <svg x-show="sidebarOpen"
                                class="w-4 h-4 text-slate-500 transition-transform duration-200 flex-shrink-0"
                                :class="{ 'rotate-180 text-[var(--accent-text)]': openMenus.includes('{{ $item['id'] }}') }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="openMenus.includes('{{ $item['id'] }}') && sidebarOpen"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 transform -translate-y-1"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="pl-8 space-y-1 overflow-hidden">

                            @foreach ($item['children'] as $child)
                                <?php $isChildActive = request()->routeIs($child['active_on']); ?>

                                <a href="{{ Route::has($child['route']) ? route($child['route']) : '#' }}"
                                    wire:navigate
                                    class="block px-3 py-2 text-xs font-medium rounded-md transition-all truncate whitespace-nowrap {{ $isChildActive ? 'text-[var(--accent-text)] font-semibold bg-[var(--accent-muted)]/50' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/40' }}">
                                    &bull; {{ $child['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" wire:navigate
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all group overflow-hidden whitespace-nowrap {{ $isGroupActive ? 'bg-[var(--accent-muted)] text-[var(--accent-text)] border border-teal-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100 border border-transparent' }}"
                        title="{{ $item['name'] }}">
                        <svg class="w-5 h-5 flex-shrink-0 {{ $isGroupActive ? 'text-[var(--accent-text)]' : 'text-slate-500 group-hover:text-slate-300' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $item['icon'] !!}
                        </svg>
                        <span x-show="sidebarOpen"
                            class="transition-opacity duration-200 truncate">{{ $item['name'] }}</span>
                    </a>
                @endif
            @endforeach
        @endforeach

        <div class="px-3 pt-4 mb-2 text-[10px] font-semibold text-slate-500 uppercase tracking-widest block whitespace-nowrap overflow-hidden"
            x-show="sidebarOpen" x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0">
            Sistem
        </div>

        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-rose-400 hover:bg-rose-950/20 hover:text-rose-300 transition-all border border-transparent group overflow-hidden whitespace-nowrap text-left"
            title="Keluar Sistem">
            <svg class="w-5 h-5 flex-shrink-0 text-rose-400/60 group-hover:text-rose-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                </path>
            </svg>
            <span x-show="sidebarOpen" class="transition-opacity duration-200 truncate">Keluar Sistem</span>
        </button>

    </nav>

</aside>
