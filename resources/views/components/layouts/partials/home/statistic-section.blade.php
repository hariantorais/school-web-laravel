<section id="statistic" class="relative py-20 overflow-hidden">
    {{-- Background --}}
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-[#A31D1D] via-[#8B1A1A] to-[#1E293B]"></div>
        <div
            class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.08] mix-blend-overlay">
        </div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="40" height="40" viewBox="0 0 40 40"
            xmlns="http://www.w3.org/2000/svg"%3E%3Ccircle cx="20" cy="20" r="1" fill="%23D4AF37"
            fill-opacity="0.1"/%3E%3C/svg%3E')] bg-repeat">
        </div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80%] h-[80%] bg-[#D4AF37]/5 rounded-full blur-3xl">
        </div>
        <div
            class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#D4AF37]/30 to-transparent">
        </div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="counter-section">



        {{-- Grid Statistik --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-4">

            @php
                $stats = [
                    [
                        'key' => 'total_students',
                        'default' => 350,
                        'label' => 'Santri Aktif',
                        'desc' => 'Tahfidz & Ilmu Syar\'i',
                        'icon' =>
                            'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                        'delay' => 0,
                    ],
                    [
                        'key' => 'total_alumni',
                        'default' => 180,
                        'label' => 'Alumni Hafizh',
                        'desc' => 'Tersebar di berbagai PTN',
                        'icon' =>
                            'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
                        'delay' => 150,
                    ],
                    [
                        'key' => 'total_teachers',
                        'default' => 32,
                        'label' => 'Asatidzah Kompeten',
                        'desc' => 'Lulusan Ma\'had & LIPIA',
                        'icon' =>
                            'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                        'delay' => 300,
                    ],
                    [
                        'key' => 'established_year',
                        'default' => 2015,
                        'label' => 'Tahun Berdiri',
                        'desc' => function ($value) {
                            $hijri = $value - 579;
                            return $value . ' M / ' . $hijri . ' H';
                        },
                        'icon' =>
                            'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                        'delay' => 450,
                    ],
                ];
            @endphp

            @foreach ($stats as $index => $stat)
                @php
                    $value = get_settings($stat['key']) ?? $stat['default'];
                    $desc = is_callable($stat['desc']) ? $stat['desc']($value) : $stat['desc'];
                @endphp

                <div data-aos="zoom-in" data-aos-duration="800" data-aos-delay="{{ $stat['delay'] }}"
                    class="relative group bg-white/5 backdrop-blur-sm rounded-2xl p-6 text-center hover:bg-white/10 transition-all duration-500 border border-white/10 hover:border-[#D4AF37]/30">
                    <div
                        class="absolute inset-0 rounded-2xl bg-[#D4AF37]/0 group-hover:bg-[#D4AF37]/5 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-14 h-14 mx-auto mb-4 text-[#D4AF37] group-hover:scale-110 transition-transform duration-500">
                            <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="{{ $stat['icon'] }}" />
                            </svg>
                        </div>
                        <span class="counter font-heading text-4xl sm:text-5xl font-bold text-white drop-shadow-lg"
                            data-target="{{ $value }}">0</span>
                        <p class="text-[#D4AF37] text-xs sm:text-sm font-semibold tracking-wide uppercase mt-2">
                            {{ $stat['label'] }}</p>
                        <p class="text-white/50 text-[10px] mt-1">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
