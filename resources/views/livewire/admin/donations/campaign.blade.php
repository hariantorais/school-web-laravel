<?php

use Livewire\Volt\Component;
use App\Models\DonationCampaign;
use Illuminate\Support\Str;

new class extends Component {
    public string $title = '';
    public string $target_amount = '';
    public string $description = '';
    public string $end_date = '';

    public function saveCampaign()
    {
        $this->validate([
            'title' => 'required|min:5',
            'target_amount' => 'required|numeric|min:10000',
            'description' => 'required',
        ]);

        DonationCampaign::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . rand(100, 999),
            'target_amount' => $this->target_amount,
            'description' => $this->description,
            'end_date' => $this->end_date ?: null,
            'status' => 'active',
        ]);

        $this->reset(['title', 'target_amount', 'description', 'end_date']);

        // Panggil komponen reusable toast yang kita buat sebelumnya via dispatch
        $this->dispatch('toast', type: 'success', message: 'Program campaign donasi berhasil diluncurkan!');
    }

    public function with(): array
    {
        return [
            'campaigns' => DonationCampaign::latest()->get(),
        ];
    }
}; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 border border-slate-200 rounded-xl shadow-sm h-fit">
        <h3 class="text-base font-bold text-slate-900 mb-4">Buka Program Donasi</h3>

        <form wire:submit.prevent="saveCampaign" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Program Donasi</label>
                <input type="text" wire:model="title" placeholder="Contoh: Wakaf Pembangunan Masjid Sekolah"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-700 focus:outline-none transition-all">
                @error('title')
                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Target Dana (Rupiah)</label>
                <input type="number" wire:model="target_amount" placeholder="Contoh: 50000000"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-700 focus:outline-none transition-all">
                @error('target_amount')
                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Batas Waktu Akhir (Opsional)</label>
                <input type="date" wire:model="end_date"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-700 focus:outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Lengkap & Urgensi
                    Program</label>
                <textarea wire:model="description" rows="4" placeholder="Jelaskan tujuan penggalangan dana..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500/20 focus:border-teal-700 focus:outline-none transition-all"></textarea>
                @error('description')
                    <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-teal-700 hover:bg-teal-800 text-white text-sm font-semibold py-2.5 rounded-lg shadow-sm transition-colors">
                Publish Campaign Donasi
            </button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm lg:col-span-2 overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-900 text-sm sm:text-base">Daftar Penggalangan Dana Aktif</h3>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($campaigns as $cp)
                <div class="p-5 space-y-3.5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm sm:text-base">{{ $cp->title }}</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Batas Waktu:
                                {{ $cp->end_date ? \Carbon\Carbon::parse($cp->end_date)->format('d M Y') : 'Tanpa batas' }}
                            </p>
                        </div>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $cp->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                            {{ $cp->status === 'active' ? 'Berjalan' : 'Selesai' }}
                        </span>
                    </div>

                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-semibold">
                            <span class="text-teal-700">Rp {{ number_format($cp->current_amount, 0, ',', '.') }}
                                terkumpul</span>
                            <span class="text-slate-400">Target: Rp
                                {{ number_format($cp->target_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 shadow-inner overflow-hidden">
                            <div class="bg-teal-600 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $cp->percentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-right text-slate-400 font-medium">{{ $cp->percentage }}% Terpenuhi
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400 text-sm">Belum ada program donasi sekolah yang dibuka.</div>
            @endforelse
        </div>
    </div>
</div>
