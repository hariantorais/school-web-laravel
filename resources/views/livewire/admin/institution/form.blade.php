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
            {{-- TAB STATISTIK --}}
            <button type="button" @click="activeTab = 'statistik'"
                class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200 whitespace-nowrap"
                :class="activeTab === 'statistik' ? 'bg-[var(--accent-primary)] text-white shadow-sm' :
                    'text-slate-600 hover:bg-slate-100'">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Statistik
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

            {{-- Tab 7: Statistik --}}
            <div x-show="activeTab === 'statistik'" x-transition:enter.duration.300ms.opacity class="space-y-4">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Data Statistik</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-form.input label="Jumlah Santri Aktif" name="form.total_students" type="number"
                        placeholder="0" />
                    <x-form.input label="Jumlah Alumni Hafizh" name="form.total_alumni" type="number"
                        placeholder="0" />
                    <x-form.input label="Jumlah Asatidzah" name="form.total_teachers" type="number"
                        placeholder="0" />
                </div>

                <x-form.input label="URL Video Profil (YouTube)" name="form.profile_video_url" type="url"
                    placeholder="https://www.youtube.com/embed/VIDEO_ID" />
                <p class="text-[10px] text-slate-400 -mt-2">Masukkan URL embed YouTube, contoh:
                    https://www.youtube.com/embed/VIDEO_ID</p>
            </div>
        </div>
    </form>
</div>
