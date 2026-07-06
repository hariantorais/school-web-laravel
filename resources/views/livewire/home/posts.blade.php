<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Category;
use Livewire\Attributes\{Layout, Computed, Url};

new #[Layout('layouts.home')] class extends Component {
    use WithPagination;

    #[Url(keep: true)]
    public string $search = '';

    #[Url(as: 'category', keep: true)]
    public string $categoryFilter = '';

    // Reset pagination setiap kali user mengubah kata kunci pencarian
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Reset pagination setiap kali user mengubah filter kategori
    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    /**
     * AMBIL DATA KATEGORI (COMPUTED PROPERTY)
     */
    #[Computed]
    public function categories()
    {
        return Category::select('id', 'name')->get();
    }

    /**
     * AMBIL DATA POSTINGAN TERFILTER
     */
    #[Computed]
    public function posts()
    {
        return Post::with('category')
            ->where('status', 'published')
            ->when(trim($this->search), function ($query) {
                $query->where('title', 'like', '%' . trim($this->search) . '%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->latest()
            ->paginate(6);
    }
}; ?>

<div class="min-h-screen bg-[#FDFBF7] text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif]">

    {{-- ========================================================================= --}}
    {{-- BLOCK 1: HERO ARCHIVE POSTS --}}
    {{-- ========================================================================= --}}
    <section class="relative pt-32 pb-16 lg:pt-40 lg:pb-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-[#1E293B] via-[#2D3A4F] to-[#1E293B]"></div>
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.05]">
            </div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#A31D1D]/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#D4AF37]/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-3xl mx-auto space-y-4">
                <div
                    class="inline-flex items-center gap-2 bg-[#A31D1D]/90 backdrop-blur-sm px-4 py-2 rounded-full mb-2 border border-white/10">
                    <span class="text-xs font-semibold text-[#D4AF37] uppercase tracking-wider">Arsip Warta &
                        Berita</span>
                </div>
                <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                    Kabar & Informasi <span class="text-[#D4AF37]">Lengkap</span>
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed font-medium">
                    Jelajahi seluruh arsip publikasi resmi, dokumentasi kegiatan santri, informasi PPDB, dan kajian
                    keislaman di Pondok Pesantren Daarul Huffadz.
                </p>
            </div>
        </div>
    </section>

    {{-- ========================================================================= --}}
    {{-- BLOCK 2: FILTER & GRID CONTENT --}}
    {{-- ========================================================================= --}}
    <section class="py-16 relative bg-[#FDFBF7]">
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.015]">
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div
                class="bg-white p-4 rounded-2xl shadow-xl shadow-slate-200/30 border border-slate-200/60 mb-10 grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="relative md:col-span-2">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.400ms="search"
                        placeholder="Ketik kata kunci judul artikel yang Anda cari..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-[#1E293B] font-semibold focus:ring-4 focus:ring-red-50 focus:border-[#A31D1D] focus:outline-hidden transition-all placeholder-slate-400 shadow-inner">
                </div>

                <div>
                    <select wire:model.live="categoryFilter"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:ring-4 focus:ring-red-50 focus:border-[#A31D1D] focus:outline-hidden transition-all cursor-pointer text-slate-700 font-bold shadow-inner">
                        <option value="">Semua Kategori</option>
                        @foreach ($this->categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 min-h-[400px]">
                @forelse($this->posts as $post)
                    <x-home.post-card :post="$post" :key="$post->id" />
                @empty
                    <div
                        class="col-span-1 md:col-span-2 lg:col-span-3 flex flex-col items-center justify-center py-20 text-center">
                        <div class="p-3.5 bg-slate-100 text-slate-400 rounded-full mb-3.5 border border-slate-200/40">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800">Artikel Warta Tidak Ditemukan</h4>
                        <p class="text-slate-400 text-xs mt-0.5 max-w-xs mx-auto leading-relaxed">
                            Tidak ada warta yang cocok dengan kata kunci pencarian atau kategori yang Anda pilih saat
                            ini.
                        </p>
                    </div>
                @endforelse
            </div>

            <x-home.pagination :paginator="$this->posts" :show-info="true" :show-per-page="true" :per-page-options="[6, 12, 24, 48]" />

        </div>
    </section>
</div>
