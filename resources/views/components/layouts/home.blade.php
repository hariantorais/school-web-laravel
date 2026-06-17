<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Pesantren Tahfidz Daarul Huffadz Balikpapan</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
