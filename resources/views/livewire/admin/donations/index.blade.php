<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Services\DonationService;
use App\Models\Donation;
use Livewire\Attributes\{Computed, On};

new class extends Component {
    use WithPagination;

    // State Filter Utama (Sinkron Ke Kueri Eloquent Backend)
    public string $search = '';
    public string $filterStatus = '';
    public string $filterTarget = '';

    /**
     * Listener untuk sinkronisasi render ulang data saat form modal sukses menyimpan data
     */
    #[On('refresh-table')]
    public function refreshTable(): void {}

    // Reset ke halaman pertama setiap kali filter diubah secara reaktif
    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }
    public function updatingFilterTarget(): void
    {
        $this->resetPage();
    }

    /**
     * Mengosongkan seluruh filter pencarian kembali ke posisi semula
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'filterStatus', 'filterTarget']);
        $this->resetPage();

        // Pemicu sinyal global ke Alpine agar membersihkan layar browser seketika
        $this->dispatch('filters-reseted');
    }

    /**
     * Memanggil Data Program Donasi Terfilter via Service Layer
     */
    #[Computed]
    public function donations()
    {
        $payloadFilters = [
            'search' => trim($this->search),
            'status' => $this->filterStatus !== '' ? (int) $this->filterStatus : '',
            'target_range' => $this->filterTarget,
        ];

        return app(DonationService::class)->getAllPaginated($payloadFilters, 9);
    }

    /**
     * Mendelegasikan Aksi Hapus Program Donasi ke Tingkat Service Layer
     */
    public function delete(int $id, DonationService $service)
    {
        try {
            $service->delete($id);
            $this->dispatch('toast', type: 'success', message: 'Program donasi dan berkas banner berhasil dihapus secara permanen!');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Gagal melenyapkan data: ' . $e->getMessage());
        }
    }
}; ?>

