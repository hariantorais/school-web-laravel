<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Livewire\Forms\PostForm;
use App\Services\PostService;
use App\Models\Category;
use App\Models\Post;
use Livewire\Attributes\Title;

new class extends Component {
    use WithFileUploads;

    public PostForm $form;

    // Menyimpan state mode halaman saat ini
    public bool $isEditMode = false;
    public ?string $existingImageUrl = null;

    /**
     * Lifecycle Mount: Memeriksa apakah ada parameter 'slug' yang dikirim
     */
    public function mount(?string $slug = null)
    {
        if ($slug) {
            $post = Post::where('slug', $slug)->firstOrFail();
            $this->form->setPost($post);
            $this->isEditMode = true;
            $this->existingImageUrl = $post->featured_image ? asset('storage/' . $post->featured_image) : null;
        }
    }

    /**
     * Aksi Pengiriman Form (Submit Handler)
     */
    public function save(PostService $postService)
    {
        // Eksekusi proses simpan melalui Form Object & kirim dependensi PostService
        $this->form->store($postService);

        if ($this->isEditMode) {
            $this->dispatch('toast', type: 'success', message: 'Postingan berhasil diperbarui!');
            $this->existingImageUrl = $this->form->post->featured_image ? asset('storage/' . $this->form->post->featured_image) : null;
        } else {
            $this->dispatch('toast', type: 'success', message: 'Postingan resmi diterbitkan!');
            $this->form->clear();
            $this->dispatch('trix-clear'); // Reset Trix Editor UI
        }
    }

    /**
     * Berbagi data kategori ke tampilan blade
     */
    public function with(): array
    {
        return [
            'categories' => Category::all(),
        ];
    }
}; ?>

<div class=" bg-white rounded-xl border border-slate-200 shadow-sm p-4 sm:p-6 md:p-8">

    <div class="mb-5 pb-4 border-b border-slate-100">
        <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
            {{ $isEditMode ? 'Edit Postingan' : 'Buat Postingan Baru' }}
        </h2>
        <p class="text-xs text-slate-400 mt-1 leading-relaxed">
            Gunakan form ini untuk menyebarkan informasi, berita resmi, atau agenda kegiatan instansi.
        </p>
    </div>

    @if (session()->has('message'))
        <div
            class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-lg flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="leading-tight">{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4 sm:space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Judul Postingan /
                    Berita</label>
                <input type="text" wire:model="form.title" placeholder="Contoh: Pelaksanaan Ujian Akhir..."
                    class="w-full px-3.5 py-3 sm:py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-700 transition-all appearance-none">
                @error('form.title')
                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Kategori</label>
                <select wire:model="form.category_id"
                    class="w-full px-3.5 py-3 sm:py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-700 transition-all cursor-pointer">
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('form.category_id')
                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Gambar Sampul</label>
            <div
                class="mt-1 flex justify-center px-4 py-5 sm:px-6 sm:pt-5 sm:pb-6 border-2 border-slate-200 border-dashed rounded-lg bg-slate-50">
                <div class="space-y-2 text-center w-full">

                    @if ($form->featured_image)
                        <div class="mb-2 flex flex-col items-center">
                            <span class="text-[11px] font-semibold text-teal-600 mb-1">Preview File Baru:</span>
                            <img src="{{ $form->featured_image->temporaryUrl() }}"
                                class="max-h-36 sm:max-h-40 rounded-lg object-cover shadow-sm">
                        </div>
                    @elseif($isEditMode && $existingImageUrl)
                        <div class="mb-2 flex flex-col items-center">
                            <span class="text-[11px] font-semibold text-slate-500 mb-1">Gambar Saat Ini:</span>
                            <img src="{{ $existingImageUrl }}"
                                class="max-h-36 sm:max-h-40 rounded-lg object-cover shadow-sm">
                        </div>
                    @else
                        <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none"
                            viewBox="0 0 48 48">
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4-4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    @endif

                    <div class="flex text-sm text-slate-600 justify-center">
                        <label
                            class="relative cursor-pointer bg-white border border-slate-200 sm:border-transparent px-4 py-2 sm:p-0 rounded-md font-medium text-teal-700 hover:text-teal-800 shadow-sm sm:shadow-none w-full sm:w-auto block">
                            <span>{{ $isEditMode ? 'Ganti file gambar' : 'Unggah file gambar' }}</span>
                            <input type="file" wire:model="form.featured_image" class="sr-only">
                        </label>
                    </div>
                    <p class="text-[11px] text-slate-400">PNG, JPG, JPEG (Otomatis dikompres ke WebP)</p>
                </div>
            </div>
            @error('form.featured_image')
                <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div wire:ignore>
            <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Isi Pengumuman /
                Postingan</label>
            <div x-data="{
                value: @entangle('form.content'),
                clearForm() { $refs.trix.editor.loadHTML('') }
            }" x-init="$watch('value', v => { if (v === '') clearForm(); });
            if (value) { $refs.trix.editor.loadHTML(value) }" @trix-clear.window="clearForm()"
                @trix-change="value = $event.target.value"
                class="bg-white border border-slate-200 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-teal-500/20 focus-within:border-teal-700 transition-all">

                <input id="content" type="hidden" name="content">
                <trix-editor input="content" x-ref="trix"
                    class="prose max-w-none p-3 sm:p-4 text-sm text-slate-700 placeholder-slate-400 focus:outline-none break-words min-h-[280px] sm:min-h-[350px]"></trix-editor>
            </div>
        </div>
        @error('form.content')
            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
        @enderror

        <div
            class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-slate-100">

            <div class="flex items-center justify-start gap-4 bg-slate-50 sm:bg-transparent p-3 sm:p-0 rounded-lg">
                <label class="inline-flex items-center text-sm text-slate-600 cursor-pointer select-none">
                    <input type="radio" wire:model="form.status" value="draft"
                        class="w-4 h-4 text-teal-700 focus:ring-teal-500/20 border-slate-300">
                    <span class="ml-2 text-xs sm:text-sm">Draft</span>
                </label>
                <label class="inline-flex items-center text-sm text-slate-600 cursor-pointer select-none">
                    <input type="radio" wire:model="form.status" value="published"
                        class="w-4 h-4 text-teal-700 focus:ring-teal-500/20 border-slate-300">
                    <span class="ml-2 text-xs sm:text-sm text-teal-700 font-medium">Terbitkan</span>
                </label>
            </div>

            <button type="submit" wire:loading.attr="disabled"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-semibold text-sm px-5 py-3 sm:py-2.5 rounded-lg transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                <span wire:loading.remove>{{ $isEditMode ? 'Perbarui Postingan' : 'Simpan Postingan' }}</span>
                <span wire:loading
                    class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                <span wire:loading>Memproses...</span>
            </button>
        </div>

    </form>
</div>
