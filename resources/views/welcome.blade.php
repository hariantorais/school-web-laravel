<x-layouts.home>

    @include('components.layouts.partials.home.hero-section')

    <!-- ========== SISTEM PENDIDIKAN & KELULUSAN (TRANSISI ANTARA HERO DAN PROFIL) ========== -->
    <section id="sistem-pendidikan" class="relative -mt-20 md:-mt-28 z-20">
        <div class="absolute inset-0 z-0">
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')] opacity-[0.2]">
            </div>

        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8" data-aos="fade-up">

                <!-- Card 1: Jenjang Pendidikan (Gradasi Maroon SOLID) -->
                <x-ui.home.card icon="🏫" title="Jenjang Pendidikan" subtitle="Pendidikan Formal Berjenjang"
                    description="Pendidikan formal setara dengan standar nasional:" :gridItems="[
                        ['name' => 'SMP', 'label' => 'Sekolah Menengah Pertama', 'age' => 'Usia 11-13 tahun'],
                        ['name' => 'SMA', 'label' => 'Sekolah Menengah Atas', 'age' => 'Usia 14-17 tahun'],
                    ]" />

                <!-- Card 2: Sistem Kelulusan Ganda -->
                <x-ui.home.card bgGradient="from-[#1E293B] via-[#2D3A4F] to-[#3D1F2F]" accentColor="#D4AF37"
                    textColor="white" icon="🎓" title="Sistem Kelulusan Ganda"
                    subtitle="Jaminan Kompetensi & Legitimasi"
                    description="Lulusan Pondok Pesantren Tahfidz Daarul Huffadz berhak memperoleh:" contentType="list"
                    :listItems="[
                        [
                            'icon' => '📋',
                            'title' => 'Ijazah Negara Resmi',
                            'desc' => 'Setara SMP/MA, diakui untuk lanjut studi & bekerja',
                        ],
                        [
                            'icon' => '📜',
                            'title' => 'Ijazah Internal Pondok',
                            'desc' => 'Kompetensi keislaman & sanad hafalan Al-Qur\'an',
                        ],
                    ]" />
            </div>
        </div>
    </section>

    @include('components.layouts.partials.home.profile-section')



    <!-- ========== STATISTIK ========== -->
    @include('components.layouts.partials.home.statistic-section')

    @include('components.layouts.partials.home.program-section')

    @include('components.layouts.partials.home.tacher-section')

    @include('components.layouts.partials.home.facility-section')
    @include('components.layouts.partials.home.testimonial-section')



    <livewire:home.recent-posts />
</x-layouts.home>
