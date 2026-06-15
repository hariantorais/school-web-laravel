<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Category;
use Livewire\Attributes\Layout;

new class extends Component {
    use WithPagination;

    // State pencarian dan filter kategori
    #[Layout('layouts.home')]
    public string $search = '';
    public string $categoryFilter = '';

    // Query string agar URL disinkronkan (fitur shareable link filter)
    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['as' => 'category', 'except' => ''],
    ];

    /**
     * Otomatis menangkap inputan filter kategori dari halaman detail atau widget
     */
    public function mount()
    {
        // Menangkap parameter request jika diakses konvensional
        if (request()->has('category')) {
            $this->categoryFilter = request()->get('category');
        }
        if (request()->has('search')) {
            $this->search = request()->get('search');
        }
    }

    /**
     * Reset pagination setiap kali user mengubah kata kunci pencarian
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Reset pagination setiap kali user mengubah filter kategori
     */
    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    /**
     * Mengambil data artikel terbit secara berkala dan reaktif
     */
    public function with(): array
    {
        $posts = Post::with('category')
            ->where('status', 'published')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->latest()
            ->paginate(6); // Menampilkan 6 kartu artikel per halaman

        return [
            'posts' => $posts,
            'categories' => Category::all(),
        ];
    }
}; ?>

<div>
    <!-- ========== HERO ARCHIVE POSTS ========== -->
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
            <div class="max-w-3xl mx-auto space-y-4" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 bg-[#A31D1D]/90 backdrop-blur-sm px-4 py-2 rounded-full mb-2">
                    <span class="text-xs font-semibold text-[#D4AF37] uppercase tracking-wider">Arsip Warta &
                        Berita</span>
                </div>
                <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                    Kabar & Informasi <span class="text-[#D4AF37]">Lengkap</span>
                </h1>
                <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                    Jelajahi seluruh arsip publikasi resmi, dokumentasi kegiatan santri, informasi PPDB, dan kajian
                    keislaman di Pondok Pesantren Daarul Huffadz.
                </p>
            </div>
        </div>
    </section>

    <!-- ========== FILTER & GRID CONTENT ========== -->
    <section class="py-16 relative bg-[#FDFBF7]">
        <div class="absolute inset-0 z-0">
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.015]">
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- BAR FILTER & PENCARIAN REAKTIF -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/60 mb-10 grid grid-cols-1 md:grid-cols-3 gap-4"
                data-aos="fade-up">
                <!-- Input Pencarian Kata Kunci -->
                <div class="relative md:col-span-2">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.350ms="search"
                        placeholder="Ketik kata kunci judul artikel yang Anda cari..."
                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-1 focus:ring-[#A31D1D] focus:border-[#A31D1D] focus:outline-none transition-all">
                </div>

                <!-- Dropdown Pilih Kategori -->
                <div>
                    <select wire:model.live="categoryFilter"
                        class="w-full px-3.5 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-1 focus:ring-[#A31D1D] focus:border-[#A31D1D] focus:outline-none transition-all cursor-pointer text-slate-600 font-medium">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- GRID POSTINGAN UTAMA -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 min-h-[400px]">
                @forelse($posts as $post)
                    <!-- Artikel Card -->
                    <div wire:key="archive-post-{{ $post->id }}" data-aos="fade-up"
                        class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-200 hover:shadow-xl transition-all duration-300 group flex flex-col h-full">

                        <!-- Thumbnail Area -->
                        <div class="h-52 overflow-hidden relative bg-slate-100">
                            <span
                                class="absolute top-4 left-4 bg-[#A31D1D] text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-md z-10 shadow-sm">
                                {{ $post->category->name }}
                            </span>
                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy">
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex flex-col flex-grow">
                            <span class="text-xs font-semibold text-slate-400 block mb-2">
                                {{ $post->published_at ? $post->published_at->isoFormat('D MMMM Y') : $post->created_at->isoFormat('D MMMM Y') }}
                            </span>

                            <h3
                                class="font-heading font-bold text-lg text-[#1E293B] mb-3 hover:text-[#A31D1D] transition-colors line-clamp-2 leading-snug">
                                <a href="/posts/{{ $post->slug }}" wire:navigate>
                                    {{ $post->title }}
                                </a>
                            </h3>

                            <p class="text-slate-500 text-sm line-clamp-3 mb-6 leading-relaxed">
                                {{ Str::limit(strip_tags($post->content), 110) }}
                            </p>

                            <!-- Button Link -->
                            <a href="/posts/{{ $post->slug }}" wire:navigate
                                class="mt-auto text-[#1E293B] font-bold text-sm inline-flex items-center hover:text-[#A31D1D] transition-colors group">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- State Kosong / Tidak Ditemukan -->
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 flex flex-col items-center justify-center py-20 text-center"
                        data-aos="fade-up">
                        <div class="p-4 bg-slate-100 rounded-full text-slate-400 mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-800">Artikel Tidak Ditemukan</h4>
                        <p class="text-slate-400 text-xs sm:text-sm mt-1 max-w-sm mx-auto">Tidak ada warta yang cocok
                            dengan kata kunci pencarian atau kategori yang Anda pilih.</p>
                    </div>
                @endforelse
            </div>

            <!-- PAGINATION AREA (Penyelarasan Desain Bootstrap/Tailwind Otomatis) -->
            <div class="mt-12 pt-6 border-t border-slate-200/60" data-aos="fade-up">
                {{ $posts->links() }}
            </div>

        </div>
    </section>
    </d>
