<?php

use Livewire\Volt\Component;
use App\Models\Donation;
use App\Models\DonationTransaction;
use Livewire\Attributes\{Layout, Computed};

new #[Layout('layouts.home')] class extends Component {
    public string $slug;
    public bool $showShareModal = false;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    #[Computed]
    public function donation()
    {
        return Donation::where('slug', $this->slug)
            ->withCount(['transactions' => fn($q) => $q->where('status', 'success')])
            ->firstOrFail();
    }

    #[Computed]
    public function contributors()
    {
        return DonationTransaction::where('donation_id', $this->donation->id)->where('status', 'success')->latest()->get();
    }

    public function toggleShareModal(): void
    {
        $this->showShareModal = !$this->showShareModal;
    }

    public function openShareModal(): void
    {
        $this->showShareModal = true;
    }

    public function closeShareModal(): void
    {
        $this->showShareModal = false;
    }
}; ?>

<div x-data="{
    showShareModal: @entangle('showShareModal'),
    copied: false,
    activeTab: 'deskripsi',
    init() {
        this.$watch('showShareModal', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    },
    openModal() {
        this.showShareModal = true;
    },
    closeModal() {
        this.showShareModal = false;
    }
}" class="text-slate-800 antialiased flex flex-col min-h-screen">

    {{-- ========================================================================= --}}
    {{-- A. INJEKSI HEADER ACTION BAR --}}
    {{-- ========================================================================= --}}
    @section('header-action')
        <button @click="openModal()" type="button"
            class="p-2 -mr-2 rounded-full hover:bg-white/20 active:bg-white/30 text-white/90 transition-all duration-300 cursor-pointer backdrop-blur-sm">
            <x-heroicon-o-share class="w-5 h-5" />
        </button>
    @endsection

    @php
        $isOneTime = $this->donation->type === 'one_time';
        $percentage =
            $this->donation->target_amount > 0
                ? min(100, round(($this->donation->current_amount / $this->donation->target_amount) * 100))
                : 0;
        $shareUrl = urlencode(url()->current());
        $shareText = urlencode('Mari bantu program kebaikan: ' . $this->donation->title);
    @endphp

    {{-- ========================================================================= --}}
    {{-- B. HERO SECTION PREMIUM (Seperti Detail Post) --}}
    {{-- ========================================================================= --}}
    <section class="relative pt-32 pb-16 lg:pt-40 lg:pb-24 overflow-hidden">
        <!-- Background dengan gradient dan pattern -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-[#1E293B] via-[#2D3A4F] to-[#1E293B]"></div>
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.05]">
            </div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-[#A31D1D]/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#D4AF37]/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Content Hero -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">

                <!-- Badge Kategori Premium -->
                <div
                    class="inline-flex items-center gap-2 bg-[#A31D1D]/90 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                    <span class="w-2 h-2 bg-[#D4AF37] rounded-full animate-pulse"></span>
                    <span class="text-xs font-semibold text-[#D4AF37] uppercase tracking-wider">
                        {{ $this->donation->category->name ?? 'Program Kebaikan' }}
                    </span>
                    @if ($this->donation->is_active)
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full ml-1"></span>
                        <span class="text-[10px] font-medium text-emerald-400 uppercase tracking-wider">Aktif</span>
                    @endif
                </div>

                <!-- Judul -->
                <h1
                    class="font-heading text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold text-white leading-tight">
                    {{ $this->donation->title }}
                </h1>

                <!-- Meta Info -->
                <div class="flex flex-wrap items-center justify-center gap-4 mt-6 text-slate-300 text-sm">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-currency-dollar class="w-4 h-4" />
                        <span>Rp {{ number_format($this->donation->current_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-1 h-1 bg-slate-500 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-user-group class="w-4 h-4" />
                        <span>{{ $this->donation->transactions_count }} muhsinin</span>
                    </div>
                    @if ($isOneTime && $this->donation->days_left !== null)
                        <div class="w-1 h-1 bg-slate-500 rounded-full"></div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-clock class="w-4 h-4" />
                            <span>{{ (int) $this->donation->days_left }} Hari Lagi</span>
                        </div>
                    @endif
                </div>

                <!-- Progress Bar di Hero -->
                @if ($isOneTime)
                    <div class="mt-8 max-w-2xl mx-auto">
                        <div class="flex justify-between text-xs text-slate-400 mb-2">
                            <span>Progress Donasi</span>
                            <span class="font-bold text-white">{{ $percentage }}%</span>
                        </div>
                        <div class="w-full h-3 bg-white/10 rounded-full overflow-hidden backdrop-blur-sm">
                            <div class="bg-gradient-to-r from-[#A31D1D] to-[#D4AF37] h-full rounded-full transition-all duration-1000 ease-out relative"
                                style="width: {{ $percentage }}%">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-shimmer">
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-slate-400">
                            <span>Rp 0</span>
                            <span>Target: Rp {{ number_format($this->donation->target_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endif

                <!-- CTA Button di Hero -->
                <!-- resources/views/livewire/donation/partials/hero.blade.php -->
                <!-- Ganti bagian CTA button -->

                <div class="mt-8" id="hero-cta">
                    @if ($this->donation->is_active)
                        <button @click="$dispatch('open-modal', { name: 'donation-modal' })"
                            class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-[#A31D1D] to-[#C0392B] text-white font-bold rounded-full hover:shadow-[0_8px_24px_-6px_rgba(163,29,29,0.5)] transition-all duration-300 transform hover:scale-105 cursor-pointer">
                            <x-heroicon-o-plus-circle class="w-5 h-5" />
                            Donasi Sekarang
                        </button>
                    @else
                        <button disabled
                            class="inline-flex items-center gap-2 px-8 py-3.5 bg-slate-600/50 text-slate-400 font-bold rounded-full cursor-not-allowed">
                            <x-heroicon-o-lock-closed class="w-5 h-5" />
                            Program Selesai
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================================= --}}
    {{-- C. KONTEN UTAMA (Premium dengan Sidebar) --}}
    {{-- ========================================================================= --}}
    <section class="py-16 lg:py-24 relative bg-[#FDFBF7]">
        <div class="absolute inset-0 z-0">
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.015]">
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- Kolom Kiri: Deskripsi & muhsinin --}}
                <div class="lg:col-span-2" data-aos="fade-right">

                    <!-- Image Utama -->
                    <div class="relative rounded-2xl overflow-hidden shadow-xl mb-8">
                        <img src="{{ $this->donation->image_path ? asset('storage/' . $this->donation->image_path) : 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb8?auto=format&fit=crop&w=800&q=80' }}"
                            alt="{{ $this->donation->title }}" class="w-full h-auto object-cover max-h-[500px]">
                        <div
                            class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-black/50 to-transparent">
                        </div>
                    </div>

                    <!-- Tab Navigation Premium -->
                    <div class="flex relative mb-3">
                        <button @click="activeTab = 'deskripsi'"
                            :class="activeTab === 'deskripsi' ? 'text-slate-900 bg-slate-50 shadow-sm' :
                                'text-slate-400 hover:text-slate-600'"
                            class="flex-1 py-3 text-sm font-bold tracking-wide rounded-xl transition-all duration-300 relative z-10">
                            <x-heroicon-o-document-text class="w-4 h-4 inline mr-2" />
                            Deskripsi
                        </button>
                        <button @click="activeTab = 'muhsinin'"
                            :class="activeTab === 'muhsinin' ? 'text-slate-900 bg-slate-50 shadow-sm' :
                                'text-slate-400 hover:text-slate-600'"
                            class="flex-1 py-3 text-sm font-bold tracking-wide rounded-xl transition-all duration-300 relative z-10">
                            <x-heroicon-o-user-group class="w-4 h-4 inline mr-2" />
                            Muhsinin ({{ $this->donation->transactions_count }})
                        </button>
                    </div>

                    <!-- Konten -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 min-h-[300px]">

                        <!-- TAB 1: DESKRIPSI -->
                        <div x-show="activeTab === 'deskripsi'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0">
                            <div
                                class="prose prose-slate max-w-none prose-headings:font-heading prose-headings:text-[#1E293B] prose-p:text-slate-600 prose-p:leading-relaxed prose-strong:text-[#A31D1D]">
                                {!! $this->donation->description !!}
                            </div>
                        </div>

                        <!-- TAB 2: muhsinin -->
                        <div x-show="activeTab === 'muhsinin'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;">
                            <div class="space-y-4">
                                @forelse($this->contributors as $contributor)
                                    <div
                                        class="group flex gap-4 items-start p-3 rounded-2xl hover:bg-slate-50 transition-all duration-300">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-[#A31D1D] to-[#C0392B] flex items-center justify-center shrink-0 text-white text-xs font-bold shadow-md">
                                            {{ Str::upper(Str::substr($contributor->donor_name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-baseline gap-2">
                                                <h4 class="text-sm font-bold text-slate-800 truncate">
                                                    {{ $contributor->donor_name }}
                                                </h4>
                                                <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap">
                                                    {{ $contributor->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <x-heroicon-o-currency-dollar class="w-3.5 h-3.5 text-emerald-600" />
                                                <span class="text-xs font-bold text-emerald-600">
                                                    Rp {{ number_format($contributor->amount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            @if ($contributor->notes)
                                                <p
                                                    class="text-[12px] text-slate-500 mt-1.5 bg-slate-50/80 p-2.5 rounded-xl border border-slate-100/50 italic leading-relaxed">
                                                    "{{ $contributor->notes }}"
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 space-y-3">
                                        <div
                                            class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto">
                                            <x-heroicon-o-heart class="w-8 h-8 text-slate-300" />
                                        </div>
                                        <h5 class="text-sm font-bold text-slate-700">Belum Ada muhsinin</h5>
                                        <p class="text-xs text-slate-400 max-w-[200px] mx-auto leading-relaxed">
                                            Jadilah muhsinin pertama dan mulailah berbagi kebaikan
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>

                    <!-- Share Section -->
                    <div class="mt-10 pt-6 border-t border-slate-200">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-slate-500">Bagikan:</span>
                                <div class="flex gap-2">
                                    <button @click="openModal()"
                                        class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-[#A31D1D] hover:text-white transition-all cursor-pointer">
                                        <x-heroicon-o-share class="w-4 h-4" />
                                    </button>
                                    <a href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ urlencode(url()->current()) }}"
                                        target="_blank" rel="noopener"
                                        class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-[#A31D1D] hover:text-white transition-all">
                                        <x-heroicon-o-chat-bubble-left-right class="w-4 h-4" />
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                        target="_blank" rel="noopener"
                                        class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-[#A31D1D] hover:text-white transition-all">
                                        <x-heroicon-o-share class="w-4 h-4" />
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ urlencode(url()->current()) }}"
                                        target="_blank" rel="noopener"
                                        class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-[#A31D1D] hover:text-white transition-all">
                                        <x-heroicon-o-chat-bubble-left class="w-4 h-4" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Sidebar Premium --}}
                <div class="lg:col-span-1 space-y-8" data-aos="fade-left">

                    <!-- Info Donasi Card -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                        <h3 class="font-heading font-bold text-[#1E293B] mb-4 flex items-center gap-2">
                            <x-heroicon-o-currency-dollar class="w-5 h-5 text-[#A31D1D]" />
                            Info Donasi
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Terkumpul</span>
                                <span class="font-bold text-[#A31D1D]">Rp
                                    {{ number_format($this->donation->current_amount, 0, ',', '.') }}</span>
                            </div>
                            @if ($isOneTime && $this->donation->target_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Target</span>
                                    <span class="font-bold text-slate-700">Rp
                                        {{ number_format($this->donation->target_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Persentase</span>
                                    <span class="font-bold text-emerald-600">{{ $percentage }}%</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">muhsinin</span>
                                <span class="font-bold text-slate-700">{{ $this->donation->transactions_count }}
                                    Orang</span>
                            </div>
                            @if ($isOneTime && $this->donation->days_left !== null)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Sisa Waktu</span>
                                    <span class="font-bold text-slate-700">{{ (int) $this->donation->days_left }}
                                        Hari</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Sistem</span>
                                <span
                                    class="font-bold text-slate-700">{{ $isOneTime ? 'Donasi Proyek' : 'Berkelanjutan' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Program Lainnya -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                        <h3 class="font-heading font-bold text-[#1E293B] mb-4 flex items-center gap-2">
                            <x-heroicon-o-list-bullet class="w-5 h-5 text-[#A31D1D]" />
                            Program Lainnya
                        </h3>
                        <div class="space-y-4">
                            @php
                                $otherPrograms = App\Models\Donation::where('id', '!=', $this->donation->id)
                                    ->where('is_active', true)
                                    ->latest()
                                    ->take(3)
                                    ->get();
                            @endphp
                            @forelse($otherPrograms as $program)
                                <a href="{{ url('/donations/' . $program->slug) }}" wire:navigate
                                    class="flex gap-3 group items-center">
                                    <div
                                        class="w-16 h-16 rounded-xl overflow-hidden shrink-0 border border-slate-100 shadow-sm">
                                        <img src="{{ $program->image_path ? asset('storage/' . $program->image_path) : 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb8?auto=format&fit=crop&w=800&q=80' }}"
                                            alt="{{ $program->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] text-slate-400">
                                            {{ $program->created_at->diffForHumans() }}</p>
                                        <h4
                                            class="text-sm font-semibold text-[#1E293B] group-hover:text-[#A31D1D] transition line-clamp-2 leading-snug mt-0.5">
                                            {{ $program->title }}
                                        </h4>
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada program lainnya.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Quote Inspiratif -->
                    <div
                        class="bg-gradient-to-br from-[#1E293B] to-[#2D3A4F] rounded-2xl p-5 text-white border border-white/10">
                        <x-heroicon-o-chat-bubble-left class="w-8 h-8 text-[#D4AF37]/50 mb-3" />
                        <p class="text-sm italic leading-relaxed">"Sebaik-baik manusia adalah yang paling bermanfaat
                            bagi manusia lainnya."</p>
                        <p class="text-xs text-[#D4AF37] mt-3">— HR. Thabrani</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================================= --}}
    {{-- D. BOTTOM DRAWER SHARE MODAL (Premium Sheet) --}}
    {{-- ========================================================================= --}}
    <!-- resources/views/livewire/donation/partials/donation-modal.blade.php -->

    <x-ui.modal name="donation-modal" title="Cara Berdonasi">
        <div class="space-y-4">
            <!-- Header dengan icon -->
            <div class="text-center mb-2">
                <div class="w-16 h-16 bg-[#A31D1D]/10 rounded-full flex items-center justify-center mx-auto mb-3">
                    <x-heroicon-o-currency-dollar class="w-8 h-8 text-[#A31D1D]" />
                </div>
                <p class="text-sm text-slate-500">Salurkan donasi Anda untuk program <span
                        class="font-semibold text-slate-700">{{ $this->donation->title }}</span></p>
            </div>

            <!-- Metode 1: Transfer Bank -->
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#A31D1D]/10 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-building-library class="w-5 h-5 text-[#A31D1D]" />
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Transfer Bank</h4>

                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between items-center p-3 bg-white rounded-xl border border-slate-200">
                        <div>
                            <span class="text-slate-500 text-xs">Bank</span>
                            <p class="font-bold text-slate-800">Bank Syariah Indonesia (BSI)</p>
                        </div>
                        <button onclick="navigator.clipboard.writeText('1234567890'); alert('Nomor rekening disalin!')"
                            class="text-xs text-[#A31D1D] font-semibold hover:bg-[#A31D1D]/5 px-3 py-1 rounded-lg transition">
                            Salin
                        </button>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-white rounded-xl border border-slate-200">
                        <div>
                            <span class="text-slate-500 text-xs">Nomor Rekening</span>
                            <p class="font-bold text-slate-800 font-mono">1234567890</p>
                        </div>
                        <button onclick="navigator.clipboard.writeText('1234567890'); alert('Nomor rekening disalin!')"
                            class="text-xs text-[#A31D1D] font-semibold hover:bg-[#A31D1D]/5 px-3 py-1 rounded-lg transition">
                            Salin
                        </button>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-white rounded-xl border border-slate-200">
                        <div>
                            <span class="text-slate-500 text-xs">Atas Nama</span>
                            <p class="font-bold text-slate-800">Yayasan Pondok Pesantren</p>
                        </div>
                    </div>
                </div>

                <div class="mt-3 p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                    <div class="flex items-start gap-2">
                        <x-heroicon-o-information-circle class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" />
                        <p class="text-xs text-emerald-700 leading-relaxed">
                            <span class="font-semibold">Catatan:</span> Setelah transfer, kirim bukti transfer ke nomor
                            WhatsApp Admin untuk konfirmasi.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Metode 2: Langsung ke Sekretariat -->
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#A31D1D]/10 rounded-xl flex items-center justify-center">
                        <x-heroicon-o-map-pin class="w-5 h-5 text-[#A31D1D]" />
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Langsung ke Sekretariat</h4>
                        <p class="text-xs text-slate-500">Datang langsung ke lokasi kami</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="p-3 bg-white rounded-xl border border-slate-200">
                        <span class="text-slate-500 text-xs">Alamat</span>
                        <p class="font-medium text-slate-800 mt-0.5">
                            Jl. Pendidikan No. 123, Desa Santri, Kec. Iman, Kab. Berkah
                        </p>
                    </div>

                    <div class="p-3 bg-white rounded-xl border border-slate-200">
                        <span class="text-slate-500 text-xs">Jam Kerja</span>
                        <p class="font-medium text-slate-800 mt-0.5">
                            Senin - Jumat: 08.00 - 16.00 WIB
                        </p>
                    </div>
                </div>
            </div>

            <!-- Metode 3: Kontak Admin -->
            <div class="bg-gradient-to-r from-[#A31D1D]/5 to-[#C0392B]/5 rounded-2xl p-5 border border-[#A31D1D]/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#A31D1D] rounded-xl flex items-center justify-center">
                        <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 text-white" />
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-slate-800 text-sm">Butuh Bantuan?</h4>
                        <p class="text-xs text-slate-600">Hubungi admin kami untuk konsultasi</p>
                    </div>
                    <a href="https://wa.me/6281234567890?text=Assalamualaikum%20Admin%2C%20saya%20ingin%20berdonasi%20untuk%20program%20{{ urlencode($this->donation->title) }}"
                        target="_blank"
                        class="px-4 py-2 bg-emerald-500 text-white text-xs font-bold rounded-xl hover:bg-emerald-600 transition-all shadow-md hover:shadow-lg">
                        <x-heroicon-o-chat-bubble-left-right class="w-4 h-4 inline mr-1" />
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </x-ui.modal>

</div>

{{-- Tambahan CSS --}}
<style>
    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    .animate-shimmer {
        animation: shimmer 2s infinite;
    }

    [x-cloak] {
        display: none !important;
    }

    .modal-open {
        overflow: hidden;
    }
</style>
