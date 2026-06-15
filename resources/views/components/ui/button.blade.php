@props([
    'variant' => 'primary', // primary, secondary, danger, outline
    'size' => 'md', // sm, md, lg
    'href' => null, // Jika diisi, otomatis merender tag <a> bukan <button>
    'loading' => null, // Jika diisi target method-nya (cth: "save"), akan memicu loading indicator Livewire
])

@php
    // 1. Base Class (Gaya Dasar Tombol)
    $baseClasses =
        'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    // 2. Varian Warna Menggunakan Variabel :root Anda
    $variants = [
        'primary' =>
            'bg-[var(--accent-primary)] hover:bg-[var(--accent-hover)] text-white shadow-sm focus:ring-[var(--accent-primary)]',
        'secondary' =>
            'bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-sm focus:ring-slate-500',
        'danger' => 'bg-rose-600 hover:bg-rose-700 text-white shadow-sm focus:ring-rose-500',
        'outline' =>
            'bg-transparent border border-[var(--accent-primary)] text-[var(--accent-primary)] hover:bg-[var(--accent-focus)] focus:ring-[var(--accent-primary)]',
    ];

    // 3. Varian Ukuran
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs gap-1.5',
        'md' => 'px-4 py-2.5 text-sm gap-2',
        'lg' => 'px-5 py-3 text-base gap-2.5',
    ];

    $classes = implode(' ', [$baseClasses, $variants[$variant] ?? $variants['primary'], $sizes[$size] ?? $sizes['md']]);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}
        @if ($loading) wire:loading.attr="disabled" @endif>

        @if ($loading)
            <svg wire:loading @if ($loading !== 'true') wire:target="{{ $loading }}" @endif
                class="animate-spin h-4 w-4 text-current" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        @endif

        <span @if ($loading && $loading !== 'true') wire:loading.remove wire:target="{{ $loading }}" @endif
            class="inline-flex items-center gap-inherit">
            {{ $slot }}
        </span>

        @if ($loading && $loading !== 'true')
            <span wire:loading wire:target="{{ $loading }}">Memproses...</span>
        @endif
    </button>
@endif
