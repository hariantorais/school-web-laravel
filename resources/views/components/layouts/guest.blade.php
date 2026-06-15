<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Masuk - Sistem Informasi Sekolah' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-center">

    <div class="py-12 px-4 sm:px-6 lg:px-8 w-full max-w-md mx-auto">

        <div class="text-center mb-8">
            <div
                class="mx-auto h-12 w-12 rounded-2xl bg-teal-900 text-teal-400 flex items-center justify-center shadow-sm border border-teal-800/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
            </div>
            <h2 class="mt-4 text-2xl font-extrabold text-slate-900 tracking-tight">
                Sistem Informasi Sekolah
            </h2>
            <p class="mt-1.5 text-xs text-slate-400 max-w-xs mx-auto leading-relaxed">
                Silakan masuk menggunakan akun resmi untuk mengelola berita, pengumuman, dan data akademik.
            </p>
        </div>

        {{ $slot }}

        <p class="mt-8 text-center text-xs text-slate-400">
            &copy; 2026 Tim IT Sekolah. All rights reserved.
        </p>
    </div>

</body>

</html>
