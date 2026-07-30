<?php

use Livewire\Volt\Component;
use App\Models\Post;
use App\Models\Category;
use Livewire\Attributes\Layout;

new class extends Component {
    public Post $post;

    /**
     * Menginisialisasi data berdasarkan slug yang dikirim dari rute URL
     */
    #[Layout('layouts.home')]
    public function mount(string $slug)
    {
        // 1. Ambil data postingan yang berstatus diterbitkan beserta relasi kategori dan penulisnya
        $this->post = Post::with(['category', 'user'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // 2. KUNCI PELACAKAN: Naikkan hitungan jumlah pembaca secara otomatis & aman
        $this->post->incrementViews();
    }

    /**
     * Menyediakan data pendukung eksternal untuk area Widget Sidebar
     */
    public function with(): array
    {
        return [
            // Mengambil 4 artikel terbaru untuk widget selain artikel yang sedang dibaca saat ini
            'recentPosts' => Post::where('status', 'published')->where('id', '!=', $this->post->id)->latest()->take(4)->get(),

            // Mengambil semua kategori untuk ditampilkan pada widget tag cloud
            'categories' => Category::all(),
        ];
    }

    /**
     * 🔥 Helper untuk mendapatkan ID YouTube dari URL
     */
    public function getYoutubeIdProperty(): ?string
    {
        if (!$this->post->youtube_url) {
            return null;
        }

        $url = $this->post->youtube_url;

        // Extract video ID dari berbagai format URL YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }

        // Coba parse dari URL
        parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $params);
        return $params['v'] ?? null;
    }

    /**
     * 🔥 Helper untuk mendapatkan embed URL dengan parameter pembatasan
     * Memastikan TIDAK ADA rekomendasi video lainnya
     */
    public function getYoutubeEmbedUrlProperty(): ?string
    {
        $videoId = $this->youtube_id;
        if (!$videoId) {
            return null;
        }

        // Parameter lengkap untuk memblokir semua rekomendasi
        return "https://www.youtube.com/embed/{$videoId}?" .
            http_build_query([
                'autoplay' => 0,
                'rel' => 0, // 🔥 MATIKAN REKOMENDASI VIDEO TERKAIT
                'modestbranding' => 1,
                'showinfo' => 0,
                'controls' => 1,
                'disablekb' => 1, // 🔥 NONAKTIFKAN KEYBOARD SHORTCUT
                'fs' => 0, // 🔥 NONAKTIFKAN FULLSCREEN
                'iv_load_policy' => 3, // 🔥 MATIKAN ANOTASI VIDEO
                'cc_load_policy' => 0,
                'hl' => 'id',
                'playsinline' => 1,
                'loop' => 1, // 🔥 LOOP VIDEO
                'playlist' => $videoId, // 🔥 HANYA VIDEO INI YANG DIPUTAR
                'widget_referrer' => url()->current(),
            ]);
    }
}; ?>

<div>

    @php
        $post = $this->post;
        $schoolName = get_settings('school_name') ?? 'Pondok Pesantren Daarul Huffadz';
        $postTitle = $post->title . ' - ' . $schoolName;
        $postExcerpt = Str::limit(strip_tags($post->content ?? ''), 160);
        $postImage = $post->featured_image
            ? asset('storage/' . $post->featured_image)
            : get_settings('school_logo') ?? asset('images/masjid.jpg');
        $postCategory = $post->category->name ?? 'Artikel';
        $postUrl = url()->current();
        $postAuthor = $post->user->name ?? 'Admin';
        $postDate = $post->published_at ?? $post->created_at;

        // 🔥 Cek apakah ada video YouTube
        $hasVideo = !empty($post->youtube_url);
        $youtubeEmbedUrl = $this->youtube_embed_url;
        $youtubeId = $this->youtube_id;
    @endphp

    @section('title', $postTitle)
    @section('meta_description', $postExcerpt)
    @section('meta_keywords', $post->title . ', pondok pesantren, ' . $postCategory . ', daarul huffadz, balikpapan')
    @section('canonical', $postUrl)

    @section('og_type', 'article')
    @section('og_url', $postUrl)
    @section('og_title', $postTitle)
    @section('og_description', $postExcerpt)
    @section('og_image', $postImage)

    @section('twitter_title', $postTitle)
    @section('twitter_description', $postExcerpt)
    @section('twitter_image', $postImage)

    @section('extra_meta')
        <meta property="article:published_time" content="{{ $postDate }}">
        <meta property="article:author" content="{{ $postAuthor }}">
        <meta property="article:section" content="{{ $postCategory }}">
        <meta name="robots" content="index, follow">
        @if ($post->featured_image)
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
        @endif
    @endsection

    {{-- HEADER HERO --}}
    <section class="relative pt-32 pb-16 lg:pt-40 lg:pb-24 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-[#1E293B] via-[#2D3A4F] to-[#1E293B]"></div>
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.05]">
            </div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#A31D1D]/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#D4AF37]/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
                <div
                    class="inline-flex items-center gap-2 bg-[#A31D1D]/90 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                    <span class="w-2 h-2 bg-[#D4AF37] rounded-full animate-pulse"></span>
                    <span class="text-xs font-semibold text-[#D4AF37] uppercase tracking-wider">
                        {{ $post->category->name }}
                    </span>
                </div>

                <h1
                    class="font-heading text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold text-white leading-tight">
                    {{ $post->title }}
                </h1>

                <div class="flex flex-wrap items-center justify-center gap-4 mt-6 text-slate-300 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $post->published_at ? $post->published_at->isoFormat('D MMMM Y') : $post->created_at->isoFormat('D MMMM Y') }}</span>
                    </div>
                    <div class="w-1 h-1 bg-slate-500 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ $post->user->name }}</span>
                    </div>
                    <div class="w-1 h-1 bg-slate-500 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>{{ number_format($post->views, 0, ',', '.') }} views</span>
                    </div>
                    {{-- 🔥 INDIKATOR VIDEO DI HEADER --}}
                    @if ($hasVideo)
                        <div class="w-1 h-1 bg-slate-500 rounded-full"></div>
                        <div class="flex items-center gap-1.5 text-[#D4AF37]">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                            <span class="text-xs font-medium">Video</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- KONTEN UTAMA --}}
    <section class="py-16 lg:py-24 relative bg-[#FDFBF7]">
        <div class="absolute inset-0 z-0">
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.015]">
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- KOLOM KIRI: KONTEN UTAMA --}}
                <div class="lg:col-span-2" data-aos="fade-right">

                    {{-- 🔥 MEDIA AREA: TAMPILKAN VIDEO ATAU GAMBAR --}}
                    @if ($hasVideo && $youtubeEmbedUrl)
                        {{-- VIDEO YOUTUBE DENGAN BLOKIR TOTAL --}}
                        <div
                            class="rounded-2xl overflow-hidden shadow-xl mb-8 border-2 border-[#A31D1D]/20 video-wrapper relative">
                            <div class="relative aspect-video bg-black">
                                <iframe class="absolute inset-0 w-full h-full" src="{{ $youtubeEmbedUrl }}"
                                    title="{{ $post->title }} - Video Resmi" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen loading="lazy"
                                    id="youtube-player">
                                </iframe>

                                {{-- 🔥 OVERLAY BLOKIR - MENUTUPI SELURUH AREA BAWAH --}}
                                <div class="absolute bottom-0 left-0 right-0" style="height: 40%; z-index: 30;">
                                    <!-- Elemen block yang benar-benar menutupi -->
                                    <div class="w-full h-full" style="background: transparent; pointer-events: none;">
                                    </div>
                                </div>

                                {{-- 🔥 OVERLAY KEDUA DENGAN POINTER-EVENTS: AUTO UNTUK BLOKIR KLIK --}}
                                <div class="absolute bottom-0 left-0 right-0"
                                    style="height: 35%; z-index: 31; pointer-events: auto; cursor: default;">
                                    <div class="w-full h-full flex items-end justify-center pb-4">

                                    </div>
                                </div>

                                {{-- 🔥 OVERLAY KETIGA - PALING ATAS UNTUK BLOKIR --}}
                                <div class="absolute bottom-0 left-0 right-0"
                                    style="height: 30%; z-index: 32; pointer-events: auto; cursor: default;">
                                    <div class="w-full h-full bg-transparent"></div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- TAMPILKAN GAMBAR SAMPUL (jika tidak ada video) --}}
                        <div class="relative rounded-2xl overflow-hidden shadow-xl mb-8">
                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                class="w-full h-auto object-cover max-h-[500px]">
                            <div
                                class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-black/50 to-transparent">
                            </div>
                        </div>
                    @endif

                    {{-- KONTEN ARTIKEL --}}
                    <div
                        class="prose prose-slate max-w-none prose-headings:font-heading prose-headings:text-[#1E293B] prose-p:text-slate-600 prose-p:leading-relaxed prose-strong:text-[#A31D1D] focus:outline-none">
                        {!! $post->content !!}
                    </div>

                    {{-- BAGIKAN --}}
                    <div class="mt-10 pt-6 border-t border-slate-200">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-slate-500">Bagikan:</span>
                                <div class="flex gap-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                        target="_blank" rel="noopener"
                                        class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-[#A31D1D] hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                                        </svg>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                                        target="_blank" rel="noopener"
                                        class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-[#A31D1D] hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                        </svg>
                                    </a>
                                    <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' - ' . url()->current()) }}"
                                        target="_blank" rel="noopener"
                                        class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-[#A31D1D] hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.244 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.717-1.454L0 24zm6.59-3.559c1.649.979 3.26 1.496 4.905 1.498 5.4 0 9.794-4.378 9.797-9.759.002-2.607-1.013-5.059-2.859-6.907C16.635 3.424 14.194 2.408 11.6 2.408c-5.4 0-9.794 4.378-9.797 9.758-.002 2.019.527 3.992 1.533 5.734L2.342 21.75l3.963-1.031z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: SIDEBAR --}}
                <div class="lg:col-span-1 space-y-8" data-aos="fade-left">

                    {{-- SEARCH --}}
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                        <h3 class="font-heading font-bold text-[#1E293B] mb-4">Cari Artikel</h3>
                        <form action="/posts" method="GET" class="relative">
                            <input type="text" name="search" placeholder="Cari berita pondok..."
                                class="w-full p-3 pr-10 border border-slate-200 rounded-xl focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition text-sm">
                            <button type="submit"
                                class="absolute right-3 top-3.5 text-slate-400 hover:text-[#A31D1D] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- ARTIKEL TERBARU --}}
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                        <h3 class="font-heading font-bold text-[#1E293B] mb-4">Artikel Terbaru</h3>
                        <div class="space-y-4">
                            @forelse($recentPosts as $recent)
                                <a href="/posts/{{ $recent->slug }}" wire:navigate
                                    class="flex gap-3 group items-center">
                                    <div
                                        class="w-16 h-16 rounded-xl overflow-hidden shrink-0 border border-slate-100 shadow-sm relative">
                                        <img src="{{ $recent->image_url }}" alt="{{ $recent->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                        @if (!empty($recent->youtube_url))
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white/80" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] text-slate-400">
                                            {{ $recent->created_at->isoFormat('D M Y') }}</p>
                                        <h4
                                            class="text-sm font-semibold text-[#1E293B] group-hover:text-[#A31D1D] transition line-clamp-2 leading-snug mt-0.5">
                                            {{ $recent->title }}
                                        </h4>
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada artikel warta lainnya.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- KATEGORI --}}
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                        <h3 class="font-heading font-bold text-[#1E293B] mb-4">Kategori</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($categories as $category)
                                <a href="/posts?category={{ $category->id }}" wire:navigate
                                    class="px-3 py-1.5 text-xs bg-slate-100 text-slate-600 rounded-full hover:bg-[#A31D1D] hover:text-white transition-all font-medium">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- QUOTE --}}
                    <div
                        class="bg-gradient-to-br from-[#1E293B] to-[#2D3A4F] rounded-2xl p-5 text-white border border-white/10">
                        <svg class="w-8 h-8 text-[#D4AF37]/50 mb-3" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>
                        <p class="text-sm italic leading-relaxed">"Sebaik-baik kalian adalah yang mempelajari Al-Qur'an
                            dan mengajarkannya."</p>
                        <p class="text-xs text-[#D4AF37] mt-3">— HR. Bukhari</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>

