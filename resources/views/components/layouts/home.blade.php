<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pondok Pesantren Tahfidz Daarul Huffadz Balikpapan</title>
    <meta name="description"
        content="Pondok Pesantren Tahfidz Daarul Huffadz Balikpapan. Menempa generasi Qurani yang berakhlak mulia, cerdas, dan hafal Al-Quran di Balikpapan. Hubungi kami untuk pendaftaran santri baru.">
    <meta name="keywords"
        content="pondok pesantren balikpapan, tahfidz quran balikpapan, daarul huffadz balikpapan, pesantren tahfidz, rumah tahfidz balikpapan">
    <meta name="author" content="Pondok Pesantren Daarul Huffadz Balikpapan">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Pondok Pesantren Tahfidz Daarul Huffadz Balikpapan">
    <meta property="og:description"
        content="Membentuk generasi penghafal Al-Quran yang unggul dan berkarakter di Balikpapan. Pelajari program pendidikan dan kurikulum kami.">
    <meta property="og:image" content="{{ asset('images/masjid.jpg') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

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
