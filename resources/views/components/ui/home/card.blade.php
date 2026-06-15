<!-- resources/views/components/card.blade.php -->
@props([
    // Warna utama card
    'bgGradient' => 'from-[#A31D1D] via-[#8B1A1A] to-[#6B0F0F]',
    'accentColor' => '#D4AF37',
    'textColor' => 'white',

    // Header
    'icon' => '🏫',
    'title' => 'Jenjang Pendidikan',
    'subtitle' => 'Pendidikan Formal Berjenjang',

    // Konten
    'description' => null,

    // Type konten: 'grid', 'list', atau 'tags'
    'contentType' => 'grid', // grid, list, tags

    // Data untuk grid (2 kolom)
    'gridItems' => [
        ['name' => 'SMP', 'label' => 'Sekolah Menengah Pertama', 'age' => 'Usia 11-13 tahun'],
        ['name' => 'SMA', 'label' => 'Sekolah Menengah Atas', 'age' => 'Usia 14-17 tahun'],
    ],

    // Data untuk list (vertical items)
    'listItems' => [],

    // Data untuk tags (flex wrap)
    'tagItems' => [],

    // Footer
    'footerText' => null,
])

<div
    class="group bg-gradient-to-br {{ $bgGradient }} rounded-2xl p-5 sm:p-6 lg:p-8 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden flex flex-col h-full border border-white/10">

    <!-- CORAK: Garis gradasi atas -->
    <div
        class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-[{{ $accentColor }}] via-[{{ $accentColor }}]/50 to-transparent">
    </div>

    <!-- CORAK: Lingkaran blur di pojok -->
    <div
        class="absolute -top-20 -right-20 w-48 h-48 bg-gradient-to-br from-[{{ $accentColor }}]/15 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
    </div>
    <div
        class="absolute -bottom-20 -left-20 w-48 h-48 bg-gradient-to-tr from-white/5 to-transparent rounded-full blur-2xl">
    </div>

    <!-- CORAK: Garis sudut -->
    <div
        class="absolute top-0 left-0 w-16 h-16 border-t-2 border-l-2 border-[{{ $accentColor }}]/30 rounded-tl-2xl group-hover:border-[{{ $accentColor }}]/60 transition-all duration-300">
    </div>
    <div
        class="absolute bottom-0 right-0 w-16 h-16 border-b-2 border-r-2 border-[{{ $accentColor }}]/30 rounded-br-2xl group-hover:border-[{{ $accentColor }}]/60 transition-all duration-300">
    </div>

    <!-- CORAK: Grid titik -->
    <div class="absolute top-6 right-6 opacity-20 group-hover:opacity-30 transition-opacity duration-500">
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="5" cy="5" r="1" fill="{{ $accentColor }}" />
            <circle cx="15" cy="5" r="1" fill="{{ $accentColor }}" />
            <circle cx="25" cy="5" r="1" fill="{{ $accentColor }}" />
            <circle cx="35" cy="5" r="1" fill="{{ $accentColor }}" />
            <circle cx="5" cy="15" r="1" fill="{{ $accentColor }}" />
            <circle cx="15" cy="15" r="1" fill="{{ $accentColor }}" />
            <circle cx="25" cy="15" r="1" fill="{{ $accentColor }}" />
            <circle cx="35" cy="15" r="1" fill="{{ $accentColor }}" />
        </svg>
    </div>

    <div class="relative z-10 flex flex-col h-full">
        <!-- HEADER -->
        <div class="flex items-center space-x-3 mb-4">
            <div
                class="w-10 h-10 sm:w-12 sm:h-12 bg-[{{ $accentColor }}]/20 rounded-xl flex items-center justify-center group-hover:bg-[{{ $accentColor }}]/30 transition-all">
                <span class="text-xl sm:text-2xl">{{ $icon }}</span>
            </div>
            <div>
                <h3 class="font-heading text-lg sm:text-xl font-bold text-{{ $textColor }}">{{ $title }}
                </h3>
                <p class="text-[10px] sm:text-xs text-[{{ $accentColor }}]">{{ $subtitle }}</p>
            </div>
        </div>

        <!-- DESCRIPTION (opsional) -->
        @if ($description)
            <p
                class="text-{{ $textColor == 'white' ? 'slate-100' : 'slate-600' }} text-xs sm:text-sm mb-4 leading-relaxed">
                {{ $description }}
            </p>
        @endif

        <!-- CONTENT: TYPE GRID (untuk Jenjang Pendidikan) -->
        @if ($contentType === 'grid')
            <div class="grid grid-cols-2 gap-3 sm:gap-4 mt-2">
                @foreach ($gridItems as $item)
                    <div
                        class="bg-[{{ $accentColor }}]/10 rounded-xl p-3 sm:p-4 text-center hover:bg-[{{ $accentColor }}]/20 transition-all duration-300 border border-[{{ $accentColor }}]/20 group-hover:border-[{{ $accentColor }}]/50">
                        <span
                            class="text-2xl sm:text-3xl font-heading font-bold text-[{{ $accentColor }}]">{{ $item['name'] }}</span>
                        <p
                            class="text-[10px] sm:text-xs text-{{ $textColor == 'white' ? 'white/90' : 'gray-700' }} mt-1">
                            {{ $item['label'] }}</p>
                        @if (isset($item['age']))
                            <p class="text-[8px] sm:text-[10px] text-[{{ $accentColor }}]/80 mt-1 sm:mt-2">
                                {{ $item['age'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- CONTENT: TYPE LIST (untuk Sistem Kelulusan) -->
        @if ($contentType === 'list')
            <div class="space-y-2 sm:space-y-3 flex-grow">
                @foreach ($listItems as $item)
                    <div
                        class="group/item relative flex items-center space-x-3 bg-white/10 rounded-xl px-3 py-2 sm:px-4 sm:py-3 hover:bg-white/15 transition-all duration-300 border border-white/15 group-hover:border-[{{ $accentColor }}]/30">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-[{{ $accentColor }}]/10 to-transparent opacity-0 group-hover/item:opacity-100 transition-opacity duration-300">
                        </div>
                        <span class="relative z-10 text-xl sm:text-2xl">{{ $item['icon'] }}</span>
                        <div class="relative z-10">
                            <p class="font-bold text-xs sm:text-sm text-white">{{ $item['title'] }}</p>
                            <p class="text-[9px] sm:text-[11px] text-slate-300">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- CONTENT: TYPE TAGS (untuk Ekskul) -->
        @if ($contentType === 'tags')
            <div class="flex flex-wrap gap-2">
                @foreach ($tagItems as $item)
                    <div
                        class="group/tag relative px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 hover:scale-105 cursor-pointer shadow-sm border border-slate-200 bg-white/80 text-slate-700 hover:shadow-md hover:bg-white hover:border-[{{ $accentColor }}]/50 overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-[{{ $accentColor }}]/10 to-transparent opacity-0 group-hover/tag:opacity-100 transition-opacity duration-300">
                        </div>
                        <span class="relative z-10">{{ $item }}</span>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