<div class="space-y-6 animate-fade-in">


    <div class="bg-white p-4 border border-slate-200/60 rounded-2xl shadow-xl shadow-slate-100/40" x-data="{
        localSearch: @entangle('search'),
        localStatus: @entangle('filterStatus'),
        localTarget: @entangle('filterTarget'),
        isTyping: false
    }"
        @filters-reseted.window="localSearch = ''; localStatus = ''; localTarget = ''; isTyping = false">

        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">

            <div class="grid grid-cols-1 sm:grid-cols-3 flex-1 gap-3">

                {{-- Kotak Cari Judul Berbasis Alpine Debounce untuk Mencegah Lag Tgetikan --}}
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <template x-if="isTyping">
                            <svg class="animate-spin h-4 w-4 text-[var(--accent-primary)]" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </template>
                        <template x-if="!isTyping">
                            <x-heroicon-m-magnifying-glass class="w-4 h-4" />
                        </template>
                    </span>
                    <input type="text" x-model="localSearch"
                        @input="isTyping = true; setTimeout(() => { isTyping = false }, 400)"
                        wire:model.live.debounce.400ms="search" placeholder="Cari judul program..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-4 focus:ring-[var(--accent-focus)] focus:border-[var(--accent-primary)] focus:outline-hidden transition-all placeholder-slate-400 shadow-inner" />
                </div>

                {{-- Filter Status Keaktifan --}}
                <div>
                    <select x-model="localStatus" wire:model.live="filterStatus"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-4 focus:ring-[var(--accent-focus)] focus:border-[var(--accent-primary)] focus:outline-hidden transition-all cursor-pointer">
                        <option value="">Semua Status Berkas</option>
                        <option value="1">Aktif / Berjalan</option>
                        <option value="0">Selesai / Ditutup</option>
                    </select>
                </div>

                {{-- Filter Range Nominal Target --}}
                <div>
                    <select x-model="localTarget" wire:model.live="filterTarget"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-4 focus:ring-[var(--accent-focus)] focus:border-[var(--accent-primary)] focus:outline-hidden transition-all cursor-pointer">
                        <option value="">Semua Nilai Target</option>
                        <option value="under_10m">Di bawah Rp 10 Juta</option>
                        <option value="10m_to_50m">Rp 10 Juta - Rp 50 Juta</option>
                        <option value="above_50m">Di atas Rp 50 Juta</option>
                    </select>
                </div>
            </div>

            {{-- Tombol Aksi Sisi Kanan --}}
            <div class="flex items-center gap-2.5 justify-end">
                <template x-if="localSearch || localStatus !== '' || localTarget">
                    <button type="button" wire:click="resetFilters"
                        class="inline-flex items-center gap-1.5 px-3 py-2.5 text-xs font-bold text-slate-500 hover:text-rose-600 bg-slate-50 border border-slate-200 rounded-xl hover:bg-rose-50 transition-colors cursor-pointer">
                        <x-heroicon-m-arrow-path class="w-3.5 h-3.5" />
                        Reset
                    </button>
                </template>

                <x-ui.button @click="$dispatch('open-modal', { name: 'modal-form' })"
                    class="inline-flex items-center gap-2 justify-center cursor-pointer py-2.5 text-xs">
                    <x-heroicon-m-plus class="w-4 h-4" />
                    Tambah Program
                </x-ui.button>
            </div>

        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- BLOCK 3: GRID CARD HYBRID UTAMA BERBASIS KATA KUNCI GRID                  --}}
    {{-- ========================================================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($this->donations as $donation)
            @php
                $percentage =
                    $donation->target_amount > 0
                        ? min(($donation->current_amount / $donation->target_amount) * 100, 100)
                        : 0;
            @endphp

            <div class="bg-white border border-slate-200/60 rounded-2xl shadow-xl shadow-slate-100/30 overflow-hidden flex flex-col group hover:border-[var(--accent-primary)]/40 hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 relative"
                wire:key="donation-card-{{ $donation->id }}">

                {{-- AREA BANNER DENGAN OVERLAY STATUS BADGE MELAYANG --}}
                <div class="w-full h-44 bg-slate-100 relative overflow-hidden border-b border-slate-100 flex-shrink-0">
                    <img src="{{ $donation->imageUrl }}" alt="{{ $donation->title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    {{-- Status Badge Melayang --}}
                    <div class="absolute top-3.5 right-3.5 z-10">
                        @if ($donation->is_active)
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50/90 backdrop-blur-xs text-emerald-700 border border-emerald-200 shadow-xs">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                Aktif
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-900/80 backdrop-blur-xs text-slate-100 shadow-xs">
                                Selesai
                            </span>
                        @endif
                    </div>
                </div>

                {{-- KONTEN DETAIL CARD --}}
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    {{-- Judul Program (Terproteksi Line-Clamp) --}}
                    <div class="space-y-1">
                        <h3 class="font-bold text-slate-900 text-sm leading-tight group-hover:text-[var(--accent-primary)] transition-colors line-clamp-2"
                            title="{{ $donation->title }}">
                            {{ $donation->title }}
                        </h3>
                    </div>

                    {{-- Progres Bar Kas & Akumulasi Dana --}}
                    <div class="space-y-2 pt-1">
                        <div
                            class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner border border-slate-200/30">
                            <div class="bg-[var(--accent-primary)] h-2 rounded-full transition-all duration-500"
                                style="width: {{ $percentage }}%"></div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] font-medium text-slate-400">
                            <div class="flex flex-col">
                                <span
                                    class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Terkumpul</span>
                                <span class="text-xs font-bold text-[var(--accent-primary)]">
                                    Rp {{ idr($donation->current_amount) }}
                                </span>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Target</span>
                                <span class="text-xs font-bold text-slate-700 font-mono">
                                    {{ idr($donation->target_amount) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FOOTER CARD: AKSI AKUNTABILITAS DATA --}}
                <div
                    class="px-5 py-3.5 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between gap-4">
                    <span
                        class="text-[11px] font-bold text-slate-500 bg-slate-100 border border-slate-200/60 px-2 py-0.5 rounded-md font-mono">
                        {{ round($percentage) }}% Ketercapaian
                    </span>

                    {{-- Komponen Menu Edit / Hapus Reusable --}}
                    <div class="flex items-center">
                        <a href="{{ route('admin.donations.transactions', ['filterDonation' => $donation->id]) }}"
                            wire:navigate title="Lihat Log Transaksi Masuk"
                            class="p-1.5 text-slate-500 hover:text-[var(--accent-primary)] hover:bg-slate-100 rounded-lg transition-colors border border-transparent hover:border-slate-200">
                            <x-heroicon-m-document-text class="w-4 h-4" />
                        </a>
                        <x-table.actions :id="$donation->id" />
                    </div>
                </div>

            </div>
        @empty
            {{-- STATE KOSONG INTERAKTIF --}}
            <div class="col-span-1 md:col-span-2 xl:col-span-3">
                <x-table.empty colspan="1" :search="$search"
                    blankMessage="Tidak ada program donasi yang cocok dengan kombinasi filter Anda." />
            </div>
        @endforelse
    </div>

    {{-- KONTROL PAGINASI HALAMAN --}}
    <x-table.pagination :paginator="$this->donations" />

    {{-- INJEKSI MODAL DAN KONFIRMASI POPUP --}}
    <x-ui.modal title="Form Program Donasi">
        <livewire:admin.donations.form />
    </x-ui.modal>

    <x-ui.confirm confirmLabel="Ya, Hapus Donasi" />
</div>
