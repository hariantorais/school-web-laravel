@props(['donation'])

@php
    $isArr = is_array($donation);
    $title = $isArr ? $donation['title'] : $donation->title;
    $slug = $isArr ? $donation['slug'] : $donation->slug;
    $imagePath = $isArr ? $donation['image_path'] : $donation->image_path;
    $isActive = $isArr ? $donation['is_active'] : $donation->is_active;
    $currentAmount = $isArr ? $donation['current_amount'] : $donation->current_amount;
    $percentage = $isArr ? $donation['percentage'] : $donation->percentage;
    $daysLeft = $isArr ? $donation['days_left'] : $donation->days_left;
    $categoryName = $isArr
        ? $donation['category_name'] ?? 'Daarul Huffadz'
        : $donation->category?->name ?? 'Daarul Huffadz';

    $imageUrl = $imagePath
        ? asset('storage/' . $imagePath)
        : 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb8?auto=format&fit=crop&w=600&q=80';
@endphp

<a href="{{ $isActive ? url('/donasi/' . $slug) : '#' }}" wire:navigate
    class="relative w-[220px] flex-shrink-0 rounded-lg bg-white shadow-[0_2px_8px_rgba(152,152,152,0.15)] block h-full flex flex-col {{ !$isActive ? 'opacity-75 cursor-not-allowed' : '' }}">

    {{-- BAR SISA HARI MELAYANG (Pita Tipis Elegan di Sudut Kiri) --}}
    @if ($isActive && $daysLeft !== null)
        <div
            class="absolute left-0 top-0 rounded-br-lg rounded-tl-lg bg-[#EBF7FC] px-2 py-0.5 z-10 border-b border-r border-[#10A8E5]/10">
            <span class="block text-[9px] font-bold leading-relaxed text-[#10A8E5] font-sans">
                {{ (int) $daysLeft }} hari lagi
            </span>
        </div>
    @endif

    {{-- AREA SAMPUL GAMBAR --}}
    <div class="relative h-[120px] w-[220px] overflow-hidden shrink-0">
        <img src="{{ $imageUrl }}" alt="{{ $title }}"
            class="h-[120px] w-[220px] rounded-tl-lg rounded-tr-lg object-cover" loading="lazy">
    </div>

    {{-- AREA KONTEN DATA (Mengadopsi Pola Indah dari Recurring Card) --}}
    <div class="p-3 flex flex-col flex-grow justify-between bg-white">
        <div class="space-y-1">
            {{-- Inisiator & Lencana Verifikasi Biru --}}
            <div class="flex items-center min-w-0">
                <span
                    class="inline-block overflow-hidden text-ellipsis whitespace-nowrap text-[11px] font-medium text-slate-500 max-w-[160px]">
                    {{ $categoryName }}
                </span>

            </div>

            {{-- Judul Program Kampanye --}}
            <h3
                class="line-clamp-2 block h-8 overflow-hidden break-words text-[12px] font-semibold text-slate-800 leading-snug">
                {{ $title }}
            </h3>

            {{-- Nominal Finansial (Dibuat Mendatar/Baseline Mendampingi Kata Tersedia) --}}
            <div class="flex items-baseline gap-1 pt-1">
                <span class="text-[10px] text-slate-400">Terkumpul</span>
                <span class="text-[12px] font-bold text-slate-800 tracking-tight">
                    Rp{{ number_format($currentAmount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Progress Bar Proporsional (Satu Aliran dengan Struktur SVG Indah Anda) --}}
        <div class="pt-2">
            <svg width="100%" height="4" aria-label="progressBar">
                <rect x="0" rx="2" width="100%" height="100%" fill="#E8E8E8"></rect>
                <rect x="0" rx="2" width="{{ $percentage }}%" height="100%" fill="#10A8E5"></rect>
            </svg>
        </div>
    </div>
</a>
