<?php

use Livewire\Volt\Component;
use App\Models\Donation;
use Livewire\Attributes\{Layout, Computed};

new #[Layout('layouts.home', ['headerTitle' => 'Program Kebaikan'])] class extends Component {
    // Limit awal penayangan kartu untuk masing-masing tipe section
    public int $limitRecurring = 4;
    public int $limitOneTime = 4;

    // Total hitungan data aktif di database untuk kontrol visibilitas tombol
    public int $totalRecurring = 0;
    public int $totalOneTime = 0;

    public function mount(): void
    {
        $this->totalOneTime = Donation::where('is_active', true)->where('type', 'one_time')->count();

        $this->totalRecurring = Donation::where('is_active', true)->where('type', '!=', 'one_time')->count();
    }

    /**
     * Memuat data program Gerakan Abadi (Recurring)
     */
    #[Computed]
    public function recurringDonations()
    {
        return Donation::with(['category'])
            ->withCount(['transactions' => fn($q) => $q->where('status', 'success')])
            ->where('is_active', true)
            ->where('type', '!=', 'one_time')
            ->latest()
            ->take($this->limitRecurring)
            ->get()
            ->map(fn($d) => $this->formatData($d))
            ->toArray();
    }

    /**
     * Memuat data program Donasi Insidental (One Time)
     */
    #[Computed]
    public function oneTimeDonations()
    {
        return Donation::with(['category'])
            ->withCount(['transactions' => fn($q) => $q->where('status', 'success')])
            ->where('is_active', true)
            ->where('type', 'one_time')
            ->latest()
            ->take($this->limitOneTime)
            ->get()
            ->map(fn($d) => $this->formatData($d))
            ->toArray();
    }

    /**
     * Helper standardisasi struktur data array
     */
    private function formatData($d): array
    {
        return [
            'id' => $d->id,
            'title' => $d->title,
            'slug' => $d->slug,
            'image_path' => $d->image_path,
            'is_active' => $d->is_active,
            'current_amount' => $d->current_amount,
            'target_amount' => $d->target_amount,
            'transactions_count' => $d->transactions_count,
            'percentage' => $d->target_amount > 0 ? min(100, round(($d->current_amount / $d->target_amount) * 100)) : 0,
            'type' => $d->type,
            'category_name' => $d->category?->name ?? 'Umum',
            'category_color' => $d->category?->color ?? '#A31D1D',
            'days_left' => $d->end_date?->isFuture() ? (int) floor(now()->diffInDays($d->end_date)) : null,
        ];
    }

    /**
     * Aksi memuat data tambahan khusus untuk tipe Gerakan Abadi
     */
    public function loadMoreRecurring(): void
    {
        $this->limitRecurring += 4;
    }

    /**
     * Aksi memuat data tambahan khusus untuk tipe Donasi Satu Kali
     */
    public function loadMoreOneTime(): void
    {
        $this->limitOneTime += 4;
    }
}; ?>

<div class="bg-[#FDFBF7] text-slate-800 font-sans min-h-screen space-y-8 pb-12">

    {{-- ========================================================================= --}}
    {{-- BLOCK 1: HERO BANNER --}}
    {{-- ========================================================================= --}}
    <section
        class="relative bg-gradient-to-br from-[#1E293B] via-[#2D3A4F] to-[#1E293B] px-5 pt-6 pb-12 overflow-hidden shrink-0">
        <div
            class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.04]">
        </div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-[#A31D1D]/15 rounded-full blur-3xl"></div>

        <div class="relative text-center space-y-2">
            <h1 class="text-xl font-black text-white leading-tight tracking-tight">
                Titip Kebaikan <span class="text-[#D4AF37]">Penuh Berkah</span>
            </h1>
            <p class="text-slate-300 text-xs leading-relaxed font-medium px-6 max-w-sm mx-auto">
                Salurkan kontribusi terbaik secara transparan untuk pembangunan pesantren dan masa depan santri
                Al-Qur'an
            </p>
        </div>
    </section>

    {{-- ========================================================================= --}}
    {{-- BLOCK 2: RENDER SUB-KOMPONEN PROGRAM BERKELANJUTAN (VOLT) --}}
    {{-- ========================================================================= --}}
    <livewire:home.donations.feed-recurring />

    {{-- ========================================================================= --}}
    {{-- BLOCK 3: RENDER SUB-KOMPONEN DONASI INSIDENTAL (VOLT) --}}
    {{-- ========================================================================= --}}
    <livewire:home.donations.feed-onetime />

</div>
