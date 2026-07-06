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

        @if ($showReadMore)
            <a href="/posts/{{ $post->slug }}" wire:navigate
                class="mt-auto text-[{{ $secondaryColor }}] font-bold text-sm inline-flex items-center hover:text-[{{ $primaryColor }}] transition-colors group">
                Baca Selengkapnya
                <svg class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        @endif
    </div>
</div>
