<?php

use Livewire\Volt\Component;
use App\Models\Post;
use Livewire\Attributes\On;

new class extends Component {
    public $popularPosts = [];

    public function mount()
    {
        $this->loadPopularPosts();
    }

    public function placeholder()
    {
        return view('components.dashboard.popular-posts-placeholder');
    }

    public function loadPopularPosts()
    {
        $this->popularPosts = Post::with('category')->where('status', 'published')->orderBy('views', 'desc')->limit(5)->get();
    }

    #[On('refresh-stats')]
    public function refreshStats()
    {
        $this->loadPopularPosts();
    }
};

?>

<div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-slate-700">Postingan Terpopuler</h3>
        <a href="{{ route('admin.posts.index') }}" class="text-xs text-[var(--accent-primary)] hover:underline">
            Lihat Semua
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-slate-400 uppercase tracking-wider border-b border-slate-200/60">
                    <th class="pb-2 font-medium">#</th>
                    <th class="pb-2 font-medium">Judul</th>
                    <th class="pb-2 font-medium">Kategori</th>
                    <th class="pb-2 font-medium text-right">Views</th>
                    <th class="pb-2 font-medium text-right">Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($popularPosts as $index => $post)
                    <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/50 transition-colors">
                        <td class="py-2 text-slate-400 text-xs">{{ $index + 1 }}</td>
                        <td class="py-2">
                            <a href="/posts/{{ $post->slug }}" target="_blank"
                                class="font-medium text-slate-700 hover:text-[var(--accent-primary)] transition-colors">
                                {{ $post->title }}
                            </a>
                        </td>
                        <td class="py-2">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px]">
                                {{ $post->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="py-2 text-right">
                            <span class="inline-flex items-center gap-1 text-slate-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ number_format($post->views) }}
                            </span>
                        </td>
                        <td class="py-2 text-right text-slate-400 text-xs">
                            {{ $post->created_at->format('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <p class="text-sm">Belum ada postingan</p>
                                <a href="{{ route('admin.posts.create') }}"
                                    class="text-xs text-[var(--accent-primary)] hover:underline">
                                    Buat postingan pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
