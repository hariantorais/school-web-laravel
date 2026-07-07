<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- ======================================================================== --}}
    {{-- DEFAULT DATA DARI DATABASE --}}
    {{-- ======================================================================== --}}
    @php
        $schoolName = get_settings('school_name') ?? 'Pondok Pesantren Daarul Huffadz Balikpapan';
        $defaultTitle = get_settings('meta_title') ?? $schoolName;
        $defaultDescription =
            get_settings('meta_description') ??
            'Pondok Pesantren Tahfidz Daarul Huffadz Balikpapan. Menempa generasi Qurani yang berakhlak mulia, cerdas, dan hafal Al-Quran di Balikpapan. Hubungi kami untuk pendaftaran santri baru.';
        $defaultKeywords =
            get_settings('meta_keywords') ??
            'pondok pesantren balikpapan, tahfidz quran balikpapan, daarul huffadz balikpapan, pesantren tahfidz, rumah tahfidz balikpapan';
        $defaultAuthor = $schoolName;
        $defaultOgImage = get_settings('school_logo') ?? asset('images/masjid.jpg');
        $defaultFavicon = get_settings('school_favicon') ?? asset('images/favicon.png');
    @endphp

    {{-- ======================================================================== --}}
    {{-- TITLE (Bisa di-override per halaman) --}}
    {{-- ======================================================================== --}}
    <title>@yield('title', $defaultTitle)</title>

    {{-- ======================================================================== --}}
    {{-- META DESCRIPTION (Bisa di-override per halaman) --}}
    {{-- ======================================================================== --}}
    <meta name="description" content="@yield('meta_description', $defaultDescription)">

    {{-- ======================================================================== --}}
    {{-- META KEYWORDS (Bisa di-override per halaman) --}}
    {{-- ======================================================================== --}}
    <meta name="keywords" content="@yield('meta_keywords', $defaultKeywords)">

    {{-- ======================================================================== --}}
    {{-- META AUTHOR --}}
    {{-- ======================================================================== --}}
    <meta name="author" content="@yield('meta_author', $defaultAuthor)">

    {{-- ======================================================================== --}}
    {{-- CANONICAL URL --}}
    {{-- ======================================================================== --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- ======================================================================== --}}
    {{-- OPEN GRAPH (OG) TAGS --}}
    {{-- ======================================================================== --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', $defaultTitle)">
    <meta property="og:description" content="@yield('og_description', $defaultDescription)">
    <meta property="og:image" content="@yield('og_image', $defaultOgImage)">
    <meta property="og:site_name" content="{{ $schoolName }}">

    {{-- ======================================================================== --}}
    {{-- TWITTER CARD --}}
    {{-- ======================================================================== --}}
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', $defaultTitle)">
    <meta name="twitter:description" content="@yield('twitter_description', $defaultDescription)">
    <meta name="twitter:image" content="@yield('twitter_image', $defaultOgImage)">

    {{-- ======================================================================== --}}
    {{-- FAVICON & APPLE TOUCH ICON --}}
    {{-- ======================================================================== --}}
    <link rel="icon" type="image/png" href="@yield('favicon', $defaultFavicon)">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="@yield('apple_touch_icon', $defaultFavicon)">

    {{-- ======================================================================== --}}
    {{-- EXTRA META (Untuk custom per halaman, misal: artikel, robot, dll) --}}
    {{-- ======================================================================== --}}
    @yield('extra_meta')

    {{-- ======================================================================== --}}
    {{-- FONTS --}}
    {{-- ======================================================================== --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    {{-- ======================================================================== --}}
    {{-- ASSETS --}}
    {{-- ======================================================================== --}}
    @vite(['resources/css/home.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="antialiased selection:bg-[#A31D1D] selection:text-white">


    <!-- ========== HEADER & NAVIGATION ========== -->
    @include('components.layouts.partials.home.header')


    {{ $slot }}

    @include('components.layouts.partials.home.footer')


</body>

@livewireScripts

</html>
