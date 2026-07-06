<footer
    class="bg-gradient-to-b from-[#1E293B] to-[#0F172A] text-slate-300 pt-20 pb-8 border-t border-[#D4AF37]/30 relative overflow-hidden">
    <div class="absolute inset-0 islamic-pattern opacity-[0.02] pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Grid Footer --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 pb-16 border-b border-slate-800 items-start">

            {{-- Kolom 1: Profile & Sosial Media --}}
            <div class="lg:col-span-4 space-y-4">
                <div>
                    <div class="flex items-center justify-start space-x-3">
                        @php
                            $logo = get_settings('school_logo');
                            $name = get_settings('school_name');
                            $tagline = get_settings('school_tagline');
                            $initial = $name ? substr($name, 0, 2) : 'PP';
                        @endphp

                        @if ($logo && !str_contains($logo, 'default-logo.png'))
                            <img src="{{ $logo }}" alt="{{ $name }}"
                                class="w-10 h-10 rounded-xl object-cover border border-[#D4AF37]/20 shadow-lg shrink-0 bg-white p-1">
                        @else
                            <div
                                class="w-10 h-10 rounded-xl bg-white border border-[#D4AF37]/20 shadow-lg shrink-0 flex items-center justify-center text-[#A31D1D] font-bold text-sm">
                                {{ $initial }}
                            </div>
                        @endif

                        <div>
                            <h5 class="font-heading text-sm sm:text-base font-bold text-white tracking-wide">
                                {{ $name }}
                            </h5>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed text-left mt-3">
                        {{ $tagline }}
                    </p>
                </div>

                {{-- Media Sosial --}}
                @php
                    $facebook = get_settings('facebook');
                    $instagram = get_settings('instagram');
                    $youtube = get_settings('youtube');
                    $socials = array_filter([
                        'facebook' => $facebook,
                        'instagram' => $instagram,
                        'youtube' => $youtube,
                    ]);
                @endphp

                @if (!empty($socials))
                    <div class="space-y-2 pt-2">
                        <span
                            class="text-[10px] text-slate-500 font-bold uppercase tracking-widest block text-left">Media
                            Sosial Resmi:</span>
                        <div class="flex justify-start space-x-3">
                            @if ($facebook)
                                <a href="{{ $facebook }}" target="_blank" aria-label="Facebook"
                                    class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-[#A31D1D] hover:text-white hover:border-[#A31D1D] transition-all duration-300 shadow-md">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path
                                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                                    </svg>
                                </a>
                            @endif
                            @if ($instagram)
                                <a href="{{ $instagram }}" target="_blank" aria-label="Instagram"
                                    class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-[#A31D1D] hover:text-white hover:border-[#A31D1D] transition-all duration-300 shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5">
                                        </rect>
                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                    </svg>
                                </a>
                            @endif
                            @if ($youtube)
                                <a href="{{ $youtube }}" target="_blank" aria-label="YouTube"
                                    class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-[#A31D1D] hover:text-white hover:border-[#A31D1D] transition-all duration-300 shadow-md">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path
                                            d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.498-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Kolom 2: Akses Halaman --}}
            <div class="lg:col-span-2 space-y-4">
                <div>
                    <h6 class="text-xs sm:text-sm font-bold uppercase tracking-widest text-white text-left">Akses
                        Halaman</h6>
                    <ul class="space-y-2 text-xs sm:text-sm font-medium text-slate-400 mt-3">
                        <li><a href="#kurikulum" class="hover:text-[#D4AF37] transition-all block text-left">•
                                Kurikulum</a></li>
                        <li><a href="#fasilitas" class="hover:text-[#D4AF37] transition-all block text-left">•
                                Fasilitas</a></li>
                        <li><a href="#program" class="hover:text-[#D4AF37] transition-all block text-left">• Ekskul</a>
                        </li>
                        <li><a href="#testimoni" class="hover:text-[#D4AF37] transition-all block text-left">•
                                Testimoni</a></li>
                    </ul>
                </div>
            </div>

            {{-- Kolom 3: Sekretariat --}}
            <div class="lg:col-span-3 space-y-4">
                <div>
                    <h6 class="text-xs sm:text-sm font-bold uppercase tracking-widest text-white text-left">Sekretariat
                    </h6>
                    <ul class="space-y-3 text-xs sm:text-sm text-slate-400 text-left mt-3">
                        @php
                            $address = get_settings('school_address');
                            $whatsapp = get_settings('whatsapp');
                            $whatsapp_2 = get_settings('whatsapp_2');
                            $email = get_settings('school_email');
                        @endphp

                        @if ($address)
                            <li class="flex items-start space-x-2.5">
                                <span class="text-[#D4AF37] shrink-0 mt-0.5">📍</span>
                                <span class="leading-relaxed">{{ $address }}</span>
                            </li>
                        @endif
                        @if ($whatsapp)
                            <li class="flex items-center space-x-2.5">
                                <span class="text-[#D4AF37] shrink-0">📞</span>
                                <a href="tel:{{ $whatsapp }}"
                                    class="hover:text-white transition-colors text-slate-300 font-semibold">{{ $whatsapp }}</a>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <span class="text-[#D4AF37] shrink-0">📞</span>
                                <a href="tel:{{ $whatsapp_2 }}"
                                    class="hover:text-white transition-colors text-slate-300 font-semibold">{{ $whatsapp_2 }}</a>
                            </li>
                        @endif
                        @if ($email)
                            <li class="flex items-center space-x-2.5">
                                <span class="text-[#D4AF37] shrink-0">✉️</span>
                                <a href="mailto:{{ $email }}"
                                    class="hover:text-white transition-colors text-slate-300 break-all">{{ $email }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Kolom 4: Google Maps --}}
            <div class="lg:col-span-3 space-y-3">
                <div
                    class="w-full h-36 rounded-xl overflow-hidden border border-white/10 shadow-lg bg-slate-800 relative group">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.9075731808193!2d116.85283337448256!3d-1.2242447355604316!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df1462f6b9648f9%3A0x6a6d7e4cdbdd430e!2sPondok%20Tahfiz%20Darul%20Huffadz!5e0!3m2!1sid!2sid!4v1781481745828!5m2!1sid!2sid"
                        class="w-full h-full border-0 opacity-85 hover:opacity-100 transition-opacity duration-300 absolute inset-0"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi Pondok Pesantren Daarul Huffadz Balikpapan">
                    </iframe>
                </div>
            </div>
        </div>

        {{-- Copyright --}}
        <div
            class="pt-8 flex flex-col md:flex-row justify-between items-center text-[11px] sm:text-xs text-slate-500 gap-4">
            <span class="w-full md:w-auto text-center">© {{ date('Y') }} {{ get_settings('school_name') }}</span>
            <div class="flex space-x-4 font-medium">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <span class="text-slate-800">|</span>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
