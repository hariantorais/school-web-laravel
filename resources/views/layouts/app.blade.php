<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

     <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.ico') }}">
    

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-[var(--bg-main)] text-slate-800 antialiased" x-data="{ sidebarOpen: true, profileMenuOpen: false, mobileSidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col h-screen overflow-hidden">

            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-30">
                <div class="flex items-center gap-4">
                    <button @click="mobileSidebarOpen = true"
                        class="p-2 -ml-2 rounded-md text-slate-500 hover:bg-slate-100 md:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="hidden md:block p-2 -ml-2 rounded-md text-slate-500 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h8m-8 6h16"></path>
                        </svg>
                    </button>
                    <div class="relative hidden sm:block w-64">
                        <span
                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" placeholder="Cari data..."
                            class="w-full pl-9 pr-4 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-focus)] focus:border-[var(--accent-primary)] transition-all">
                    </div>
                </div>

                <div class="flex items-center gap-4">


                    <div class="h-6 w-px bg-slate-200"></div>

                    {{-- Profile Dropdown --}}
                    <div class="relative" @click.away="profileMenuOpen = false">
                        <button @click="profileMenuOpen = !profileMenuOpen"
                            class="flex items-center gap-2 focus:outline-none group">
                            @php
                                $name = auth()->user()->name;
                            @endphp

                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold ring-2 ring-slate-200 group-hover:ring-[var(--accent-primary)] bg-[var(--accent-muted)] text-[var(--accent-text)] transition-all">
                                {{ getInitials($name) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-slate-700 group-hover:text-slate-900 leading-none">
                                    {{ $name }}
                                </p>
                            </div>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="profileMenuOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg py-1 z-50 text-sm">

                            {{-- pengaturan institusi --}}

                            <a href="{{ route('admin.institution.form') }}" wire:navigate
                                class="block px-4 py-2 hover:bg-slate-50 transition-colors duration-150 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                    </path>
                                </svg>
                                Pengaturan
                            </a>

                            <hr class="border-slate-100 my-1">
                            <button type="button" @click="$dispatch('open-logout-modal')"
                                class="w-full text-left px-4 py-2 font-medium text-red-600 hover:bg-red-50 transition-colors duration-150 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Keluar Sistem
                            </button>

                            {{-- Hidden Logout Form --}}
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">{{ $title ?? '' }}</h1>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $subhead ?? '' }}
                        </p>
                    </div>
                    <div>
                        {{ $headerAction ?? '' }}
                    </div>
                </div>

                {{ $slot }}
            </main>
        </div>
    </div>

    <x-ui.toast />
    <x-ui.modal-logout />
    @livewireScripts
</body>

</html>
