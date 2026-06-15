@props(['colspan', 'search' => '', 'blankMessage' => 'Belum ada data terdaftar di sistem.'])

<tr>
    {{-- Padding seimbang, animasi masuk halus, latar belakang putih bersih --}}
    <td colspan="{{ $colspan }}" class="px-6 py-16 text-center select-none animate-fade-in bg-white">
        <div class="flex flex-col items-center justify-center space-y-3">

            @if ($search)
                {{-- STATE 1: PENCARIAN GAGAL (Menggunakan variasi Slate-Teal dengan tekstur abu-abu tegas agar tetap netral) --}}
                <div
                    class="w-14 h-14 bg-slate-100 border border-slate-200 rounded-full flex items-center justify-center text-slate-500 shadow-xs">
                    <x-heroicon-o-magnifying-glass class="w-6 h-6" />
                </div>
            @else
                {{-- STATE 2: DATA MEMANG KOSONG (Menggunakan tema utama identitas sekolah: Aksen Teal murni) --}}
                <div
                    class="w-14 h-14 bg-[var(--accent-primary)]/10 border border-[var(--accent-primary)]/20 rounded-full flex items-center justify-center text-[var(--accent-primary)] shadow-xs">
                    <x-heroicon-o-inbox class="w-6 h-6" />
                </div>
            @endif

            {{-- BLOK BLURB TEKS INFORMASI --}}
            <div class="space-y-1">
                {{-- Judul: font-bold, text-slate-800 kontras tinggi untuk light mode --}}
                <h3 class="text-xs font-bold text-slate-800 tracking-wide">
                    {{ $search ? 'Pencarian Tidak Ditemukan' : 'Data Masih Kosong' }}
                </h3>

                {{-- Sub-deskripsi: text-slate-500 medium, bersih untuk dibaca --}}
                <p class="text-[11px] font-medium text-slate-500 max-w-xs leading-relaxed mx-auto">
                    @if ($search)
                        Tidak ada data yang cocok dengan kata kunci
                        <span
                            class="text-slate-900 font-mono font-black bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200 break-all inline-block">
                            "{{ $search }}"
                        </span>
                    @else
                        {{ $blankMessage }}
                    @endif
                </p>
            </div>

        </div>
    </td>
</tr>
