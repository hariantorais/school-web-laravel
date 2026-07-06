<?php

use Livewire\Volt\Component;
use App\Models\Donation;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function donations()
    {
        return Donation::with(['category'])
            ->where('is_active', true)
            ->where('type', '!=', 'one_time')
            ->latest()
            ->take(8) // Batasi muatan feed horizontal
            ->get()
            ->map(function ($d) {
                return [
                    'id' => $d->id,
                    'title' => $d->title,
                    'slug' => $d->slug,
                    'image_path' => $d->image_path,
                    'is_active' => $d->is_active,
                    'current_amount' => $d->current_amount,
                    'type' => $d->type,
                    'category_name' => $d->category?->name ?? 'Umum',
                ];
            })
            ->toArray();
    }
}; ?>

<div>
    @if (count($this->donations) > 0)
        <section class="space-y-3">
            {{-- Header Row Kategori --}}
            <div class="flex items-center justify-between border-b border-slate-100 pb-2 mx-5">
                <h2 class="text-[13px] font-black text-slate-900 tracking-tight uppercase flex items-center gap-1.5">
                    <span class="w-1.5 h-3.5 rounded-full bg-emerald-500 inline-block"></span>
                    Program Berkelanjutan
                </h2>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            </div>

            {{-- Slider Wrapper dengan Kendali Kursor Geser --}}
            <div x-data="{
                showLeft: false,
                showRight: true,
                checkScroll() {
                    let el = $refs.scrollContainer;
                    this.showLeft = el.scrollLeft > 10;
                    this.showRight = el.scrollLeft < (el.scrollWidth - el.clientWidth - 10);
                },
                scroll(direction) {
                    let el = $refs.scrollContainer;
                    let amount = 240; // Lebar kartu + gap space
                    el.scrollBy({ left: direction === 'left' ? -amount : amount, behavior: 'smooth' });
                }
            }" @init="setTimeout(() => checkScroll(), 500)" class="relative w-full">

                {{-- 🔄 TOMBOL NAVIGASI KIRI BERBASIS BLADE HEROICONS --}}
                <button x-show="showLeft" @click="scroll('left')" type="button"
                    class="absolute left-3 top-[35%] z-30 w-8 h-8 flex items-center justify-center bg-white/95 backdrop-blur-md text-slate-600 hover:text-emerald-600 rounded-full shadow-md border border-slate-200/60 transition-all active:scale-90 cursor-pointer focus:outline-none select-none">
                    <x-heroicon-o-chevron-left class="w-5 h-5" stroke-width="2.5" />
                </button>

                {{-- 🔄 TOMBOL NAVIGASI KANAN BERBASIS BLADE HEROICONS --}}
                <button x-show="showRight" @click="scroll('right')" type="button"
                    class="absolute right-3 top-[35%] z-30 w-8 h-8 flex items-center justify-center bg-white/95 backdrop-blur-md text-slate-600 hover:text-emerald-600 rounded-full shadow-md border border-slate-200/60 transition-all active:scale-90 cursor-pointer focus:outline-none select-none">
                    <x-heroicon-o-chevron-right class="w-5 h-5" stroke-width="2.5" />
                </button>

                {{-- Kontainer Utama Flexbox Horizontal Scroll --}}
                <div x-ref="scrollContainer" @scroll.debounce.50ms="checkScroll()"
                    class="mb-4 flex flex-row space-x-4 overflow-x-auto scroll-smooth pb-4 scrollbar-hide snap-x snap-mandatory w-full">
                    <div class="flex gap-4 px-5 pb-2">
                        @foreach ($this->donations as $donation)
                            <div wire:key="rec-card-{{ $donation['id'] }}"
                                class="w-[220px] flex-shrink-0 snap-start {{ $loop->first ? 'scroll-ml-5' : '' }}">
                                @include('components.home.recurring-card', ['donation' => $donation])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
