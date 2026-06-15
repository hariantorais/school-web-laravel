<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Services\DonationTransactionService;
use App\Models\Donation;
use Livewire\Attributes\{Computed, On, Url, Title};

new class extends Component {
    use WithPagination;

    // State Filter Utama
    public string $search = '';
    public string $filterStatus = '';

    /**
     * 🔥 SINKRONISASI URL: Mengunci pilihan program donasi langsung dari parameter URL browser.
     * Jika datang dari tombol "Lihat Riwayat" di kartu donasi, properti ini otomatis terisi ID donasi.
     */
    #[Title('Riwayat Donasi')]
    #[Url(keep: true)]
    public string $filterDonation = '';

    /**
     * Listener untuk memicu re-render tabel saat komponen form modal sukses menyimpan data
     */
    #[On('refresh-table')]
    public function refreshTable(): void
    {
        $this->resetPage();
    }

    // Reset ke halaman pertama (halaman 1) setiap kali pengguna mengubah filter pencarian
    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingFilterDonation(): void
    {
        $this->resetPage();
    }
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    /**
     * Mengosongkan seluruh filter kembali ke posisi semula
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'filterDonation', 'filterStatus']);
        $this->resetPage();
    }

    /**
     * Mengambil properti transaksi donasi terkomputasi lewat Service Layer
     */
    #[Computed]
    public function transactions()
    {
        $payloadFilters = [
            'search' => trim($this->search),
            'donation_id' => $this->filterDonation,
            'status' => $this->filterStatus,
        ];

        return app(DonationTransactionService::class)->getAllPaginated($payloadFilters, 10);
    }

    /**
     * Aksi Konfirmasi Admin menyetujui validasi dana masuk donatur umum
     */
    public function approve(int $id, DonationTransactionService $service)
    {
        try {
            $service->verifyPayment($id, 'success');
            $this->dispatch('toast', type: 'success', message: 'Dana donasi berhasil diverifikasi dan masuk saldo program!');
            $this->dispatch('refresh-table'); // Mengupdate progres bar di komponen lain jika diperlukan
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Gagal melakukan verifikasi: ' . $e->getMessage());
        }
    }
}; ?>

