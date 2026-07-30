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

        // 🔥 Ambil nomor WhatsApp dari settings
        $whatsappNumber = get_settings('whatsapp_number');
        $whatsappMessage =
            get_settings('whatsapp_message') ?? 'Assalamu\'alaikum, saya ingin bertanya tentang pondok pesantren.';
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

    {{-- ======================================================================== --}}
    {{-- STYLE UNTUK FLOATING BUTTON & BACK TO TOP --}}
    {{-- ======================================================================== --}}
    <style>
        /* 🔥 FLOATING WHATSAPP BUTTON */
        .floating-wa {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 999;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            transition: bottom 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .floating-wa .wa-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #25D366;
            color: white;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
            position: relative;
            animation: wa-pulse 2s ease-in-out infinite;
        }

        .floating-wa .wa-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(37, 211, 102, 0.6);
        }

        .floating-wa .wa-button svg {
            width: 32px;
            height: 32px;
        }

        /* Tooltip WhatsApp */
        .floating-wa .wa-tooltip {
            position: absolute;
            right: 70px;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            color: white;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .floating-wa .wa-button:hover .wa-tooltip {
            opacity: 1;
            transform: translateX(0);
        }

        .floating-wa .wa-tooltip::after {
            content: '';
            position: absolute;
            right: -6px;
            top: 50%;
            transform: translateY(-50%);
            border-left: 6px solid rgba(0, 0, 0, 0.8);
            border-top: 6px solid transparent;
            border-bottom: 6px solid transparent;
        }

        /* Badge notifikasi */
        .floating-wa .wa-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #FF4444;
            color: white;
            font-size: 10px;
            font-weight: bold;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            animation: wa-badge-pulse 1.5s ease-in-out infinite;
        }

        /* Animasi Pulse WA */
        @keyframes wa-pulse {

            0%,
            100% {
                box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            }

            50% {
                box-shadow: 0 4px 40px rgba(37, 211, 102, 0.7);
            }
        }

        @keyframes wa-badge-pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* 🔥 BACK TO TOP BUTTON */
        .back-to-top {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #A31D1D;
            color: white;
            box-shadow: 0 4px 20px rgba(163, 29, 29, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px) scale(0.8);
            pointer-events: none;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .back-to-top:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 6px 30px rgba(163, 29, 29, 0.6);
            background: #8B1A1A;
        }

        .back-to-top svg {
            width: 24px;
            height: 24px;
            transition: transform 0.3s ease;
        }

        .back-to-top:hover svg {
            transform: translateY(-2px);
        }

        /* 🔥 Responsive */
        @media (max-width: 640px) {
            .floating-wa {
                right: 16px;
                bottom: 20px;
            }

            .floating-wa .wa-button {
                width: 52px;
                height: 52px;
            }

            .floating-wa .wa-button svg {
                width: 28px;
                height: 28px;
            }

            .back-to-top {
                width: 44px;
                height: 44px;
                right: 16px;
                bottom: 20px;
            }

            .back-to-top svg {
                width: 20px;
                height: 20px;
            }

            .floating-wa .wa-tooltip {
                display: none;
            }
        }

        @media (min-width: 641px) and (max-width: 1024px) {
            .floating-wa {
                right: 20px;
                bottom: 24px;
            }
        }
    </style>
</head>

<body class="antialiased selection:bg-[#A31D1D] selection:text-white">

    <!-- ========== HEADER & NAVIGATION ========== -->
    @include('components.layouts.partials.home.header')

    {{ $slot }}

    @include('components.layouts.partials.home.footer')

    {{-- ======================================================================== --}}
    {{-- FLOATING WHATSAPP BUTTON --}}
    {{-- ======================================================================== --}}
    <div class="floating-wa" id="floatingWA">
        <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($whatsappMessage) }}" target="_blank"
            rel="noopener noreferrer" class="wa-button" aria-label="Hubungi via WhatsApp">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
            </svg>
            <span class="wa-tooltip">Hubungi Kami</span>
        </a>
    </div>

    {{-- ======================================================================== --}}
    {{-- BACK TO TOP BUTTON --}}
    {{-- ======================================================================== --}}
    <button class="back-to-top" id="backToTop" aria-label="Kembali ke atas">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    {{-- ======================================================================== --}}
    {{-- SCRIPTS --}}
    {{-- ======================================================================== --}}
    @livewireScripts

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============================================================
            // ELEMENTS
            // ============================================================
            const backToTopBtn = document.getElementById('backToTop');
            const floatingWA = document.getElementById('floatingWA');

            // ============================================================
            // FUNGSI UPDATE POSISI
            // ============================================================
            function updateButtons() {
                const scrollY = window.scrollY || window.pageYOffset;
                const isMobile = window.innerWidth <= 640;
                const isTablet = window.innerWidth > 640 && window.innerWidth <= 1024;

                // Tentukan jarak bottom berdasarkan device
                let bottomDistance = 24;
                let waBottomDistance = 24;
                let waMoveUpDistance = 90;

                if (isMobile) {
                    bottomDistance = 20;
                    waBottomDistance = 20;
                    waMoveUpDistance = 80;
                } else if (isTablet) {
                    bottomDistance = 24;
                    waBottomDistance = 24;
                    waMoveUpDistance = 90;
                }

                // 🔥 Update posisi Back to Top
                if (scrollY > 300) {
                    // Tampilkan Back to Top
                    backToTopBtn.classList.add('visible');
                    backToTopBtn.style.bottom = bottomDistance + 'px';

                    // 🔥 WA naik
                    floatingWA.style.bottom = waMoveUpDistance + 'px';
                } else {
                    // Sembunyikan Back to Top
                    backToTopBtn.classList.remove('visible');

                    // 🔥 WA turun ke posisi semula
                    floatingWA.style.bottom = waBottomDistance + 'px';
                }
            }

            // ============================================================
            // EVENT LISTENERS
            // ============================================================

            // Scroll dengan throttle
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                if (scrollTimeout) {
                    cancelAnimationFrame(scrollTimeout);
                }
                scrollTimeout = requestAnimationFrame(updateButtons);
            });

            // Resize
            window.addEventListener('resize', function() {
                updateButtons();
            });

            // Load
            window.addEventListener('load', function() {
                updateButtons();
            });

            // ============================================================
            // BACK TO TOP - CLICK
            // ============================================================
            backToTopBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // ============================================================
            // WHATSAPP - TRACK CLICK
            // ============================================================
            const waButton = document.querySelector('.wa-button');
            if (waButton) {
                waButton.addEventListener('click', function() {
                    console.log('WhatsApp button clicked');
                });
            }

            // ============================================================
            // SMOOTH SCROLL UNTUK ANCHOR LINKS
            // ============================================================
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href === '#') return;

                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            console.log('✅ Floating buttons initialized');
        });
    </script>

</body>

</html>
