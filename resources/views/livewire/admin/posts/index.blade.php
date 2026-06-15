<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Services\PostService;
use Livewire\Attributes\{Computed, On, Title};

new class extends Component {
    use WithPagination;

    // State Utama Filter UI
    #[Title('Manajemen Postingan')]
    public string $search = '';
    public string $filterCategory = '';
    public string $filterStatus = '';

    #[On('refresh-table')]
    public function refreshTable(): void {}

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingFilterCategory(): void
    {
        $this->resetPage();
    }
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    /**
     * Mengambil Data Postingan Terfilter via Service Layer
     */
    #[Computed]
    public function posts()
    {
        $payloadFilters = [
            'search' => trim($this->search),
            'category' => $this->filterCategory,
            'status' => $this->filterStatus,
        ];

        return app(PostService::class)->getAllPaginated($payloadFilters, 10);
    }

    /**
     * Memproses Delegasi Perintah Hapus ke Service Layer Resmi
     */
    public function delete(int $id, PostService $service)
    {
        try {
            $service->delete($id);
            $this->dispatch('toast', type: 'success', message: 'Artikel postingan resmi berhasil dihapus dari server!');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Gagal mengeksekusi perintah: ' . $e->getMessage());
        }
    }
}; ?>

<div class="space-y-5 animate-fade-in">

    <x-slot name="subhead">
        Kelola semua berita, pengumuman resmi, dan agenda
        kegiatan sekolah
        di sini.
    </x-slot>

    <x-slot name="headerAction">
        <x-ui.button href="{{ route('admin.posts.create') }}" class="w-full sm:w-auto inline-flex items-center gap-2">
            <x-heroicon-m-pencil class="w-4 h-4" />
            Tulis Artikel Baru
        </x-ui.button>
    </x-slot>

    <div
        class="bg-white p-4 border border-slate-200/60 rounded-2xl shadow-xl shadow-slate-100/40 grid grid-cols-1 sm:grid-cols-3 gap-3.5">
        <x-form.search placeholder="Cari judul postingan..." name="search" />

        <div>
            <select wire:model.live="filterCategory"
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-4 focus:ring-[var(--accent-focus)] focus:border-[var(--accent-primary)] focus:outline-hidden transition-all cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach (\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <select wire:model.live="filterStatus"
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-4 focus:ring-[var(--accent-focus)] focus:border-[var(--accent-primary)] focus:outline-hidden transition-all cursor-pointer">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="published">Diterbitkan</option>
            </select>
        </div>
    </div>

    {{-- KONTANER TABEL TUNGGAL RESPONSIF --}}
    {{-- GRID KONTENER UTAMA --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($this->posts as $post)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-slate-300/60 transition-all duration-200 flex flex-col overflow-hidden group"
                wire:key="post-card-{{ $post->id }}">

                {{-- 1. AREA GAMBAR SAMPUL & BADGE --}}
                <div class="w-full aspect-video bg-slate-100 relative overflow-hidden border-b border-slate-100">
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                        class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-300">

                    {{-- Kategori Melayang --}}
                    <div class="absolute top-3 left-3">
                        <span
                            class="bg-slate-900/70 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg border border-white/10 shadow-xs">
                            {{ $post->category->name }}
                        </span>
                    </div>

                    {{-- Status Badge Melayang --}}
                    <div class="absolute top-3 right-3">
                        @if ($post->status === 'published')
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500 text-white border border-emerald-400 shadow-sm">
                                <span class="w-1 h-1 bg-white rounded-full animate-pulse"></span>
                                Diterbitkan
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-600 text-white border border-slate-500 shadow-sm">
                                Draft
                            </span>
                        @endif
                    </div>
                </div>

                {{-- 2. BADAN INFORMASI KARTU (CARD BODY) --}}
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">

                    {{-- Bagian Teks: Judul + Potongan Isi Konten --}}
                    <div class="space-y-2">
                        {{-- Judul Artikel --}}
                        <h3 class="font-extrabold text-sm text-slate-800 line-clamp-2 leading-snug tracking-tight group-hover:text-[var(--accent-primary)] transition-colors"
                            title="{{ $post->title }}">
                            {{ $post->title }}
                        </h3>

                        <p class="text-xs text-slate-500 line-clamp-2 font-medium leading-relaxed break-words">
                            {{ clean_trix($post->content, 80) }}
                        </p>
                    </div>

                    {{-- 3. FOOTER KARTU: METADATA & AKSI --}}
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-4">
                        {{-- Informasi Penulis & Waktu --}}
                        <div class="flex items-center gap-2.5 min-w-0">
                            {{-- Avatar Inisial --}}
                            <div
                                class="w-7 h-7 rounded-full bg-[var(--accent-muted)]/60 border border-teal-900/10 flex items-center justify-center shrink-0">
                                <span class="text-[10px] font-bold text-[var(--accent-text)] uppercase">
                                    {{ substr($post->user->name, 0, 2) }}
                                </span>
                            </div>

                            <div class="flex flex-col min-w-0">
                                <span class="text-xs font-bold text-slate-700 truncate leading-none mb-1">
                                    {{ $post->user->name }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium leading-none">
                                    {{ $post->published_at ? $post->published_at->diffForHumans() : 'Belum dirilis' }}
                                </span>
                            </div>
                        </div>

                        {{-- Komponen Aksi --}}
                        <div class="shrink-0">
                            <x-table.actions :id="$post->id" :modalName="null"
                                href="{{ route('admin.posts.edit', $post->slug) }}" />
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-1 sm:col-span-2 xl:col-span-3">
                <x-table.empty colspan="1" :search="$search"
                    blankMessage="Belum ada artikel atau pengumuman sekolah terdaftar." />
            </div>
        @endforelse
    </div>

    {{-- BARIS NAVIGASI PAGINASI --}}
    <x-table.pagination :paginator="$this->posts" />

    {{-- MODAL MODAL DIALOG CONFIRM REUSABLE --}}
    <x-ui.confirm confirmLabel="Ya, Hapus Artikel" />
</div>
