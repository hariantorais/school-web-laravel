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
            ->where('type', 'one_time')
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($d) {
                return [
                    'id' => $d->id,
                    'title' => $d->title,
                    'slug' => $d->slug,
                    'image_path' => $d->image_path,
                    'is_active' => $d->is_active,
                    'current_amount' => $d->current_amount,
                    'percentage' => $d->target_amount > 0 ? min(100, round(($d->current_amount / $d->target_amount) * 100)) : 0,
                    'type' => $d->type,
                    'category_name' => $d->category?->name ?? 'Umum',
                    'days_left' => $d->end_date?->isFuture() ? (int) floor(now()->diffInDays($d->end_date)) : null,
                ];
            })
            ->toArray();
    }
}; ?>

<div>
    @if (count($this->donations) > 0)
        <section class="space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2 mx-5">
                <h2 class="text-[13px] font-black text-slate-900 tracking-tight uppercase flex items-center gap-1.5">
                    <span class="w-1.5 h-3.5 rounded-full bg-[#A31D1D] inline-block"></span>
                    Donasi Insidental
                </h2>
                <span class="w-1.5 h-1.5 rounded-full bg-[#A31D1D]"></span>
            </div>

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
                    let amount = 240;
                    el.scrollBy({ left: direction === 'left' ? -amount : amount, behavior: 'smooth' });
                }
            }" @init="setTimeout(() => checkScroll(), 500)" class="relative w-full">

                <img x-show="showLeft" @click="scroll('left')"
                    class="absolute left-2 top-[35%] z-30 w-7 h-7 cursor-pointer bg-white/90 rounded-full p-1.5 shadow-md border border-slate-200/60"
                    src="https://assets.kitabisa.cc/images/icons/icon_arrow-left.png" alt="arrow left">
                <img x-show="showRight" @click="scroll('right')"
                    class="absolute right-2 top-[35%] z-30 w-7 h-7 cursor-pointer bg-white/90 rounded-full p-1.5 shadow-md border border-slate-200/60"
                    src="https://assets.kitabisa.cc/images/icons/icon_arrow-right.png" alt="arrow right">

                <div x-ref="scrollContainer" @scroll.debounce.50ms="checkScroll()"
                    class="mb-4 flex flex-row space-x-4 overflow-x-auto scroll-smooth pb-4 scrollbar-hide snap-x snap-mandatory w-full">
                    <div class="flex gap-4 px-5 pb-2">
                        @foreach ($this->donations as $donation)
                            <div wire:key="one-card-{{ $donation['id'] }}"
                                class="w-[220px] flex-shrink-0 snap-start {{ $loop->first ? 'scroll-ml-5' : '' }}">
                                @include('components.home.donation-card', ['donation' => $donation])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
