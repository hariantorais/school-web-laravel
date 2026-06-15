<?php

use Livewire\Volt\Component;
use App\Livewire\Forms\DonationTransactionForm;
use App\Models\Donation;
use App\Models\DonationTransaction;
use Livewire\Attributes\{Computed, On};

new class extends Component {
    // Sediakan State Tunggal Form Object
    public DonationTransactionForm $form;

    /**
     * ACCESSOR/COMPUTED: Menyiapkan data donasi berformat JSON untuk Autocomplete
     */
    #[Computed]
    public function donationListJson(): string
    {
        return Donation::select('id', 'title', 'target_amount', 'current_amount')
            ->where('is_active', true)
            ->whereColumn('current_amount', '<', 'target_amount')
            ->latest()
            ->get()
            ->map(function ($donation) {
                $percentage = $donation->target_amount > 0 ? min(($donation->current_amount / $donation->target_amount) * 100, 100) : 0;

                return [
                    'id' => $donation->id,
                    'label' => $donation->title,
                    'sub' => 'Sisa: Rp ' . idr($donation->target_amount - $donation->current_amount) . ' (' . round($percentage) . '%)',
                ];
            })
            ->toJson();
    }

    /**
     * ACCESSOR/COMPUTED: Memantau program donasi yang sedang dipilih oleh Admin
     */
    #[Computed]
    public function selectedDonation()
    {
        if (!$this->form->donation_id) {
            return null;
        }

        $donation = Donation::find($this->form->donation_id);

        if (!$donation) {
            return null;
        }

        $target = $donation->target_amount;
        $current = $donation->current_amount;
        $remaining = $target - $current;
        $percentage = $target > 0 ? min(($current / $target) * 100, 100) : 0;

        return [
            'title' => $donation->title,
            'target' => $target,
            'current' => $current,
            'remaining' => $remaining,
            'percentage' => round($percentage),
        ];
    }

    /**
     * Menangkap Sinyal Pengisian Data (Aman dari Masalah Tabrakan Event)
     */
    #[On('open-modal')]
    public function loadData($id = null)
    {
        $this->form->clear();
        $this->resetErrorBag();

        if ($id) {
            $transaction = DonationTransaction::findOrFail($id);
            $this->form->setTransaction($transaction);
        }
    }

    /**
     * Menyimpan data transaksi manual yang diinput oleh admin/panitia
     */
    public function save(): void
    {
        $this->form->validate();

        try {
            $result = $this->form->store();

            // Tutup modal dan refresh tabel induk
            $this->dispatch('close-modal', name: 'modal-form');
            $this->dispatch('refresh-table');
            $this->dispatch('toast', type: $result['type'], message: $result['message']);
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: $e->getMessage());
        }
    }
}; ?>

<form wire:submit.prevent="save" class="p-5 space-y-4">

    {{-- 1. PILIHAN PROGRAM DONASI --}}
    <div class="space-y-3">
        <div>
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                Target Program Donasi *
            </label>

            <div
                @if ($this->form->id) class="pointer-events-none opacity-65 cursor-not-allowed select-none" @endif>
                <x-form.autocomplete name="form.donation_id" placeholder="Ketik nama program donasi untuk mencari..."
                    :list="$this->donationListJson" />
            </div>


        </div>

        {{-- MONITORING PANEL KETERAPAIAN KAS --}}
        @if ($this->selectedDonation)
            <div class="bg-slate-50 border border-slate-200/80 p-4 rounded-xl space-y-3 animate-fade-in">
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-0.5">
                        <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Program Terpilih</p>
                        <h4 class="text-xs font-bold text-slate-800 leading-tight">
                            {{ $this->selectedDonation['title'] }}
                        </h4>
                    </div>
                    <span
                        class="text-xs font-mono font-bold text-teal-600 bg-teal-50 border border-teal-100 px-2 py-0.5 rounded-md shrink-0">
                        {{ $this->selectedDonation['percentage'] }}% Ketercapaian
                    </span>
                </div>

                {{-- Progress Bar --}}
                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-teal-500 h-1.5 rounded-full transition-all duration-500"
                        style="width: {{ $this->selectedDonation['percentage'] }}%"></div>
                </div>

                {{-- Detail Finansial --}}
                <div class="grid grid-cols-3 gap-2 text-center pt-1 border-t border-slate-200/40">
                    <div class="text-left">
                        <span
                            class="text-[9px] uppercase tracking-wider text-slate-400 font-semibold block">Target</span>
                        <span class="text-xs font-bold text-slate-700 font-mono">Rp
                            {{ idr($this->selectedDonation['target']) }}</span>
                    </div>
                    <div>
                        <span
                            class="text-[9px] uppercase tracking-wider text-slate-400 font-semibold block">Terkumpul</span>
                        <span class="text-xs font-bold text-[var(--accent-primary)] font-mono">Rp
                            {{ idr($this->selectedDonation['current']) }}</span>
                    </div>
                    <div class="text-right">
                        <span
                            class="text-[9px] uppercase tracking-wider text-rose-400 font-bold block">Kekurangan</span>
                        <span class="text-xs font-bold text-rose-600 font-mono">Rp
                            {{ idr($this->selectedDonation['remaining']) }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- 2. GRID IDENTITAS DONATUR --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="sm:col-span-2">
            <x-form.input label="Nama Lengkap Donatur *" name="form.donor_name"
                placeholder="Contoh: Hamba Allah / Nama Wali" />
        </div>
        <div>
            {{-- 🔥 FIX 1: Tambahkan komponen input nomor telepon/handphone donatur --}}
            <x-form.input label="Nomor WhatsApp / HP" name="form.donor_phone" placeholder="Contoh: 0812xxxxxxxx" />
        </div>
    </div>

    {{-- GRID EMAIL & METODE --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-form.input type="email" label="Email (Opsional)" name="form.donor_email" placeholder="donatur@email.com" />
        <x-form.select label="Metode Penerimaan *" name="form.payment_method">
            {{-- SINKRONISASI: Disesuaikan dengan string value enum database Anda (manual / bank_transfer) --}}
            <option value="cash">Tunai</option>
            <option value="transfer_bank">Transfer Bank</option>

        </x-form.select>
    </div>

    {{-- 4. CATATAN / DOA --}}
    <x-form.textarea label="Catatan / Doa Donatur" name="form.notes" rows="3"
        placeholder="Tulis catatan titipan atau amanah donatur jika ada..." />

    {{-- 5. TOMBOL SUBMIT REUSABLE --}}
    <x-form.modal-submit-button />

</form>