{{-- 🔥 CSS UNTUK MEMBLOKIR TOTAL SEMUA INTERAKSI --}}
<style>
    /* Video wrapper */
    .video-wrapper {
        position: relative;
        overflow: hidden;
    }

    /* Iframe video */
    .video-wrapper iframe {
        pointer-events: auto;
        position: relative;
        z-index: 1;
    }

    /* 🔥 BLOKIR OVERLAY DENGAN POINTER-EVENTS: AUTO */
    .video-wrapper .blocker-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 35%;
        z-index: 50;
        pointer-events: auto;
        cursor: default;
        background: transparent;
    }

    /* 🔥 OVERLAY KEDUA LEBIH TINGGI */
    .video-wrapper .blocker-overlay-2 {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40%;
        z-index: 51;
        pointer-events: auto;
        cursor: default;
        background: transparent;
    }

    /* 🔥 OVERLAY KETIGA - PALING ATAS */
    .video-wrapper .blocker-overlay-3 {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 30%;
        z-index: 52;
        pointer-events: auto;
        cursor: default;
        background: transparent;
    }

    /* Untuk mobile */
    @media (max-width: 640px) {
        .video-wrapper .blocker-overlay {
            height: 40%;
        }

        .video-wrapper .blocker-overlay-2 {
            height: 45%;
        }

        .video-wrapper .blocker-overlay-3 {
            height: 35%;
        }
    }
