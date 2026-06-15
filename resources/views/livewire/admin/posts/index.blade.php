<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithPagination;

    // State untuk Pencarian dan Filter
    public string $search = '';
    public string $filterCategory = '';
    public string $filterStatus = '';

    // Reset halaman pagination jika user mengetik di kolom pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Aksi Menghapus Postingan Sekolah Resmi beserta Gambar Sampulnya
     */
    public function deletePost(int $id)
    {
        $post = Post::findOrFail($id);

        // Hapus file fisik gambar dari storage jika ada
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        session()->flash('message', 'Postingan berhasil dihapus permanen dari sistem.');
    }

    /**
     * Mengambil dan memfilter data postingan secara reaktif
     */
    public function with(): array
    {
        $posts = Post::with(['category', 'user'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterCategory, function ($query) {
                $query->where('category_id', $this->filterCategory);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate(10);

        return [
            'posts' => $posts,
            'categories' => Category::all(),
        ];
    }
}; ?>

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Manajemen Postingan</h1>
            <p class="text-xs text-slate-400 mt-1">Kelola semua berita, pengumuman resmi, dan agenda kegiatan sekolah di
                sini.</p>
        </div>
        <div>
            <a href="{{ route('admin.posts.create') }}" wire:navigate
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-semibold text-sm px-4 py-3 sm:py-2.5 rounded-lg transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tulis Artikel Baru
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div
            class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-lg flex items-center gap-2.5 shadow-sm">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white p-4 border border-slate-200 rounded-xl shadow-sm grid grid-cols-1 sm:grid-cols-3 gap-3.5">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul postingan..."
                class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-700 focus:outline-none transition-all">
        </div>

        <div>
            <select wire:model.live="filterCategory"
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-700 focus:outline-none transition-all cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <select wire:model.live="filterStatus"
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-700 focus:outline-none transition-all cursor-pointer">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="published">Diterbitkan</option>
            </select>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-4 w-16 text-center">Info</th>
                        <th class="p-4">Judul Artikel</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal Rilis</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($posts as $post)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 text-center">
                                @if ($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                                        class="w-10 h-10 object-cover rounded-md border border-slate-100 mx-auto shadow-inner">
                                @else
                                    <div
                                        class="w-10 h-10 bg-slate-100 text-slate-400 rounded-md flex items-center justify-center mx-auto text-xs">
                                        No img</div>
                                @endif
                            </td>
                            <td class="p-4 font-medium text-slate-900 max-w-xs">
                                <p class="truncate" title="{{ $post->title }}">{{ $post->title }}</p>
                                <p class="text-xs text-slate-400 font-normal mt-0.5">Oleh: {{ $post->user->name }}</p>
                            </td>
                            <td class="p-4 text-slate-500">{{ $post->category->name }}</td>
                            <td class="p-4">
                                @if ($post->status === 'published')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Draft</span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-400 text-xs">
                                {{ $post->published_at ? $post->published_at->format('d M Y, H:i') : 'Belum rilis' }}
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.posts.edit', $post->slug) }}" wire:navigate
                                        class="p-1.5 text-slate-500 hover:text-teal-700 hover:bg-teal-50 rounded-md transition-colors"
                                        title="Ubah data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <button
                                        @click="confirm('Apakah Anda yakin ingin menghapus pengumuman ini secara permanen?') ? $wire.deletePost({{ $post->id }}) : null"
                                        class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-colors"
                                        title="Hapus data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1a3 3 0 003 3h10M9 3h6m2 5H5">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">Tidak ada postingan sekolah yang
                                sesuai dengan filter pencarian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="block md:hidden divide-y divide-slate-100">
            @forelse($posts as $post)
                <div class="p-4 flex flex-col gap-3.5 bg-white">
                    <div class="flex items-start gap-3">
                        @if ($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                                class="w-12 h-12 object-cover rounded-lg border border-slate-100 flex-shrink-0 shadow-inner">
                        @else
                            <div
                                class="w-12 h-12 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center text-[10px] flex-shrink-0 border border-slate-100">
                                No Img</div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-slate-900 break-words line-clamp-2 leading-snug">
                                {{ $post->title }}</h4>
                            <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                <span
                                    class="text-[10px] font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded">{{ $post->category->name }}</span>
                                @if ($post->status === 'published')
                                    <span
                                        class="text-[10px] font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded border border-emerald-100">Aktif</span>
                                @else
                                    <span
                                        class="text-[10px] font-semibold bg-amber-50 text-amber-700 px-2 py-0.5 rounded border border-amber-100">Draft</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-between pt-3 border-t border-slate-100/70 text-xs text-slate-400">
                        <div>
                            <p>Oleh: <span class="text-slate-600 font-medium">{{ $post->user->name }}</span></p>
                            <p class="text-[10px] mt-0.5">
                                {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'Belum rilis' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.posts.edit', $post->slug) }}" wire:navigate
                                class="inline-flex items-center justify-center gap-1 bg-slate-50 border border-slate-200 text-slate-700 font-medium px-3 py-2 rounded-lg text-xs active:bg-slate-100 transition-colors">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit
                            </a>
                            <button
                                @click="confirm('Hapus permanen artikel ini?') ? $wire.deletePost({{ $post->id }}) : null"
                                class="inline-flex items-center justify-center gap-1 bg-rose-50 border border-rose-200 text-rose-700 font-medium px-3 py-2 rounded-lg text-xs active:bg-rose-100 transition-colors">
                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-16v1a3 3 0 003 3h10M9 3h6m2 5H5">
                                    </path>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-xs text-slate-400">Tidak ada postingan sekolah.</div>
            @endforelse
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-100">
            {{ $posts->links() }}
        </div>

    </div>
</div>
