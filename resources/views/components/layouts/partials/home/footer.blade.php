 <footer
     class="bg-gradient-to-b from-[#1E293B] to-[#0F172A] text-slate-300 pt-20 pb-8 border-t border-[#D4AF37]/30 relative overflow-hidden">
     <div class="absolute inset-0 islamic-pattern opacity-[0.02] pointer-events-none"></div>
     <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

         <!-- Grid Footer -->
         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 pb-16 border-b border-slate-800 items-start">

             <!-- Kolom 1: Profile & Sosial Media (rata kiri di semua device) -->
             <div class="lg:col-span-4 space-y-4">
                 <div>
                     <div class="flex items-center justify-start space-x-3">
                         <div
                             class="w-10 h-10 bg-[#A31D1D] rounded-xl flex items-center justify-center p-2 text-[#D4AF37] border border-[#D4AF37]/20 shadow-lg shrink-0">
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                 stroke="currentColor" class="w-full h-full">
                                 <path stroke-linecap="round" stroke-linejoin="round"
                                     d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                             </svg>
                         </div>
                         <div>
                             <h5 class="font-heading text-sm sm:text-base font-bold text-white tracking-wide">Daarul
                                 Huffadz</h5>
                             <p class="text-[9px] text-[#D4AF37] font-semibold tracking-widest uppercase">Balikpapan
                             </p>
                         </div>
                     </div>
                     <p class="text-xs text-slate-400 leading-relaxed text-left mt-3">
                         {{ get_settings('school_tagline') }}
                     </p>
                 </div>

                 <div class="space-y-2 pt-2">
                     <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest block text-left">Media
                         Sosial Resmi:</span>
                     <div class="flex justify-start space-x-3">
                         <a href="#" target="_blank" aria-label="Facebook"
                             class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-[#A31D1D] hover:text-white hover:border-[#A31D1D] transition-all duration-300 shadow-md">
                             <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                 <path
                                     d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                             </svg>
                         </a>
                         <a href="#" target="_blank" aria-label="Instagram"
                             class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-[#A31D1D] hover:text-white hover:border-[#A31D1D] transition-all duration-300 shadow-md">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <rect x="2" y="2" width="20" height="20" rx="5" ry="5">
                                 </rect>
                                 <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                 <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                             </svg>
                         </a>
                         <a href="#" target="_blank" aria-label="YouTube"
                             class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-[#A31D1D] hover:text-white hover:border-[#A31D1D] transition-all duration-300 shadow-md">
                             <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                 <path
                                     d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.498-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                             </svg>
                         </a>
                     </div>
                 </div>
             </div>

             <!-- Kolom 2: Akses Halaman (rata kiri) -->
             <div class="lg:col-span-2 space-y-4">
                 <div>
                     <h6 class="text-xs sm:text-sm font-bold uppercase tracking-widest text-white text-left">Akses
                         Halaman</h6>
                     <ul class="space-y-2 text-xs sm:text-sm font-medium text-slate-400 mt-3">
                         <li><a href="#kurikulum" class="hover:text-[#D4AF37] transition-all block text-left">•
                                 Kurikulum</a></li>
                         <li><a href="#fasilitas" class="hover:text-[#D4AF37] transition-all block text-left">•
                                 Fasilitas</a></li>
                         <li><a href="#program" class="hover:text-[#D4AF37] transition-all block text-left">•
                                 Ekskul</a></li>
                         <li><a href="#testimoni" class="hover:text-[#D4AF37] transition-all block text-left">•
                                 Testimoni</a></li>
                     </ul>
                 </div>
             </div>

             <!-- Kolom 3: Sekretariat (rata kiri) -->
             <div class="lg:col-span-3 space-y-4">
                 <div>
                     <h6 class="text-xs sm:text-sm font-bold uppercase tracking-widest text-white text-left">
                         Sekretariat</h6>
                     <ul class="space-y-3 text-xs sm:text-sm text-slate-400 text-left mt-3">
                         <li class="flex items-start space-x-2.5">
                             <span class="text-[#D4AF37] shrink-0 mt-0.5">📍</span>
                             <span class="leading-relaxed">{{ get_settings('school_address') }}</span>
                         </li>
                         <li class="flex items-center space-x-2.5">
                             <span class="text-[#D4AF37] shrink-0">📞</span>
                             <a href="tel:081216140764"
                                 class="hover:text-white transition-colors text-slate-300 font-semibold">0812-1614-0764</a>
                         </li>
                         <li class="flex items-center space-x-2.5">
                             <span class="text-[#D4AF37] shrink-0">📞</span>
                             <a href="tel:088247957504"
                                 class="hover:text-white transition-colors text-slate-300 font-semibold">0882-4795-7504</a>
                         </li>
                         <li class="flex items-center space-x-2.5">
                             <span class="text-[#D4AF37] shrink-0">✉️</span>
                             <a href="mailto:info@daarulhuffadzbpn.sch.id"
                                 class="hover:text-white transition-colors text-slate-300 break-all">{{ get_settings('school_email') }}</a>
                         </li>
                     </ul>
                 </div>
             </div>

             <!-- Kolom 4: Google Maps (rata kiri) -->
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

         <!-- Copyright - Rata kiri di HP, tengah di desktop -->
         <div
             class="pt-8 flex flex-col md:flex-row justify-between items-center text-[11px] sm:text-xs text-slate-500 gap-4">
             <span class="w-full md:w-auto text-center">© 2026 Pondok Pesantren Tahfidz Daarul
                 Huffadz Balikpapan</span>
             <div class="flex space-x-4 font-medium">
                 <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                 <span class="text-slate-800">|</span>
                 <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
             </div>
         </div>
     </div>
 </footer>
