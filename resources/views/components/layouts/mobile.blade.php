<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>{{ $title ?? 'Daarul Huffadz' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-slate-100 antialiased font-['Plus_Jakarta_Sans',sans-serif]">

    {{-- 
        =========================================================================
        WRAPPER UTAMA: Menggunakan max-w-xl (576px) agar lega di laptop ala Kitabisa,
        namun tetap w-full (100%) otomatis saat diakses dari HP asli.
        =========================================================================
    --}}
    <div
        class="min-h-screen w-full max-w-xl mx-auto bg-[#FDFBF7] shadow-2xl shadow-slate-900/10 border-x border-slate-200/40 relative flex flex-col">

        {{-- 1. FIXED HEADER ACCORDING TO PARENT MAX-W-XL --}}
        <header
            class="fixed top-0 z-50 bg-[#FDFBF7]/85 backdrop-blur-md border-b border-slate-200/60 px-5 pt-[calc(0.75rem+env(safe-area-inset-top))] pb-3 flex items-center justify-between w-full max-w-xl mx-auto left-0 right-0">

            {{-- Tombol Kembali --}}
            <button onclick="history.back()"
                class="p-2 -ml-2 rounded-full active:bg-slate-100 transition-colors text-slate-700 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>

            {{-- Judul Halaman --}}
            <span
                class="text-xs font-black text-slate-800 uppercase tracking-widest truncate max-w-[320px] text-center">
                {{ $headerTitle ?? 'DAARUL HUFFADZ' }}
            </span>

            {{-- Aksi Kanan Dinamis --}}
            @hasSection('header-action')
                @yield('header-action')
            @else
                <div class="w-9"></div>
            @endif
        </header>

        {{-- 2. MAIN HUB CONTENT CONTAINER --}}
        <main
            class="flex-grow pt-[calc(3.75rem+env(safe-area-inset-top))] pb-[calc(6.5rem+env(safe-area-inset-bottom))]">
            {{ $slot }}
        </main>

        {{-- 3. FLOATING FIXED FOOTER ACTIONS --}}
        @hasSection('footer-action')
            <div
                class="fixed bottom-0 left-0 right-0 z-40 p-4 pb-[calc(1.25rem+env(safe-area-inset-bottom))] bg-gradient-to-t from-[#FDFBF7] via-[#FDFBF7]/95 to-transparent w-full max-w-xl mx-auto">
                @yield('footer-action')
            </div>
        @endif

    </div>

    @livewireScripts
</body>

</html>
