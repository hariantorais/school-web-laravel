<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Masuk - Sistem Informasi Sekolah' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Inter'] antialiased min-h-screen bg-white">

    <div class="flex min-h-screen">
        {{-- LEFT: Gambar --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#1E293B] overflow-hidden">
            {{-- Background pattern --}}
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.05]">
            </div>

            {{-- Decorative blur --}}
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#A31D1D]/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#D4AF37]/10 rounded-full blur-3xl"></div>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col items-center justify-center w-full p-12 text-center">
                <div class="max-w-md">
                    {{-- Logo besar --}}
                    <div class="mb-8">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo"
                            class="h-24 w-24 mx-auto object-contain">
                    </div>

                    <h1 class="text-4xl font-bold text-white mb-4">
                        {{ get_settings('school_name') ?? 'Pondok Pesantren' }}
                    </h1>

                    <div class="w-16 h-1 bg-[#D4AF37] mx-auto mb-4"></div>

                    <p class="text-slate-300 text-sm leading-relaxed">
                        {{ get_settings('school_tagline') ?? 'Mencetak generasi Qur\'ani yang unggul dan berakhlak mulia' }}
                    </p>


                </div>
            </div>
        </div>

        {{-- RIGHT: Form Login --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
            <div class="w-full max-w-md">
                {{-- Mobile Logo --}}
                <div class="lg:hidden text-center mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14 w-14 mx-auto object-contain">
                    <h2 class="mt-3 text-xl font-bold text-slate-900">
                        {{ get_settings('school_name') ?? 'Pondok Pesantren' }}
                    </h2>
                </div>

                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-slate-900">Masuk ke Akun</h3>
                    <p class="text-sm text-slate-500 mt-1">Silakan login untuk mengelola sistem</p>
                </div>

                {{-- Content --}}
                {{ $slot }}
            </div>
        </div>
    </div>

</body>

</html>