</style>

{{-- 🔥 JAVASCRIPT UNTUK BLOKIR INTERAKSI --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 🔥 1. Mencegah klik kanan pada iframe
            document.querySelectorAll('.video-wrapper iframe').forEach(function(iframe) {
                iframe.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    return false;
                });
            });

            // 🔥 2. Mencegah keyboard shortcut YouTube
            document.addEventListener('keydown', function(e) {
                const iframe = document.querySelector('.video-wrapper iframe');
                if (iframe && document.activeElement === iframe) {
                    const blockedKeys = ['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', ' ', 'Space',
                        'f', 'F'
                    ];
                    if (blockedKeys.includes(e.key) || e.key === ' ') {
                        e.preventDefault();
                        return false;
                    }
                }
            });

            // 🔥 3. Mencegah semua event pada area bawah
            const videoWrapper = document.querySelector('.video-wrapper');
            if (videoWrapper) {
                // Blokir semua event mouse di area bawah
                videoWrapper.addEventListener('click', function(e) {
                    const rect = this.getBoundingClientRect();
                    const clickY = e.clientY - rect.top;
                    const height = rect.height;

                    // Jika klik di 35% bawah video
                    if (clickY > height * 0.65) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                }, true);

                // Blokir mouse events lainnya
                ['mousedown', 'mouseup', 'mousemove', 'mouseover', 'mouseout'].forEach(function(eventType) {
                    videoWrapper.addEventListener(eventType, function(e) {
                        const rect = this.getBoundingClientRect();
                        const mouseY = e.clientY - rect.top;
                        const height = rect.height;

                        if (mouseY > height * 0.65) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        }
                    }, true);
                });
            }

            // 🔥 4. Blokir touch events untuk mobile
            document.querySelectorAll('.video-wrapper').forEach(function(wrapper) {
                wrapper.addEventListener('touchstart', function(e) {
                    const rect = this.getBoundingClientRect();
                    const touch = e.touches[0];
                    const touchY = touch.clientY - rect.top;
                    const height = rect.height;

                    if (touchY > height * 0.65) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                }, {
                    passive: false
                });
            });
        });
    </script>
@endpush
