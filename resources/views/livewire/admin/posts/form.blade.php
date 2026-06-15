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

    /**
     * Lifecycle Mount
     */
    public function mount(?string $slug = null): void
    {
        if ($slug) {
            $post = Post::where('slug', $slug)->firstOrFail();
            $this->form->setPost($post);
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

        </div>
    </form>
</div>
