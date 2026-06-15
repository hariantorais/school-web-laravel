<section id="program" class="py-12 sm:py-20 bg-gradient-to-br from-slate-50 to-slate-100 relative overflow-hidden">
    <!-- Ornamen Latar Belakang -->
    <div class="absolute inset-0 z-0 pointer-events-none opacity-40">
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#A31D1D]/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-[#D4AF37]/5 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Header Section (Lebih Ringkas) -->
        <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-16 space-y-3" data-aos="fade-up">
            <div
                class="inline-flex items-center gap-2 bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-[#A31D1D] animate-pulse"></span>
                <span class="text-[10px] sm:text-xs font-bold text-[#A31D1D] uppercase tracking-wider">
                    Pendidikan
                </span>
            </div>
            <h2 class="font-heading text-2xl sm:text-4xl font-extrabold text-[#1E293B] leading-tight">
                Program Kurikulum <span class="text-[#A31D1D]">& Ekskul</span>
            </h2>
            <p class="text-slate-500 text-xs sm:text-base max-w-xl mx-auto">
                Integrasi kurikulum syar'i otentik dan pengembangan bakat santri yang terukur.
            </p>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch" data-aos="fade-up">

            <!-- ========== KOLOM KIRI: MATERI DINIYYAH ========== -->
            <div
                class="lg:col-span-7 bg-white rounded-2xl p-4 sm:p-6 shadow-md hover:shadow-lg transition-all duration-300 flex flex-col h-full border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#A31D1D] to-[#D4AF37]"></div>

                <!-- Card Header -->
                <div class="flex items-center space-x-3 mb-5 pb-3 border-b border-slate-100">
                    <!-- shrink-0 mencegah ikon gepeng di HP -->
                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 shrink-0 bg-[#A31D1D]/10 rounded-xl flex items-center justify-center text-[#A31D1D]">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-xl font-bold text-[#1E293B]">Materi Diniyyah</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 font-medium mt-0.5">Ahlussunnah wal Jama'ah</p>
                    </div>
                </div>

                <!-- Grid Mapel Diniyyah -->
                <div x-data="{
                    mapelDiniyyah: [
                        { number: '01', name: 'Tahfidz Al-Qur\'an' }, { number: '02', name: 'Aqidah' },
                        { number: '03', name: 'Hadits' }, { number: '04', name: 'Tafsir' },
                        { number: '05', name: 'Bahasa Arab' }, { number: '06', name: 'Fiqih' },
                        { number: '07', name: 'Akhlak & Adab' }, { number: '08', name: 'Khot / Imla\'' },
                        { number: '09', name: 'Doa & Dzikir' }, { number: '10', name: 'Tajwid' },
                        { number: '11', name: 'Nahwu' }, { number: '12', name: 'Ushul Fikih' },
                        { number: '13', name: 'Ushul Tafsir' }, { number: '14', name: 'Siroh Islamiyah' }
                    ]
                }" class="grid grid-cols-2 lg:grid-cols-3 gap-1.5 sm:gap-2 flex-grow">
                    <template x-for="(item, index) in mapelDiniyyah" :key="index">
                        <div
                            class="p-1.5 sm:p-2 bg-slate-50 rounded-lg border border-slate-100 flex items-center space-x-1.5 hover:border-[#A31D1D]/30 hover:bg-white transition-all duration-200 cursor-pointer min-w-0">
                            <span
                                class="shrink-0 text-[#A31D1D] font-mono font-bold text-[10px] sm:text-xs bg-[#A31D1D]/5 w-5 h-5 flex items-center justify-center rounded">
                                <span x-text="item.number"></span>
                            </span>
                            <span class="text-slate-700 text-xs font-medium truncate" :title="item.name"
                                x-text="item.name"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ========== KOLOM KANAN: UMUM & EKSKUL ========== -->
            <div class="lg:col-span-5 flex flex-col gap-4 h-full">

                <!-- KURIKULUM UMUM -->
                <div
                    class="bg-[#1E293B] rounded-2xl p-4 sm:p-5 shadow-md border border-slate-800 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#D4AF37] to-[#A31D1D]"></div>

                    <div class="flex items-center space-x-3 mb-4">
                        <div
                            class="w-10 h-10 shrink-0 bg-[#D4AF37] rounded-xl flex items-center justify-center text-[#1E293B]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-white">Kurikulum Umum</h3>
                            <p class="text-[10px] sm:text-xs text-[#D4AF37] font-medium">Kesetaraan Nasional</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-white/5 p-2 rounded-lg border border-white/10 text-center">
                            <span class="text-white text-xs font-medium block truncate">Matematika</span>
                        </div>
                        <div class="bg-white/5 p-2 rounded-lg border border-white/10 text-center">
                            <span class="text-white text-xs font-medium block truncate">IPA</span>
                        </div>
                        <div class="bg-white/5 p-2 rounded-lg border border-white/10 text-center">
                            <span class="text-white text-xs font-medium block truncate">B. Indo</span>
                        </div>
                    </div>
                </div>

                <!-- EKSTRAKURIKULER -->
                <div
                    class="bg-white rounded-2xl p-4 sm:p-5 shadow-md flex flex-col flex-1 border border-slate-100 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-400"></div>

                    <div class="flex items-center space-x-3 mb-4">
                        <div
                            class="w-10 h-10 shrink-0 bg-emerald-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-[#1E293B]">Ekstrakurikuler</h3>
                            <p class="text-[10px] sm:text-xs text-slate-400 font-medium">Minat & Bakat Santri</p>
                        </div>
                    </div>

                    <!-- List Ekskul (Sangat Ringkas di Mobile) -->
                    <div x-data="{
                        ekskulList: [
                            'Tahfidz', 'Khitobah', 'Bulu Tangkis', 'Tenis Meja',
                            'Hifzhul Hadits', 'Bola Voli', 'Kaligrafi', 'Pidato 3 Basa',
                            'Renang', 'Futsal', 'Takraw', 'Basket', 'Panahan', 'Tata Boga'
                        ]
                    }" class="flex flex-wrap gap-1.5 content-start">
                        <template x-for="(item, index) in ekskulList" :key="index">
                            <div
                                class="px-2 py-1 rounded-md text-[11px] font-medium border border-slate-200 bg-slate-50 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 transition-all duration-150">
                                <span x-text="item"></span>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
