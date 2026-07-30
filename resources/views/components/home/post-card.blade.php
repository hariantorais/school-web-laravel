@props([
    'post',
    'showCategory' => true,
    'showExcerpt' => true,
    'showDate' => true,
    'showReadMore' => true,
    'layout' => 'default',
    'primaryColor' => '#A31D1D',
    'secondaryColor' => '#1E293B',
])

@php
    $layoutClasses = [
        'default' => 'flex-col',
        'horizontal' => 'flex-row',
        'minimal' => 'flex-col gap-0',
    ];

    $imageSizes = [
        'default' => 'h-56',
        'horizontal' => 'h-48 w-48 shrink-0',
        'minimal' => 'h-40',
    ];

    $imageClass = $imageSizes[$layout] ?? 'h-56';
    $layoutClass = $layoutClasses[$layout] ?? 'flex-col';

    $imageUrl = $post->image_url ?? 'https://via.placeholder.com/400x300/1E293B/FFFFFF?text=No+Image';

    // 🔥 Cek apakah post memiliki video YouTube
    $hasVideo = !empty($post->youtube_url);

    // 🔥 Extract YouTube Video ID untuk thumbnail (jika diperlukan)
    $youtubeId = null;
    if ($hasVideo) {
        $url = $post->youtube_url;
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            $youtubeId = $matches[1];
        } else {
            parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $params);
            $youtubeId = $params['v'] ?? null;
        }
    }
@endphp

<div {{ $attributes->merge(['class' => "bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-200 hover:shadow-xl transition-all duration-300 group flex {$layoutClass} h-full"]) }}
    data-aos="fade-up">

    <!-- Image Section -->
    <div class="{{ $imageClass }} overflow-hidden relative {{ $layout === 'horizontal' ? 'rounded-l-2xl' : '' }}">
        @if ($showCategory && isset($post->category))
            <span
                class="absolute top-4 left-4 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-md z-10"
                style="background-color: {{ $primaryColor }}">
                {{ $post->category->name }}
            </span>
        @endif

        {{-- 🔥 BADGE VIDEO YOUTUBE - Pojok kiri bawah --}}
        @if ($hasVideo)
            <div class="absolute bottom-3 left-3 z-10">
                <span
                    class="inline-flex items-center gap-1.5 bg-red-600/95 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1.5 rounded-lg border border-white/20 shadow-lg">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                    </svg>
                    <span>Video</span>
                </span>
            </div>
        @endif

        {{-- 🔥 PLAY BUTTON OVERLAY - Muncul saat hover (opsional) --}}
        @if ($hasVideo)
            <div
                class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                <div
                    class="w-14 h-14 rounded-full bg-red-600/90 flex items-center justify-center shadow-2xl transform group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                    </svg>
                </div>
            </div>
        @endif

        <img src="{{ $imageUrl }}" alt="{{ $post->title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
    </div>

    <!-- Content Section -->
    <div class="p-6 flex flex-col flex-grow {{ $layout === 'horizontal' ? 'justify-center' : '' }}">
        @if ($showDate)
            <span class="text-xs font-semibold text-slate-400 block mb-2">
                {{ $post->published_at ? $post->published_at->isoFormat('D MMMM Y') : $post->created_at->isoFormat('D MMMM Y') }}
            </span>
        @endif

        <h3
            class="font-heading font-bold text-lg text-[{{ $secondaryColor }}] mb-3 hover:text-[{{ $primaryColor }}] transition-colors line-clamp-2 leading-snug">
            <a href="/posts/{{ $post->slug }}" wire:navigate>
                {{ $post->title }}
            </a>
        </h3>

        @if ($showExcerpt)
            <p class="text-slate-500 text-sm line-clamp-3 {{ $layout === 'minimal' ? 'mb-3' : 'mb-6' }}">
                {{ Str::limit(strip_tags($post->content), 120) }}
            </p>
        @endif

        {{-- 🔥 FOOTER CARD - Dengan indikator video --}}
        <div class="flex items-center justify-between mt-auto">
            @if ($showReadMore)
                <a href="/posts/{{ $post->slug }}" wire:navigate
                    class="text-[{{ $secondaryColor }}] font-bold text-sm inline-flex items-center hover:text-[{{ $primaryColor }}] transition-colors group">
                    Baca Selengkapnya
                    <svg class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3">
                        </path>
                    </svg>
                </a>
            @endif


        </div>
    </div>
</div>
