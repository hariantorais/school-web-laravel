 <section id="hero"
     class="relative w-full min-h-[720px] lg:min-h-[820px] flex items-center justify-start overflow-hidden">
     <!-- Background Gambar Penuh -->
     <div class="absolute inset-0 z-0">
         <img src="{{ asset('images/hero-image.svg') }}" alt="Gedung Pondok Pesantren Balikpapan"
             class="w-full h-full object-cover object-center scale-105 animate-[pulse_25s_ease-in-out_infinite]" />

         <!-- Overlay gradasi: Kiri GELAP untuk teks, Kanan CERAH agar gambar jelas -->
         <div class="absolute inset-0 bg-gradient-to-r from-[#1E293B]/95 via-[#1E293B]/60 to-transparent z-0"></div>

         <!-- Overlay tambahan dari bawah agar transisi lebih halus -->
         <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent z-0"></div>

         <div class="absolute inset-0 islamic-pattern mix-blend-overlay opacity-10 z-0"></div>
     </div>

     <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-16 lg:py-20">
         <!-- Area teks -->
         <div class="max-w-3xl lg:max-w-4xl" data-aos="fade-right" data-aos-duration="1000">

             <!-- Badge -->
             <div
                 class="inline-flex items-center space-x-2 bg-[#A31D1D]/80 backdrop-blur-md border border-[#D4AF37]/40 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold tracking-wider text-[#D4AF37] mb-8 shadow-lg">
                 <span>{{ get_settings('school_name') }}</span>
             </div>

             <!-- Judul Utama -->
             <h1
                 class="font-heading text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold text-white leading-tight drop-shadow-xl">
                 {{ get_settings('school_motto') }}
             </h1>

             <!-- Deskripsi -->
             <p class="text-slate-100 text-sm sm:text-base md:text-lg max-w-2xl leading-relaxed mt-8 drop-shadow-md">
                 {{ get_settings('school_tagline') }}
             </p>


         </div>
     </div>

 </section>
