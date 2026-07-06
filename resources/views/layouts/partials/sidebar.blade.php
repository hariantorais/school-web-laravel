<?php
// Struktur menu dengan referensi nama ikon Heroicons yang bersih
$sidebarMenu = navigations();
?>

{{-- ============================================================ --}}
{{-- DESKTOP SIDEBAR --}}
{{-- ============================================================ --}}
<aside
    class="hidden md:flex flex-col flex-shrink-0 text-slate-300 border-r border-slate-800 transition-all duration-300 ease-in-out bg-[var(--bg-sidebar)]"
    :class="sidebarOpen ? 'w-64' : 'w-20'">

    {{-- Header --}}
    <div class="h-16 flex items-center px-6 border-b border-slate-800 bg-[var(--bg-sidebar-inner)]/40 overflow-hidden">
        <div class="flex items-center gap-3 min-w-0 w-full">
            {{-- Logo --}}
            @php
                $logo = get_settings('school_logo');
                $name = get_settings('school_name');
                $initial = $name ? substr($name, 0, 2) : 'PP';
            @endphp

            @if ($logo && !str_contains($logo, 'default-logo.png'))
                <img src="{{ $logo }}" alt="{{ $name }}"
                    class="w-9 h-9 rounded-lg object-cover border border-slate-700/50 flex-shrink-0 bg-white p-0.5">
            @else
                <div
                    class="w-9 h-9 rounded-lg border border-teal-900/50 flex-shrink-0 bg-white text-[var(--accent-text)] flex items-center justify-center text-sm font-bold">
                    {{ $initial }}
                </div>
            @endif

            {{-- Nama Desktop --}}
            <span
                class="font-semibold text-[11px] sm:text-xs text-slate-100 tracking-wider leading-tight line-clamp-2 max-w-[150px]">
                {{ $name }}
            </span>
        </div>
    </div>

    {{-- Menu Desktop --}}
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar" x-data="{ openMenus: [] }">
        @include('layouts.partials.sidebar-links', [
            'sidebarMenu' => $sidebarMenu,
            'isMobile' => false,
        ])
    </nav>
</aside>

{{-- ============================================================ --}}
{{-- MOBILE OVERLAY --}}
{{-- ============================================================ --}}
<div x-show="mobileSidebarOpen" x-on:click="mobileSidebarOpen = false"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs md:hidden" style="display: none;">
</div>

{{-- ============================================================ --}}
{{-- MOBILE SIDEBAR --}}
{{-- ============================================================ --}}
<aside x-show="mobileSidebarOpen" x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 z-50 w-64 text-slate-300 flex flex-col md:hidden bg-[var(--bg-sidebar)] border-r border-slate-800"
    style="display: none;">

    {{-- Header Mobile --}}
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800 bg-[var(--bg-sidebar-inner)]/40">
        <div class="flex items-center gap-3">
            {{-- Logo Mobile --}}
            @php
                $logo = get_settings('school_logo');
                $name = get_settings('school_name');
                $initial = $name ? substr($name, 0, 2) : 'PP';
            @endphp

            @if ($logo && !str_contains($logo, 'default-logo.png'))
                <img src="{{ $logo }}" alt="{{ $name }}"
                    class="w-9 h-9 rounded-lg object-cover border border-slate-700/50 flex-shrink-0 bg-white p-0.5">
            @else
                <div
                    class="w-9 h-9 rounded-lg bg-white text-[var(--accent-text)] border border-teal-900/50 flex items-center justify-center text-sm font-bold">
                    {{ $initial }}
                </div>
            @endif

            {{-- Nama Mobile --}}
            <span class="font-semibold text-sm text-slate-100 tracking-wider leading-tight line-clamp-2 max-w-[150px]">
                {{ $name }}
            </span>
        </div>

        {{-- Tombol Close --}}
        <button x-on:click="mobileSidebarOpen = false"
            class="text-slate-400 hover:text-slate-100 cursor-pointer p-1 rounded-lg hover:bg-slate-800/50 transition-colors">
            <x-heroicon-o-x-mark class="w-6 h-6" />
        </button>
    </div>

    {{-- Menu Mobile --}}
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar" x-data="{ openMenus: [] }">
        @include('layouts.partials.sidebar-links', [
            'sidebarMenu' => $sidebarMenu,
            'isMobile' => true,
        ])
    </nav>
</aside>
