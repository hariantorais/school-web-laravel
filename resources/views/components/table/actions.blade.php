@props([
    'id',
    'modalName' => 'modal-form',
    'confirmModalName' => 'delete-confirm',
    'showEdit' => true,
    'showDelete' => true,
    'href' => null, // REVISI: Tambahkan prop href opsional untuk mengarahkan ke rute halaman baru
])

<div {!! $attributes->merge(['class' => 'flex items-center justify-end gap-1.5']) !!}>

    {{-- ========================================================================= --}}
    {{-- BLOK TOMBOL EDIT (HIBRIDA REUSABLE)                                       --}}
    {{-- ========================================================================= --}}
    @if ($showEdit)
        @if ($href)
            {{-- JALUR A: Mengarah ke Routing Halaman Penuh (Untuk Postingan, Produk, dll) --}}
            <a href="{{ $href }}" wire:navigate title="Ubah Data"
                class="cursor-pointer inline-flex items-center justify-center p-1.5 bg-white border border-slate-200 text-slate-400 hover:text-[var(--accent-primary)] hover:border-[var(--accent-primary)] focus:ring-4 focus:ring-[var(--accent-focus)] rounded-lg transition-all shadow-xs active:scale-95 outline-hidden">
                <x-heroicon-o-pencil-square class="w-4 h-4" />
            </a>
        @elseif ($modalName)
            {{-- JALUR B: Membuka Modal Popup Biasa (Untuk Kategori, Donasi, dll) --}}
            <button type="button"
                @click="$dispatch('open-modal', { name: '{{ $modalName }}', id: '{{ $id }}' })"
                title="Ubah Data"
                class="cursor-pointer inline-flex items-center justify-center p-1.5 bg-white border border-slate-200 text-slate-400 hover:text-[var(--accent-primary)] hover:border-[var(--accent-primary)] focus:ring-4 focus:ring-[var(--accent-focus)] rounded-lg transition-all shadow-xs active:scale-95 outline-hidden">
                <x-heroicon-o-pencil-square class="w-4 h-4" />
            </button>
        @endif
    @endif

    {{-- ========================================================================= --}}
    {{-- BLOK TOMBOL HAPUS (SINKRON KE MODAL KONFIRMASI)                           --}}
    {{-- ========================================================================= --}}
    @if ($showDelete)
        <button type="button"
            @click="$dispatch('open-confirm', { name: '{{ $confirmModalName }}', id: '{{ $id }}' })"
            title="Hapus Data"
            class="cursor-pointer inline-flex items-center justify-center p-1.5 bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 focus:ring-4 focus:ring-rose-500/10 rounded-lg transition-all shadow-xs active:scale-95 outline-hidden">
            <x-heroicon-o-trash class="w-4 h-4" />
        </button>
    @endif

</div>
