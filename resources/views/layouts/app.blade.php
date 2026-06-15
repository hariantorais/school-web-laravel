<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Muted Teal & Slate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Anda bisa mengubah kode HEX di bawah ini untuk mengganti tema seluruh aplikasi instant */
        :root {
            --bg-main: #f8fafc;
            /* Slate 50 - Latar belakang utama sangat adem */
            --bg-sidebar: #0f172a;
            /* Slate 900 - Sidebar gelap berbasis biru redup */
            --bg-sidebar-inner: #020617;
            /* Slate 950 - Variasi gelap pekat */

            /* Warna Aksen Utama (Teal/Hijau Kebiruan yang Rileks di Mata) */
            --accent-primary: #0f766e;
            /* Teal 700 - Untuk Tombol Utama / CTA */
            --accent-hover: #115e59;
            /* Teal 800 - Efek Hover Tombol */
            --accent-muted: #134e4a;
            /* Teal 950 - Background Aktif Sidebar (Sangat Gelap) */
            --accent-text: #2dd4bf;
            /* Teal 400 - Teks Aktif / Icon Aktif */
            --accent-focus: rgba(15, 118, 110, 0.15);
            /* Ring focus shadow */
        }
    </style>
</head>

<body class="bg-[var(--bg-main)] text-slate-800 antialiased" x-data="{ sidebarOpen: true, profileMenuOpen: false, mobileSidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        @include('layouts.partials.sidebar')

        <div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm md:hidden"></div>
        <aside x-show="mobileSidebarOpen"
            class="fixed inset-y-0 left-0 z-50 w-64 text-slate-300 flex flex-col md:hidden bg-[var(--bg-sidebar)]">
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-[var(--accent-muted)] text-[var(--accent-text)]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                    <span class="font-bold text-lg text-slate-100">COOL<span
                            class="text-[var(--accent-text)]">CORE</span></span>
                </div>
                <button @click="mobileSidebarOpen = false" class="text-slate-400 hover:text-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5">
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium bg-[var(--accent-muted)] text-[var(--accent-text)] border border-teal-900/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z">
                        </path>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </nav>
        </aside>

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
                    <button class="p-2 text-slate-500 hover:bg-slate-100 rounded-full relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.07 6.07 0 00-1-3.59M12 3v1m0 16v1m0-1a2.002 2.002 0 002-2M12 21a2.002 2.002 0 01-2-2m0 0V5a3 3 0 116 0v9.158c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span
                            class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full ring-2 ring-white bg-[var(--accent-primary)]"></span>
                    </button>

                    <div class="h-6 w-px bg-slate-200"></div>

                    <div class="relative" @click.away="profileMenuOpen = false">
                        <button @click="profileMenuOpen = !profileMenuOpen"
                            class="flex items-center gap-2 focus:outline-none group">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold ring-2 ring-slate-200 group-hover:ring-[var(--accent-primary)] bg-[var(--accent-muted)] text-[var(--accent-text)] transition-all">
                                HR
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-slate-700 group-hover:text-slate-900 leading-none">
                                    Harianto Rais</p>
                                <p class="text-xs text-slate-400 mt-0.5">Senior Administrator</p>
                            </div>
                        </button>

                        <div x-show="profileMenuOpen"
                            class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg py-1 z-50 text-sm">
                            <a href="#" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">Profil Saya</a>
                            <hr class="border-slate-100 my-1">
                            <a href="#"
                                class="block px-4 py-2 font-medium text-[var(--accent-primary)] hover:bg-slate-50">Keluar
                                Sistem</a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8">


                {{ $slot }}


            </main>
        </div>
    </div>

    <x-ui.toast />

</body>

</html>
