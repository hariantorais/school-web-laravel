<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Institution;

new #[Layout('layouts.home')] class extends Component {
    public $institution;

    public function mount()
    {
        $this->institution = Institution::first();
    }

    public function getMissionListProperty()
    {
        $mission = $this->institution->mission ?? '';
        $items = array_filter(explode("\n", trim($mission)));
        return $items;
    }

    public function getVisionListProperty()
    {
        $vision = $this->institution->vision ?? '';
        return array_filter(explode("\n", trim($vision)));
    }

    public function getDescriptionProperty()
    {
        return $this->institution->description ?? '';
    }

    public function getHistoryProperty()
    {
        return $this->institution->history ?? '';
    }
};

?>

@section('title', 'Profil - ' . ($this->institution->name ?? 'Pondok Pesantren'))
@section('meta_description',
    'Profil lengkap ' .
    ($this->institution->name ?? 'Pondok Pesantren') .
    ' - Visi, Misi,
    Sejarah, dan Informasi Kontak')

    <div class="min-h-screen bg-[#FDFBF7] text-[#1E293B] font-['Plus_Jakarta_Sans',sans-serif]">

        {{-- ========================================================================= --}}
        {{-- BLOCK 1: HERO / PAGE HEADER --}}
        {{-- ========================================================================= --}}
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
                <div class="max-w-3xl mx-auto space-y-4">
                    <div
                        class="inline-flex items-center gap-2 bg-[#A31D1D]/90 backdrop-blur-sm px-4 py-2 rounded-full mb-2 border border-white/10">
                        <span class="text-xs font-semibold text-[#D4AF37] uppercase tracking-wider">Profil</span>
                    </div>
                    <h1 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                        Profil <span class="text-[#D4AF37]">{{ $this->institution->name ?? 'Kami' }}</span>
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed font-medium">
                        {{ $this->institution->tagline ?? 'Mengenal lebih dekat tentang institusi kami' }}
                    </p>
                </div>
            </div>
        </section>

        {{-- ========================================================================= --}}
        {{-- BLOCK 2: DESKRIPSI SINGKAT --}}
        {{-- ========================================================================= --}}
        @if (!empty($this->description))
            <section class="py-16 lg:py-20 relative bg-white">
                <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center gap-2 mb-4">
                            <span class="h-px w-8 bg-[#A31D1D]"></span>
                            <span class="text-xs font-bold text-[#A31D1D] uppercase tracking-wider">Tentang Kami</span>
                            <span class="h-px w-8 bg-[#A31D1D]"></span>
                        </div>
                        <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#1E293B]">
                            Mengenal <span class="text-[#A31D1D]">Lebih Dekat</span>
                        </h2>
                    </div>
                    <div class="prose prose-lg max-w-none text-slate-600 leading-relaxed">
                        {!! $this->description !!}
                    </div>
                </div>
            </section>
        @endif

        {{-- ========================================================================= --}}
        {{-- BLOCK 3: VISI & MISI --}}
        {{-- ========================================================================= --}}
        <section class="py-20 lg:py-28 bg-[#FAF8F5] border-t border-b border-slate-200/50 relative overflow-hidden">
            <!-- Dekrasi Grid Halus Minimalis di Latar Belakang -->
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b05_1px,transparent_1px),linear-gradient(to_bottom,#1e293b05_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none">
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- HEADLINE SEKSI --}}
                <div class="max-w-3xl mx-auto text-center mb-20 space-y-3">
                    <div class="text-center mb-12">

                        <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#1E293B]">
                            Visi <span class="text-[#A31D1D]">Misi</span>
                        </h2>
                    </div>
                </div>

                {{-- GRID UTAMA: VISI & MISI --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch mb-16">
                    {{-- Box Visi (Premium Slate Accent) --}}
                    <div
                        class="lg:col-span-5 bg-[#1E293B] text-white rounded-2xl p-8 sm:p-10 shadow-xl shadow-slate-900/10 flex flex-col justify-between relative overflow-hidden group">
                        <div
                            class="absolute -right-16 -top-16 w-48 h-48 bg-[#A31D1D]/20 rounded-full blur-3xl pointer-events-none">
                        </div>

                        <div>
                            <span class="text-[11px] font-mono tracking-[0.25em] text-[#D4AF37] uppercase block mb-6">/
                                Visi</span>
                            @if (!empty($this->institution->vision))
                                <p
                                    class="font-heading text-xl sm:text-2xl font-medium tracking-wide leading-relaxed italic text-slate-100">
                                    "{!! nl2br(e($this->institution->vision)) !!}"
                                </p>
                            @else
                                <p class="text-slate-400 italic text-sm">Visi belum dikonfigurasi.</p>
                            @endif
                        </div>

                        <div class="mt-12 pt-6 border-t border-white/10 flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Target Utama</span>
                            <span
                                class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-sm border border-white/10">🎯</span>
                        </div>
                    </div>

                    {{-- Box Misi (Clean Card Minimalist) --}}
                    <div
                        class="lg:col-span-7 bg-white rounded-2xl p-8 sm:p-10 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                        <div>
                            <span class="text-[11px] font-mono tracking-[0.25em] text-[#A31D1D] uppercase block mb-6">/
                                Misi</span>

                            @if (!empty($this->missionList))
                                <div class="divide-y divide-slate-100">
                                    @foreach ($this->missionList as $index => $mission)
                                        <div class="flex items-start gap-4 py-4 first:pt-0 last:pb-0 group">
                                            <span
                                                class="font-serif text-xl font-bold text-[#A31D1D]/30 group-hover:text-[#A31D1D] transition-colors duration-300 w-6 shrink-0 pt-0.5">
                                                {{ sprintf('%02d', $index + 1) }}
                                            </span>
                                            <p
                                                class="text-sm sm:text-base text-slate-600 group-hover:text-slate-900 transition-colors duration-200 leading-relaxed">
                                                {{ $mission }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-slate-400 italic text-sm">Misi belum dikonfigurasi.</p>
                            @endif
                        </div>
                    </div>
                </div>


            </div>
        </section>

        {{-- ========================================================================= --}}
        {{-- BLOCK 4: SEJARAH --}}
        {{-- ========================================================================= --}}
        @if (!empty($this->history))
            <section class="py-16 lg:py-20 relative bg-white">
                <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center gap-2 mb-4">
                            <span class="h-px w-8 bg-[#A31D1D]"></span>
                            <span class="text-xs font-bold text-[#A31D1D] uppercase tracking-wider">Sejarah</span>
                            <span class="h-px w-8 bg-[#A31D1D]"></span>
                        </div>
                        <h2 class="font-heading text-3xl sm:text-4xl font-bold text-[#1E293B]">
                            Sejarah <span class="text-[#A31D1D]">Kami</span>
                        </h2>
                        @if ($this->institution->established_year)
                            <p class="text-sm text-slate-500 mt-2">Berdiri sejak tahun
                                {{ $this->institution->established_year }}</p>
                        @endif
                    </div>
                    <div class="prose prose-lg max-w-none text-slate-600 leading-relaxed">
                        {!! $this->history !!}
                    </div>

                    @if (!empty($this->institution->accreditation))
                        <div class="mt-8 text-center">
                            <div
                                class="inline-flex items-center gap-3 bg-[#F8F6F3] px-6 py-3 rounded-full border border-slate-200/60">
                                <span class="text-sm text-slate-500">Akreditasi</span>
                                <span class="w-px h-6 bg-slate-200"></span>
                                <span class="font-bold text-[#A31D1D]">{{ $this->institution->accreditation }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- ========================================================================= --}}
        {{-- BLOCK 5: CTA / PENUTUP --}}
        {{-- ========================================================================= --}}
        <section class="py-16 lg:py-20 relative bg-[#F8F6F3]">
            <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="bg-gradient-to-br from-[#1E293B] to-[#2D3A4F] rounded-3xl p-8 sm:p-12 lg:p-16">
                    <h3 class="font-heading text-2xl sm:text-3xl font-bold text-white mb-4">
                        Bergabunglah dengan <span class="text-[#D4AF37]">Kami</span>
                    </h3>
                    <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto mb-6">
                        Wujudkan generasi penghafal Al-Qur'an yang berakhlak mulia.
                        Daftarkan putra-putri Anda di {{ $this->institution->name ?? '' }}.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ !empty($this->institution->whatsapp) ? 'https://wa.me/' . $this->institution->whatsapp : '/contact' }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-[#D4AF37] text-[#1E293B] font-semibold rounded-xl hover:bg-[#C49A2E] transition-all duration-300">
                            <span>Hubungi Kami</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <a href="/posts"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition-all duration-300 border border-white/20">
                            <span>Lihat Berita</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>