<div class="space-y-5 animate-fade-in">


    <x-slot name="subhead">Audit, pantau, dan verifikasi seluruh kontribusi dana dari
        donatur secara real-time.</x-slot>

    <x-slot name="headerAction">
        <x-ui.button type="button" x-on:click="$dispatch('open-modal', { name: 'modal-form' })"
            class="w-full sm:w-auto inline-flex items-center gap-2 justify-center py-2.5 text-xs font-bold cursor-pointer shadow-xs">
            <x-heroicon-m-plus class="w-4 h-4" />
            <span>Input Donasi Manual</span>
        </x-ui.button>
    </x-slot>

    {{-- ========================================================================= --}}
    {{-- BLOCK 2: BARIS FILTER MULTI-OPSI --}}
    {{-- ========================================================================= --}}
    <div class="space-y-3">
        <div
            class="bg-white p-4 border border-slate-200/60 rounded-2xl shadow-sm grid grid-cols-1 sm:grid-cols-3 gap-3.5 items-center">

            {{-- Cari Berbasis Input --}}
            <x-form.search placeholder="Cari donatur atau kode invoice..." name="search" />

            {{-- Opsi Filter Program Donasi --}}
            <div>
                <select wire:model.live="filterDonation"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-4 focus:ring-[var(--accent-focus)] focus:border-[var(--accent-primary)] focus:outline-hidden transition-all cursor-pointer">
                    <option value="">Semua Program Donasi</option>
                    @foreach (Donation::select('id', 'title')->latest()->get() as $donation)
                        <option value="{{ $donation->id }}">{{ Str::limit($donation->title, 45) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Opsi Filter Status Pembayaran --}}
            <div class="flex items-center gap-2.5">
                <select wire:model.live="filterStatus"
                    class="w-full flex-1 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-4 focus:ring-[var(--accent-focus)] focus:border-[var(--accent-primary)] focus:outline-hidden transition-all cursor-pointer">
                    <option value="">Semua Status Log</option>
                    <option value="pending">Menunggu Verifikasi</option>
                    <option value="success">Berhasil (Sukses)</option>
                    <option value="failed">Gagal / Dibatalkan</option>
                </select>

                {{-- Tombol Reset Tampil Jika Ada Filter Aktif --}}
                @if ($search || $filterDonation || $filterStatus)
                    <button type="button" wire:click="resetFilters"
                        class="px-3 py-2.5 text-xs font-bold text-slate-500 hover:text-rose-600 bg-slate-50 border border-slate-200 rounded-xl hover:bg-rose-50 transition-colors cursor-pointer shrink-0"
                        title="Bersihkan Semua Filter">
                        <x-heroicon-m-arrow-path class="w-4 h-4" />
                    </button>
                @endif
            </div>
        </div>

        {{-- NOTIFIKASI INDIKATOR JIKA FILTER URL AKTIF --}}
        @if ($filterDonation)
            <div
                class="flex items-center justify-between bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-xl text-xs text-slate-700 animate-fade-in shadow-inner">
                <div class="flex items-center gap-2 font-semibold text-slate-600">
                    <span class="w-2 h-2 bg-[var(--accent-primary)] rounded-full animate-pulse"></span>
                    <span>Menampilkan log riwayat transaksi khusus untuk program donasi terpilih.</span>
                </div>
                <button type="button" wire:click="$set('filterDonation', '')"
                    class="text-rose-600 hover:text-rose-700 font-extrabold transition-colors cursor-pointer text-sm">
                    Tampilkan Semua Transaksi &times;
                </button>
            </div>
        @endif
    </div>

    {{-- ========================================================================= --}}
    {{-- BLOCK 3: TABEL DATA RESPONSIF --}}
    {{-- ========================================================================= --}}
    <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr
                        class="border-b border-slate-200 bg-slate-50 text-slate-500 font-bold text-xs tracking-wider uppercase">
                        <th class="p-4 pl-6">Donatur & Invoice</th>
                        <th class="p-4 hidden md:table-cell">Target Program</th>
                        <th class="p-4 text-right">Nominal Kontribusi</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 hidden lg:table-cell">Metode</th>
                        <th class="p-4 pr-6 text-right">Verifikasi (Panitia)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($this->transactions as $tx)
                        <tr class="hover:bg-slate-50/40 transition-colors" wire:key="tx-row-{{ $tx->id }}">

                            {{-- Kolom 1: Profil Donatur --}}
                            <td class="p-4 pl-6">
                                <p class="font-extrabold text-sm text-slate-800 leading-tight">{{ $tx->donor_name }}</p>
                                <p class="text-[10px] text-slate-400 font-mono mt-1 tracking-wider font-semibold">
                                    {{ $tx->reference_code }}
                                </p>

                                {{-- Sub-metadata khusus tampilan Mobile/HP --}}
                                <div
                                    class="md:hidden flex flex-col gap-0.5 mt-2 text-[11px] text-slate-400 font-normal">
                                    <span class="text-slate-600 font-semibold truncate max-w-[220px]">
                                        📁 {{ $tx->donation->title }}
                                    </span>
                                    @if ($tx->status)
                                        <span class="font-bold text-slate-500">
                                            💳 Via {{ strtoupper(str_replace('_', ' ', $tx->status)) }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Kolom 2: Judul Donasi Induk (Desktop) --}}
                            <td class="p-4 hidden md:table-cell max-w-xs text-xs font-bold text-slate-500">
                                <p class="truncate leading-normal" title="{{ $tx->donation->title }}">
                                    {{ $tx->donation->title }}
                                </p>
                            </td>

                            {{-- Kolom 3: Jumlah Dana --}}
                            <td
                                class="p-4 text-right font-extrabold text-slate-900 whitespace-nowrap text-sm font-mono">
                                Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>

                            {{-- Kolom 4: Status Badge Dinamis --}}
                            <td class="p-4 text-center whitespace-nowrap">
                                <x-ui.badge-status :status="$tx->status" />
                            </td>

                            <td
                                class="p-4 hidden lg:table-cell text-xs font-bold text-slate-500 uppercase tracking-wide font-mono">
                                {{ $tx->payment_method ? strtoupper(str_replace('_', ' ', $tx->payment_method)) : 'MANUAL' }}
                            </td>

                            {{-- Kolom 6: Tombol Konfirmasi / Info Auditor --}}
                            <td class="p-4 pr-6 text-right whitespace-nowrap">
                                @if ($tx->status === 'pending')
                                    <x-ui.button type="button" variant="outline" size="sm"
                                        @click="$dispatch('open-modal', { name: 'modal-form', id: '{{ $tx->id }}' })"
                                        class="py-1 px-2.5 text-[11px] font-extrabold hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 transition-colors cursor-pointer">
                                        <x-heroicon-m-check-circle class="w-3.5 h-3.5 mr-1 text-emerald-600" />
                                        <span>Konfirmasi</span>
                                    </x-ui.button>
                                @else
                                    <div class="text-right space-y-0.5">
                                        <p class="text-[10px] text-slate-400 font-semibold font-mono">
                                            {{ $tx->updated_at ? $tx->updated_at->format('d/m/Y H:i') : '-' }}
                                        </p>
                                        <p class="text-[10px] text-teal-600 font-bold">
                                            Verified: <span class="underline">{{ $tx->user->name ?? 'System' }}</span>
                                        </p>
                                    </div>
                                @endif
                            </td>

                        </tr>
                    @empty
                        {{-- Tampilan Jka Data Transaksi Kosong --}}
                        <x-table.empty colspan="6" :search="$search"
                            blankMessage="Belum ada catatan log kontribusi dana masuk untuk program ini." />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginasi Kontrol --}}
    <x-table.pagination :paginator="$this->transactions" />

    <x-ui.modal name="modal-form" title="">
        <livewire:admin.donations.transaction-form />
    </x-ui.modal>

</div>
