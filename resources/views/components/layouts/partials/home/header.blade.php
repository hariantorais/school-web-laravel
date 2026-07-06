<header id="navbar" x-data="{ isScrolled: false, mobileMenuOpen: false }" x-init="window.addEventListener('scroll', () => { isScrolled = window.scrollY > 20 })"
    class="fixed top-0 left-0 right-0 z-40 transition-all duration-300"
    :class="isScrolled || mobileMenuOpen ?
        'bg-white shadow-lg border-b border-slate-200 h-20' :
        'bg-transparent h-24'">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
        <div class="flex items-center justify-between h-full">

            <!-- LOGO & BRANDING PONDOK PESANTREN -->
            <a href="/" wire:navigate class="flex items-center space-x-3 group">
                <div
                    class="w-11 h-11 bg-white rounded-xl flex items-center justify-center p-1 text-[#D4AF37] shadow-md group-hover:scale-105 transition-transform flex-shrink-0 border border-slate-100">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Daarul Huffadz"
                        class="w-full h-full object-contain">
                </div>
                <div class="min-w-0">
                    <!-- FIX: Dikunci permanen menggunakan warna emas bawaan Anda -->
                    <span
                        class="block text-[9px] sm:text-[10px] font-bold uppercase tracking-widest leading-none text-[#D4AF37]">
                        Pondok Pesantren
                    </span>
                    <!-- Nama Pondok: Berubah menjadi Hitam saat scroll, kembali ke Putih saat transparan -->
                    <h1 class="font-heading text-sm sm:text-base lg:text-lg font-bold leading-tight truncate transition-colors duration-300"
                        :class="isScrolled || mobileMenuOpen ? 'text-[#1E293B]' : 'text-white'">
                        Daarul Huffadz Balikpapan
                    </h1>
                </div>
            </a>

            <!-- NAVIGASI DESKTOP -->
            <nav class="hidden md:flex items-center space-y-0 space-x-5 lg:space-x-7 text-sm font-bold tracking-wide"
                :class="isScrolled ? 'text-slate-600' : 'text-slate-200 hover:text-white'">

                <!-- Beranda -->
                <a href="/" wire:navigate
                    class="pb-1 transition-colors hover:text-[#A31D1D] {{ request()->is('/') ? 'text-[#A31D1D] border-b-2 border-[#A31D1D]' : '' }}">
                    Beranda
                </a>

                <!-- Profil -->
                <a href="{{ route('home.profil') }}" class="transition-colors hover:text-[#A31D1D] pb-1">
                    Profil
                </a>




                <!-- Program -->
                <a href="{{ request()->is('/') ? '#program' : '/#program' }}"
                    class="transition-colors hover:text-[#A31D1D] pb-1">
                    Program
                </a>


                <!-- Fasilitas -->
                <a href="{{ request()->is('/') ? '#fasilitas' : '/#fasilitas' }}"
                    class="transition-colors hover:text-[#A31D1D] pb-1">
                    Fasilitas
                </a>

                <!-- Warta Pondok -->
                <a href="/posts" wire:navigate
                    class="pb-1 transition-colors hover:text-[#A31D1D] {{ request()->is('posts') || request()->is('post/*') ? 'text-[#A31D1D] border-b-2 border-[#A31D1D]' : '' }}">
                    Berita
                </a>
            </nav>

            <!-- CALL TO ACTION (CTA) HUBUNGI KAMI -->
            <div class="hidden md:block flex-shrink-0">
                <a href="https://wa.me/{{ get_settings('phone') }}" target="_blank" rel="noopener"
                    class="bg-[#A31D1D] hover:bg-[#851619] text-white text-xs font-bold px-5 py-3 rounded-xl transition-all shadow-md hover:shadow-lg tracking-wide hover:-translate-y-0.5 inline-block">
                    Hubungi Kami
                </a>
            </div>

            <!-- BUTTON HAMBURGER MENU MOBILE -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="focus:outline-none p-2 transition-colors"
                    :class="isScrolled || mobileMenuOpen ? 'text-slate-700' : 'text-white'">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- MOBILE DROPDOWN MENU -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-4"
        class="md:hidden bg-white border-b border-slate-200 absolute w-full left-0 top-20 shadow-xl"
        style="display: none;">
        <div class="px-4 pt-2 pb-6 space-y-1.5 flex flex-col">

            <a href="/" wire:navigate @click="mobileMenuOpen = false"
                class="px-3 py-2.5 text-base font-semibold rounded-lg hover:text-[#A31D1D] hover:bg-slate-50 {{ request()->is('/') ? 'text-[#A31D1D] bg-[#A31D1D]/5' : 'text-slate-600' }}">Beranda</a>

            <a href="{{ request()->is('/') ? '#profile' : '/#profile' }}" @click="mobileMenuOpen = false"
                class="px-3 py-2.5 text-base font-semibold text-slate-600 hover:text-[#A31D1D] hover:bg-slate-50 rounded-lg">Profil</a>

            <a href="{{ request()->is('/') ? '#program' : '/#program' }}" @click="mobileMenuOpen = false"
                class="px-3 py-2.5 text-base font-semibold text-slate-600 hover:text-[#A31D1D] hover:bg-slate-50 rounded-lg">Program</a>

            <a href="{{ request()->is('/') ? '#fasilitas' : '/#fasilitas' }}" @click="mobileMenuOpen = false"
                class="px-3 py-2.5 text-base font-semibold text-slate-600 hover:text-[#A31D1D] hover:bg-slate-50 rounded-lg">Fasilitas</a>



            <a href="/posts" wire:navigate @click="mobileMenuOpen = false"
                class="px-3 py-2.5 text-base font-semibold rounded-lg hover:text-[#A31D1D] hover:bg-slate-50 {{ request()->is('posts') || request()->is('post/*') ? 'text-[#A31D1D] bg-[#A31D1D]/5' : 'text-slate-600' }}">Warta
                Pondok</a>

            <a href="https://wa.me/{{ get_settings('phone') }}" target="_blank" rel="noopener"
                class="mt-4 block w-full text-center bg-[#A31D1D] hover:bg-[#851619] text-white font-bold px-6 py-3 rounded-xl shadow-md transition-colors">
                Hubungi Kami
            </a>
        </div>
    </div>
</header>
