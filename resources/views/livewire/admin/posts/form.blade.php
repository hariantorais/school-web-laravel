<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Forms\PostForm;
use App\Models\Category;
use App\Models\Post;
use function Livewire\Volt\layout;
use function Livewire\Volt\title;

new class extends Component {
    use WithFileUploads;

    // State utama tunggal data form
    public PostForm $form;

    // 🔥 State untuk preview
    public bool $showPreview = false;

    /**
     * Lifecycle Mount
     */
    public function mount(?string $slug = null): void
    {
        if ($slug) {
            $post = Post::where('slug', $slug)->firstOrFail();
            $this->form->setPost($post);

            // 🔥 Aktifkan preview jika ada youtube_url
            if ($this->form->youtube_url) {
                $this->showPreview = true;
            }
        }
    }

    /**
     * Proses Penyimpanan Data
     */
    public function save(): void
    {
        $this->form->validate();

        try {
            $result = $this->form->store();

            $this->dispatch('toast', type: $result['type'], message: $result['message']);

            if (!$this->form->id) {
                $this->dispatch('trix-clear');
            }

            $this->redirect(route('admin.posts.index'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Gagal memproses artikel: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $title = $this->form->title ? 'Edit Postingan' : 'Tulis Postingan';
        return $this->view()->title($title);
    }

    /**
     * Menyediakan data penunjang
     */
    public function with(): array
    {
        return [
            'categories' => Category::all(),
        ];
    }

    /**
     * 🔥 Helper untuk mendapatkan ID YouTube dari URL
     */
    public function getYoutubeIdProperty(): ?string
    {
        if (!$this->form->youtube_url) {
            return null;
        }

        $url = $this->form->youtube_url;

        // Extract video ID dari berbagai format URL YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }

        // Coba parse dari URL
        parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $params);
        return $params['v'] ?? null;
    }

    /**
     * 🔥 Helper untuk mendapatkan thumbnail YouTube
     */
    public function getYoutubeThumbnailProperty(): ?string
    {
        $videoId = $this->youtube_id;
        if (!$videoId) {
            return null;
        }

        // Gunakan thumbnail HQ default
        return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
    }

    /**
     * 🔥 Helper untuk mendapatkan embed URL
     */
    public function getYoutubeEmbedUrlProperty(): ?string
    {
        $videoId = $this->youtube_id;
        if (!$videoId) {
            return null;
        }

        return "https://www.youtube.com/embed/{$videoId}?autoplay=0&rel=0&modestbranding=1";
    }

    /**
     * 🔥 Toggle preview
     */
    public function togglePreview(): void
    {
        $this->showPreview = !$this->showPreview;
    }

    /**
     * 🔥 Hapus URL YouTube
     */
    public function removeYoutube(): void
    {
        $this->form->youtube_url = null;
        $this->showPreview = false;
    }

    /**
     * 🔥 Update preview saat URL berubah
     */
    public function updatedFormYoutubeUrl(): void
    {
        if ($this->form->youtube_url && $this->youtube_id) {
            $this->showPreview = true;
        } else {
            $this->showPreview = false;
        }
    }
}; ?>

<div class="animate-fade-in space-y-4">

    <x-slot name="subhead"> Kelola konten narasi, kategori, dan media publikasi instansi akademik.</x-slot>

    <form wire:submit.prevent="save" class="grid grid-cols-1 lg:grid-cols-4 gap-5 items-start">

        <div class="lg:col-span-3 space-y-5">

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full">

                    <div class="sm:col-span-2">
                        <x-form.input label="Judul Postingan / Berita *" name="form.title"
                            placeholder="Contoh: Pelaksanaan Ujian Akhir Semester..." />
                    </div>

                    <div>
                        <x-form.select label="Kategori Artikel *" name="form.category_id">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>

                </div>
            </div>

            <x-form.trix label="Isi Pengumuman / Konten Narasi *" name="form.content"
                placeholder="Tulis narasi berita atau isi pengumuman sekolah di sini..." height="min-h-[450px]" />
        </div>
        <div class="lg:col-span-1 space-y-5 ">

            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-2">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Metode Penerbitan</h3>
                </div>

                <div class="flex flex-col gap-2.5">
                    <label
                        class="inline-flex items-center text-sm text-slate-600 cursor-pointer select-none group bg-slate-50 p-2.5 rounded-xl border border-slate-100 hover:border-slate-200/80 transition-colors">
                        <input type="radio" wire:model="form.status" value="draft"
                            class="w-4 h-4 text-(--accent-primary) focus:ring-(--accent-focus) border-slate-300 transition-colors cursor-pointer">
                        <span
                            class="ml-2.5 text-xs font-bold text-slate-500 group-hover:text-slate-800 transition-colors">
                            Simpan Sebagai Draft
                        </span>
                    </label>

                    <label
                        class="inline-flex items-center text-sm text-slate-600 cursor-pointer select-none group bg-slate-50 p-2.5 rounded-xl border border-slate-100 hover:border-slate-200/80 transition-colors">
                        <input type="radio" wire:model="form.status" value="published"
                            class="w-4 h-4 text-(--accent-primary) focus:ring-(--accent-focus) border-slate-300 transition-colors cursor-pointer">
                        <span
                            class="ml-2.5 text-xs font-bold text-(--accent-primary) group-hover:text-(--accent-hover) transition-colors">
                            Terbitkan Sekarang
                        </span>
                    </label>
                </div>

                {{-- Tombol Submit Eksekusi Finis --}}
                <div class="pt-2 border-t border-slate-100">
                    <x-ui.button type="submit" variant="primary" size="md" loading="save"
                        class="w-full justify-center py-3">
                        <x-heroicon-s-paper-airplane class="w-4 h-4" />
                        <span>{{ $this->form->id ? 'Perbarui Konten' : 'Publish Artikel' }}</span>
                    </x-ui.button>
                </div>
            </div>

            {{-- BOX PANEL 2: FEATURED IMAGE (GAMBAR SAMPUL ARTIKEL) --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="border-b border-slate-100 pb-2">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Gambar Sampul</h3>
                </div>

                <div class="w-full">
                    <x-form.image-upload label="" name="form.image" :modelId="$this->form->id" modelClass="App\Models\Post"
                        placeholder="Pilih Banner Artikel" hint="Format PNG, JPG, WebP (Maks 2MB)" />
                </div>
            </div>

            {{-- 🔥 BOX PANEL 3: YOUTUBE URL DENGAN PREVIEW INTERAKTIF --}}
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="border-b border-slate-100 pb-2">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <x-heroicon-s-video-camera class="w-4 h-4 text-(--accent-primary)" />
                        YouTube Video
                        <span
                            class="text-[10px] font-normal text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">Opsional</span>
                    </h3>
                </div>

                <div class="w-full space-y-3">
                    {{-- Input URL --}}
                    <x-form.input label="" name="form.youtube_url"
                        wire:model.live.debounce.500ms="form.youtube_url"
                        placeholder="https://www.youtube.com/watch?v=xxxxxxxxxxx"
                        hint="Masukkan URL YouTube (contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ)" />

                    {{-- 🔥 PREVIEW YOUTUBE --}}
                    @if ($this->form->youtube_url && $this->youtube_id)
                        <div class="mt-2 rounded-xl overflow-hidden border border-slate-200/80 relative group">

                            {{-- Thumbnail dengan play button overlay --}}
                            @if (!$showPreview)
                                <div class="relative pt-[56.25%] bg-slate-100 cursor-pointer"
                                    wire:click="togglePreview">
                                    <img src="{{ $this->youtube_thumbnail }}" alt="YouTube Thumbnail"
                                        class="absolute inset-0 w-full h-full object-cover">

                                    {{-- Overlay gelap --}}
                                    <div
                                        class="absolute inset-0 bg-black/20 hover:bg-black/40 transition-colors duration-300 flex items-center justify-center">
                                        <div
                                            class="w-16 h-16 rounded-full bg-red-600/90 flex items-center justify-center shadow-2xl transform hover:scale-110 transition-transform duration-300">
                                            <x-heroicon-s-play class="w-8 h-8 text-white ml-1" />
                                        </div>
                                    </div>

                                    {{-- Label --}}
                                    <div class="absolute bottom-3 left-3">
                                        <span
                                            class="bg-black/70 backdrop-blur-md text-white text-[10px] font-medium px-3 py-1.5 rounded-lg border border-white/20">
                                            Klik untuk memutar
                                        </span>
                                    </div>
                                </div>
                            @endif

                            {{-- Iframe embed --}}
                            @if ($showPreview)
                                <div class="relative pt-[56.25%] bg-black">
                                    <iframe src="{{ $this->youtube_embed_url }}" class="absolute inset-0 w-full h-full"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>

                                    {{-- Tombol close preview --}}
                                    <button type="button" wire:click="togglePreview"
                                        class="absolute top-3 right-3 bg-black/70 hover:bg-black/90 text-white rounded-full p-1.5 shadow-lg transition-colors duration-200 border border-white/20"
                                        title="Tutup preview">
                                        <x-heroicon-s-x-mark class="w-4 h-4" />
                                    </button>
                                </div>
                            @endif

                            {{-- Tombol hapus video --}}
                            <button type="button" wire:click="removeYoutube"
                                class="absolute top-3 right-3 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                title="Hapus Video">
                                <x-heroicon-s-trash class="w-4 h-4" />
                            </button>
                        </div>
                    @endif

                    {{-- 🔥 Informasi tambahan jika URL tidak valid --}}
                    @if ($this->form->youtube_url && !$this->youtube_id)
                        <div
                            class="flex items-center gap-2 text-amber-600 bg-amber-50/80 px-3 py-2 rounded-xl border border-amber-200/50 text-xs font-medium">
                            <x-heroicon-s-exclamation-triangle class="w-4 h-4 shrink-0" />
                            <span>URL YouTube tidak valid. Pastikan URL benar.</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </form>
</div>
