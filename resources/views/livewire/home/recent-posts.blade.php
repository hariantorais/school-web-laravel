<?php

use Livewire\Volt\Component;
use App\Models\Post;

new class extends Component {
    /**
     * Mengambil 3 postingan terbaru yang berstatus aktif/published
     */
    public function with(): array
    {
        return [
            'latestPosts' => Post::with('category')->where('status', 'published')->latest()->take(3)->get(),
        ];
    }
}; ?>

<section id="news" class="py-24 relative">
    <div class="absolute inset-0 z-0">
        <div
            class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.2]">
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto space-y-4 mb-12" data-aos="fade-up">
            <div class="flex items-center justify-center gap-3">
                <span class="h-px w-8 bg-gradient-to-r from-transparent to-[#A31D1D]"></span>
                <span
                    class="text-xs sm:text-sm font-bold text-[#A31D1D] uppercase tracking-widest bg-[#A31D1D]/10 backdrop-blur-sm px-4 py-2 rounded-full inline-block border border-[#A31D1D]/20">
                    Informasi
                </span>
                <span class="h-px w-8 bg-gradient-to-l from-transparent to-[#A31D1D]"></span>
            </div>

            <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-[#1E293B] leading-tight">
                Kabar Informasi <br />
                <span class="text-[#A31D1D]">Terbaru</span>
            </h2>

            <p class="text-slate-500 text-sm sm:text-base max-w-2xl mx-auto">
                Informasi terbaru seputar kegiatan pondok, pengumuman resmi, serta perkembangan santri Daarul Huffadz.
            </p>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($latestPosts as $post)
                <x-home.post-card :post="$post" :key="$post->id" />
            @empty
                <div class="col-span-1 md:col-span-3 text-center py-12 text-slate-400 text-sm">
                    Belum ada kabar informasi terbaru yang diterbitkan.
                </div>
            @endforelse
        </div>

        <div class="text-center mt-12" data-aos="fade-up">
            <a href="/posts" wire:navigate
                class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-[#A31D1D] text-[#A31D1D] font-semibold rounded-xl hover:bg-[#A31D1D] hover:text-white transition-all duration-300 group">
                <span>Lihat Semua Artikel</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</section>
