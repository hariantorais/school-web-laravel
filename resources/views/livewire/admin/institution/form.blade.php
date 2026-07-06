<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Layout, Title};
use Livewire\WithFileUploads;
use App\Models\Institution;
use App\Livewire\Forms\InstitutionForm;

new class extends Component {
    use WithFileUploads;

    public InstitutionForm $form;

    #[Title('Pengaturan Institusi')]
    public function mount()
    {
        $institution = Institution::first() ?? new Institution();
        $this->form->setInstitution($institution);
    }

    public function save()
    {
        $this->form->save();
        $this->dispatch('toast', type: 'success', message: 'Data institusi berhasil disimpan!');
    }

    public function removeLogo()
    {
        $this->form->removeLogo();
        session()->flash('success', 'Logo berhasil dihapus!');
    }

    public function removeFavicon()
    {
        $this->form->removeFavicon();
        session()->flash('success', 'Favicon berhasil dihapus!');
    }
};

?>

<div x-data="{ activeTab: 'informasi' }">

    <x-slot name="subhead">
        Kelola data profil institusi Anda
    </x-slot>

    <x-slot name="headerAction">
        <button type="submit" form="institution-form"
            class="px-6 py-2.5 bg-[var(--accent-primary)] hover:bg-[var(--accent-hover)] text-white font-medium rounded-xl transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Simpan Perubahan
        </button>
    </x-slot>



    <form id="institution-form" wire:submit.prevent="save">
        {{-- Tab Navigation --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-1.5 mb-6 flex flex-wrap gap-1">
            <button type="button" @click="activeTab = 'informasi'"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200 whitespace-nowrap"
                :class="activeTab === 'informasi' ? 'bg-[var(--accent-primary)] text-white shadow-sm' :
                    'text-slate-600 hover:bg-slate-100'">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi Dasar
                </div>
            </button>
            <button type="button" @click="activeTab = 'logo'"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200 whitespace-nowrap"
                :class="activeTab === 'logo' ? 'bg-[var(--accent-primary)] text-white shadow-sm' :
                    'text-slate-600 hover:bg-slate-100'">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Logo
                </div>
            </button>
            <button type="button" @click="activeTab = 'deskripsi'"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200 whitespace-nowrap"
                :class="activeTab === 'deskripsi' ? 'bg-[var(--accent-primary)] text-white shadow-sm' :
                    'text-slate-600 hover:bg-slate-100'">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    Deskripsi & Sejarah
                </div>
            </button>
            <button type="button" @click="activeTab = 'kontak'"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200 whitespace-nowrap"
                :class="activeTab === 'kontak' ? 'bg-[var(--accent-primary)] text-white shadow-sm' :
                    'text-slate-600 hover:bg-slate-100'">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Kontak
                </div>
            </button>
            <button type="button" @click="activeTab = 'sosial'"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200 whitespace-nowrap"
                :class="activeTab === 'sosial' ? 'bg-[var(--accent-primary)] text-white shadow-sm' :
                    'text-slate-600 hover:bg-slate-100'">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9" />
                    </svg>
                    Media Sosial
                </div>
            </button>
            <button type="button" @click="activeTab = 'seo'"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200 whitespace-nowrap"
                :class="activeTab === 'seo' ? 'bg-[var(--accent-primary)] text-white shadow-sm' :
                    'text-slate-600 hover:bg-slate-100'">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    SEO
                </div>
            </button>
        </div>

        {{-- Tab Content --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6">
            {{-- Tab 1: Informasi Dasar --}}
            <div x-show="activeTab === 'informasi'" x-transition:enter.duration.300ms.opacity class="space-y-4">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Informasi Dasar</h3>
                <x-form.input label="Nama Institusi" name="form.name" placeholder="Masukkan nama institusi" />
                <x-form.input label="Motto" name="form.motto" placeholder="Masukkan motto institusi" />
                <x-form.input label="Tagline" name="form.tagline" placeholder="Masukkan tagline institusi" />
                <x-form.textarea label="Visi" name="form.vision" placeholder="Masukkan visi institusi"
                    rows="3" />
                <x-form.textarea label="Misi" name="form.mission"
                    placeholder="Masukkan misi institusi (pisahkan dengan baris baru)" rows="6" />
            </div>

            {{-- Tab 2: Logo --}}
            <div x-show="activeTab === 'logo'" x-transition:enter.duration.300ms.opacity class="space-y-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Logo & Favicon</h3>

                {{-- Logo --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-3">
                        Logo
                    </label>
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <div
                            class="w-32 h-32 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/50 flex items-center justify-center overflow-hidden flex-shrink-0">
                            <img src="{{ $this->form->getLogoUrl() }}" alt="Logo"
                                class="w-full h-full object-contain p-3">
                        </div>
                        <div class="flex-1 w-full">
                            <input type="file" wire:model="form.logo" accept="image/*"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-[var(--accent-primary)] file:text-white hover:file:bg-[var(--accent-hover)] cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1.5">Format: JPG, PNG, SVG. Maks 2MB</p>
                            @error('form.logo')
                                <span class="text-[10px] text-rose-600 font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                            @if ($form->oldLogo)
                                <button type="button" wire:click="removeLogo"
                                    class="text-red-500 text-xs hover:text-red-700 mt-2 transition-colors">
                                    Hapus Logo
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Favicon --}}
                <div class="pt-6 border-t border-slate-200/60">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-3">
                        Favicon
                    </label>
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <div
                            class="w-16 h-16 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/50 flex items-center justify-center overflow-hidden flex-shrink-0">
                            <img src="{{ $this->form->getFaviconUrl() }}" alt="Favicon"
                                class="w-full h-full object-contain p-2">
                        </div>
                        <div class="flex-1 w-full">
                            <input type="file" wire:model="form.favicon" accept="image/*"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-[var(--accent-primary)] file:text-white hover:file:bg-[var(--accent-hover)] cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1.5">Format: ICO, PNG. Maks 1MB</p>
                            @error('form.favicon')
                                <span class="text-[10px] text-rose-600 font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                            @if ($form->oldFavicon)
                                <button type="button" wire:click="removeFavicon"
                                    class="text-red-500 text-xs hover:text-red-700 mt-2 transition-colors">
                                    Hapus Favicon
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 3: Deskripsi & Sejarah --}}
            <div x-show="activeTab === 'deskripsi'" x-transition:enter.duration.300ms.opacity class="space-y-4">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Deskripsi & Sejarah</h3>
                <x-form.textarea label="Deskripsi" name="form.description" placeholder="Masukkan deskripsi institusi"
                    rows="4" />
                <x-form.textarea label="Sejarah" name="form.history" placeholder="Masukkan sejarah institusi"
                    rows="8" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <x-form.input label="Website" name="form.website" type="url"
                        placeholder="https://example.com" />
                    <x-form.input label="Tahun Berdiri" name="form.established_year" type="number"
                        placeholder="2000" />
                </div>
                <x-form.input label="Akreditasi" name="form.accreditation"
                    placeholder="Contoh: Terakreditasi Unggul" />
            </div>

            {{-- Tab 4: Kontak --}}
            <div x-show="activeTab === 'kontak'" x-transition:enter.duration.300ms.opacity class="space-y-4">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Kontak</h3>
                <x-form.textarea label="Alamat" name="form.address" placeholder="Masukkan alamat lengkap"
                    rows="2" />
                <x-form.input label="Email" name="form.email" type="email" placeholder="Masukkan email" />
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form.input label="WhatsApp 1" name="form.whatsapp" placeholder="6281234567890" />
                    <x-form.input label="WhatsApp 2" name="form.whatsapp_2" placeholder="6281234567890 (opsional)" />
                </div>
            </div>

            {{-- Tab 5: Media Sosial --}}
            <div x-show="activeTab === 'sosial'" x-transition:enter.duration.300ms.opacity class="space-y-4">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Media Sosial</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input label="Facebook" name="form.facebook" type="url"
                        placeholder="https://facebook.com/username" />
                    <x-form.input label="Instagram" name="form.instagram" type="url"
                        placeholder="https://instagram.com/username" />
                    <x-form.input label="YouTube" name="form.youtube" type="url"
                        placeholder="https://youtube.com/@channel" />
                    <x-form.input label="Twitter" name="form.twitter" type="url"
                        placeholder="https://twitter.com/username" />
                    <x-form.input label="TikTok" name="form.tiktok" type="url"
                        placeholder="https://tiktok.com/@username" />
                </div>
            </div>

            {{-- Tab 6: SEO --}}
            <div x-show="activeTab === 'seo'" x-transition:enter.duration.300ms.opacity class="space-y-4">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Meta SEO</h3>
                <x-form.input label="Meta Title" name="form.meta_title" placeholder="Masukkan meta title" />
                <x-form.textarea label="Meta Description" name="form.meta_description"
                    placeholder="Masukkan meta description" rows="3" />
                <x-form.input label="Meta Keywords" name="form.meta_keywords"
                    placeholder="keyword1, keyword2, keyword3" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <x-form.input label="Timezone" name="form.timezone" placeholder="Asia/Jakarta" />
                    <x-form.input label="Locale" name="form.locale" placeholder="id" />
                </div>
            </div>
        </div>
    </form>
</div>
