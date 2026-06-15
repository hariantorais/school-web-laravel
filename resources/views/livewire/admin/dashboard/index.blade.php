<?php

use Livewire\Volt\Component;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

new class extends Component {
    /**
     * Mengambil data statistik aktual untuk dashboard sekolah
     */
    public function with(): array
    {
        // Mengambil hitungan dasar dari database
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();

        // Mengambil 5 postingan terbaru untuk log aktivitas
        $recentPosts = Post::with(['category', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Data dummy untuk modul donasi (bisa disambungkan ke model Donasi Anda nanti)
        $activeCampaigns = 3;
        $totalDonations = 14500000; // Contoh: Rp 14.500.000

        return [
            'totalPosts' => $totalPosts,
            'publishedPosts' => $publishedPosts,
            'draftPosts' => $draftPosts,
            'recentPosts' => $recentPosts,
            'activeCampaigns' => $activeCampaigns,
            'totalDonations' => $totalDonations,
        ];
    }
}; ?>

<div class="space-y-8">

    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 border border-slate-200 rounded-xl shadow-sm">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Hari ini adalah {{ now()->isoFormat('D MMMM Y') }}. Berikut adalah ringkasan perkembangan sistem
                informasi dan donasi sekolah.
            </p>
        </div>
        <div class="flex-shrink-0">
            <span
                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-teal-50 text-teal-700 border border-teal-200">
                <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse mr-2"></span>
                Sistem Online
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex items-center gap-4">
            <div class="p-3 bg-teal-50 text-teal-700 rounded-lg flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-5M19 20H9m5-14H5m4 4H5m7 4H5">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Artikel</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $totalPosts }}</h3>
                <p class="text-[10px] text-slate-400 mt-0.5"><span
                        class="text-emerald-600 font-medium">{{ $publishedPosts }}</span> diterbitkan</p>
            </div>
        </div>

        <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex items-center gap-4">
            <div class="p-3 bg-amber-50 text-amber-700 rounded-lg flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Artikel Draft</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $draftPosts }}</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Butuh peninjauan ulang</p>
            </div>
        </div>

        <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex items-center gap-4">
            <div class="p-3 bg-indigo-50 text-indigo-700 rounded-lg flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Campaign Donasi</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $activeCampaigns }}</h3>
                <p class="text-[10px] text-indigo-600 font-medium mt-0.5">Program Sedang Berjalan</p>
            </div>
        </div>

        <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-700 rounded-lg flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Dana Terkumpul</p>
                <h3 class="text-xl font-bold text-slate-800 mt-0.5">Rp {{ number_format($totalDonations, 0, ',', '.') }}
                </h3>
                <p class="text-[10px] text-emerald-600 font-medium mt-0.5">Siap Dialokasikan</p>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden lg:col-span-2">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Aktivitas Postingan Terbaru</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar artikel dan berita yang baru saja dimasukkan oleh
                        staff pengajar.</p>
                </div>
                <a href="{{ route('admin.posts.index') }}" wire:navigate
                    class="text-xs font-semibold text-teal-700 hover:text-teal-800 transition-colors">
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                        <tr>
                            <th class="p-4">Judul</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentPosts as $post)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="p-4 font-medium text-slate-900 max-w-xs truncate">
                                    {{ $post->title }}
                                    <span class="block text-[10px] text-slate-400 font-normal mt-0.5">Oleh:
                                        {{ $post->user->name }}</span>
                                </td>
                                <td class="p-4 text-slate-500">{{ $post->category->name }}</td>
                                <td class="p-4">
                                    @if ($post->status === 'published')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Diterbitkan</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">Draft</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('admin.posts.edit', $post->slug) }}" wire:navigate
                                        class="inline-flex items-center gap-1 text-xs text-teal-700 font-semibold hover:text-teal-800 transition-colors">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-400 text-xs">Belum ada data
                                    postingan yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-5 border border-slate-200 rounded-xl shadow-sm space-y-5">
            <div>
                <h3 class="font-bold text-slate-900 text-sm sm:text-base">Pusat Bantuan Kerja</h3>
                <p class="text-xs text-slate-400 mt-0.5">Akses cepat instruksi kerja harian admin.</p>
            </div>

            <div class="space-y-2.5">
                <a href="{{ route('admin.posts.create') }}" wire:navigate
                    class="w-full flex items-center justify-between p-3 bg-slate-50 hover:bg-teal-50/50 rounded-lg border border-slate-200/60 hover:border-teal-200 group transition-all text-xs font-medium text-slate-700 hover:text-teal-900">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-teal-600 flex-shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span>Tulis Berita / Pengumuman Baru</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-teal-600 transition-transform group-hover:translate-x-0.5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>

                <a href="#"
                    class="w-full flex items-center justify-between p-3 bg-slate-50 hover:bg-teal-50/50 rounded-lg border border-slate-200/60 hover:border-teal-200 group transition-all text-xs font-medium text-slate-700 hover:text-teal-900">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-teal-600 flex-shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Buka Program Donasi Baru</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-teal-600 transition-transform group-hover:translate-x-0.5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </a>
            </div>


        </div>

    </div>
</div>
