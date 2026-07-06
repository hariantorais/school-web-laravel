@props(['donation'])

@php
    $isOneTime = $donation->type === 'one_time';
    $percentage =
        $donation->target_amount > 0 ? min(($donation->current_amount / $donation->target_amount) * 100, 100) : 0;
@endphp

<div {{ $attributes->merge(['class' => 'bg-white border border-slate-200/60 rounded-2xl shadow-xl shadow-slate-100/30 overflow-hidden flex flex-col group hover:border-[var(--accent-primary)]/40 hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 relative']) }}
    wire:key="donation-card-{{ $donation->id }}">

    {{-- AREA BANNER DENGAN OVERLAY STATUS BADGE MELAYANG --}}
    <div class="w-full h-44 bg-slate-100 relative overflow-hidden border-b border-slate-100 flex-shrink-0">
        <img src="{{ $donation->imageUrl }}" alt="{{ $donation->title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

        {{-- Status Badge Melayang --}}
        <div class="absolute top-3.5 right-3.5 z-10 flex gap-1.5">
            {{-- Badge Petunjuk Sifat Program --}}
            <span
                class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-900/80 backdrop-blur-xs text-white shadow-xs">
                {{ $isOneTime ? 'Project' : 'Rutin' }}
            </span>

            @if ($donation->is_active)
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50/90 backdrop-blur-xs text-emerald-700 border border-emerald-200 shadow-xs">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Aktif
                </span>
            @else
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50/90 backdrop-blur-xs text-rose-700 border border-rose-200 shadow-xs">
                    Tutup
                </span>
            @endif
        </div>
    </div>

    {{-- KONTEN DETAIL CARD --}}
    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
        {{-- Judul Program & Kategori --}}
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                {{ $donation->category?->name ?? 'Kebaikan Umum' }}
            </span>
            <h3 class="font-bold text-slate-900 text-sm leading-tight group-hover:text-[var(--accent-primary)] transition-colors line-clamp-2"
                title="{{ $donation->title }}">
                {{ $donation->title }}
            </h3>
        </div>

        {{-- Progres Bar Dinamis & Akumulasi Dana --}}
        <div class="space-y-2 pt-1">
            {{-- Hanya tampilkan Progress Bar jika program bersifat Proyek (One Time) --}}
            @if ($isOneTime)
                <div
                    class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner border border-slate-200/30">
                    <div class="bg-[var(--accent-primary)] h-2 rounded-full transition-all duration-500"
                        style="width: {{ $percentage }}%"></div>
                </div>
            @endif

            <div class="flex items-center justify-between text-[11px] font-medium text-slate-400">
                <div class="flex flex-col">
                    <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">
                        {{ $isOneTime ? 'Terkumpul' : 'Dana Tersedia' }}
                    </span>
                    <span class="text-xs font-bold text-[var(--accent-primary)]">
                        Rp {{ number_format($donation->current_amount, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Sembunyikan Target Pendanaan jika program bersifat Berkelanjutan --}}
                @if ($isOneTime)
                    <div class="flex flex-col items-end">
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Target</span>
                        <span class="text-xs font-bold text-slate-700 font-mono">
                            Rp {{ number_format($donation->target_amount, 0, ',', '.') }}
                        </span>
                    </div>
                @else
                    <div class="flex flex-col items-end">
                        <span class="text-[9px] uppercase tracking-wider text-emerald-600 font-bold">Sifat</span>
                        <span class="text-[11px] font-bold text-emerald-700">Abadi / Kontinu</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- FOOTER CARD: AKSI AKUNTABILITAS DATA --}}
    <div class="px-5 py-3.5 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between gap-4">
        <div>
            @if ($isOneTime)
                <span
                    class="text-[11px] font-bold text-slate-500 bg-slate-100 border border-slate-200/60 px-2 py-0.5 rounded-md font-mono">
                    {{ round($percentage) }}%
                </span>
            @else
                <span
                    class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2 py-0.5 rounded-md uppercase tracking-wider">
                    Recurring
                </span>
            @endif
        </div>

        {{-- Komponen Menu Edit / Hapus Reusable --}}
        <div class="flex items-center">
            <a href="{{ route('admin.donations.transactions', ['filterDonation' => $donation->id]) }}" wire:navigate
                title="Lihat Log Transaksi Masuk"
                class="p-1.5 text-slate-500 hover:text-[var(--accent-primary)] hover:bg-slate-100 rounded-lg transition-colors border border-transparent hover:border-slate-200">
                <x-heroicon-m-document-text class="w-4 h-4" />
            </a>
            <x-table.actions :id="$donation->id" />
        </div>
    </div>

</div>
