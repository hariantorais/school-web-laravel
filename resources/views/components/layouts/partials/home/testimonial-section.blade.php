{{-- ========================================================================= --}}
{{-- TESTIMONI SECTION --}}
{{-- ========================================================================= --}}
@php
    use App\Models\Testimonial;
    $testimonials = Testimonial::active()->ordered()->limit(6)->get();
@endphp

@if ($testimonials->isNotEmpty())
    <section id="testimoni" class="py-12 sm:py-20 bg-gradient-to-br from-slate-50 to-slate-100 relative overflow-hidden">
        {{-- Ornamen Latar Belakang --}}
        <div class="absolute inset-0 z-0 pointer-events-none opacity-40">
            <div class="absolute top-0 right-0 w-72 h-72 bg-[#A31D1D]/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-[#D4AF37]/5 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Header Section --}}
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-16 space-y-3">
                <div class="flex items-center justify-center gap-3">
                    <span class="h-px w-8 bg-gradient-to-r from-transparent to-[#A31D1D]"></span>
                    <span
                        class="text-xs sm:text-sm font-bold text-[#A31D1D] uppercase tracking-widest bg-[#A31D1D]/10 backdrop-blur-sm px-4 py-2 rounded-full inline-block border border-[#A31D1D]/20">
                        Testimoni
                    </span>
                    <span class="h-px w-8 bg-gradient-to-l from-transparent to-[#A31D1D]"></span>
                </div>
                <h2 class="font-heading text-2xl sm:text-4xl font-extrabold text-[#1E293B] leading-tight">
                    Kata <span class="text-[#A31D1D]">Mereka</span>
                </h2>
                <p class="text-slate-500 text-xs sm:text-base max-w-xl mx-auto">
                    Pendapat dan pengalaman dari orang tua santri, alumni, dan masyarakat
                </p>
            </div>



            {{-- Grid Testimoni --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                @foreach ($testimonials as $testimonial)
                    <div
                        class="group bg-white rounded-2xl p-5 sm:p-6 shadow-md hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-[#A31D1D]/20 relative overflow-hidden">
                        {{-- Decorative Line --}}
                        <div
                            class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#A31D1D] to-[#D4AF37] opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>

                        {{-- Rating --}}
                        <div class="flex items-center gap-1 mb-3">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-slate-200' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>

                        {{-- Content --}}
                        <p
                            class="text-sm sm:text-base text-slate-600 leading-relaxed line-clamp-4 group-hover:line-clamp-none transition-all duration-300 mb-4">
                            "{{ $testimonial->content }}"
                        </p>

                        {{-- Author --}}
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                            <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}"
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover border-2 border-white shadow-sm">
                            <div>
                                <p class="font-bold text-sm sm:text-base text-[#1E293B]">{{ $testimonial->name }}</p>
                                @if ($testimonial->role)
                                    <p class="text-xs text-slate-400">{{ $testimonial->role }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endif
