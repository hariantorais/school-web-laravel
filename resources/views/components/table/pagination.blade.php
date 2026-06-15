@props(['paginator'])

@if ($paginator->hasPages())
    {{-- REVISI: Menggunakan border-slate-100 agar menyatu halus di bawah tabel baru Anda --}}
    <div
        class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">

        {{-- Teks info kiri: bersih, tegas, menggunakan variabel kustom aksen Teal Anda --}}
        <div class="text-xs text-slate-600 font-semibold tracking-wide">
            Menampilkan <span class="text-slate-900 font-mono font-black">{{ $paginator->firstItem() }}</span>
            sampai <span class="text-slate-900 font-mono font-black">{{ $paginator->lastItem() }}</span>
            dari total <span class="text-[var(--accent-primary)] font-mono font-black">{{ $paginator->total() }}</span>
            data.
        </div>

        {{-- Navigasi kanan: tombol bersih + indikator hitam kontras tinggi --}}
        <div class="flex items-center gap-2 w-full sm:w-auto justify-center">

            {{-- Tombol Previous --}}
            <button type="button" wire:click="previousPage" wire:loading.attr="disabled"
                @if ($paginator->onFirstPage()) disabled @endif
                class="px-3 py-2 rounded-xl text-xs font-bold border transition-all select-none flex items-center gap-1
                {{ $paginator->onFirstPage() ? 'bg-slate-50 border-slate-200 text-slate-300 cursor-not-allowed shadow-none' : 'bg-white border-slate-200 text-slate-700 hover:border-slate-300 hover:bg-slate-50 active:scale-[0.97] cursor-pointer shadow-xs' }}">
                <x-heroicon-o-chevron-left class="w-3.5 h-3.5 stroke-[2.5]" />
            </button>

            {{-- Indikator Index Halaman - Menggunakan warna Teal kustom Anda pada teks halaman aktif --}}
            <div
                class="px-3 py-2 bg-slate-900 text-white text-xs font-mono font-bold rounded-xl shadow-inner min-w-[75px] text-center">
                <span class="text-[var(--accent-primary)]">{{ $paginator->currentPage() }}</span>
                <span class="text-slate-500">/</span>
                {{ $paginator->lastPage() }}
            </div>

            {{-- Tombol Next --}}
            <button type="button" wire:click="nextPage" wire:loading.attr="disabled"
                @if (!$paginator->hasMorePages()) disabled @endif
                class="px-3 py-2 rounded-xl text-xs font-bold border transition-all select-none flex items-center gap-1
                {{ !$paginator->hasMorePages() ? 'bg-slate-50 border-slate-200 text-slate-300 cursor-not-allowed shadow-none' : 'bg-white border-slate-200 text-slate-700 hover:border-slate-300 hover:bg-slate-50 active:scale-[0.97] cursor-pointer shadow-xs' }}">
                <x-heroicon-o-chevron-right class="w-3.5 h-3.5 stroke-[2.5]" />
            </button>

        </div>
    </div>
@endif
