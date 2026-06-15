<?php

use Livewire\Volt\Component;
use App\Models\DonationLog;
use Illuminate\Support\Facades\DB;

new class extends Component {
    /**
     * Aksi Verifikasi Uang Donasi Masuk (Dana disetujui dan ditambahkan ke campaign terkait)
     */
    public function verifyDonation(int $id)
    {
        DB::transaction(function () use ($id) {
            $log = DonationLog::findOrFail($id);
            if ($log->status !== 'pending') {
                return;
            }

            // Update status log menjadi terverifikasi
            $log->update(['status' => 'verified']);

            // Suntik dana masuk ke saldo campaign induknya
            $log->campaign->increment('current_amount', $log->amount);
        });

        $this->dispatch('toast', type: 'success', message: 'Uang donasi berhasil diverifikasi dan masuk ke kas saldo!');
    }

    public function with(): array
    {
        return [
            'logs' => DonationLog::with('campaign')->latest()->get(),
        ];
    }
}; ?>

<div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-slate-100">
        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Audit Laporan & Mutasi Donasi Masuk</h3>
        <p class="text-xs text-slate-400 mt-0.5">Konfirmasi dan verifikasi setiap dana transfer masuk dari donatur umum
            atau wali murid.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs sm:text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                <tr>
                    <th class="p-4">Donatur</th>
                    <th class="p-4">Program Donasi</th>
                    <th class="p-4">Nominal</th>
                    <th class="p-4">Tujuan Bank</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 font-medium text-slate-900">
                            {{ $log->donor_name }}
                            <span
                                class="block text-[10px] text-slate-400 font-normal mt-0.5">{{ $log->donor_phone ?? 'No HP tidak dicantumkan' }}</span>
                        </td>
                        <td class="p-4 text-slate-500 max-w-xs truncate">{{ $log->campaign->title }}</td>
                        <td class="p-4 font-bold text-slate-800">Rp {{ number_format($log->amount, 0, ',', '.') }}</td>
                        <td class="p-4 text-xs font-medium text-slate-600 uppercase">{{ $log->bank_name }}</td>
                        <td class="p-4">
                            @if ($log->status === 'verified')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Sah
                                    / Masuk</span>
                            @elseif($log->status === 'pending')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100 animate-pulse">Checking</span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">Ditolak</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            @if ($log->status === 'pending')
                                <button wire:click="verifyDonation({{ $log->id }})"
                                    class="bg-teal-700 hover:bg-teal-800 text-white font-semibold text-xs px-3 py-1.5 rounded transition-colors shadow-sm">
                                    Approve & Cairkan
                                </button>
                            @else
                                <span class="text-xs text-slate-400 italic">Selesai diperiksa</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-400 text-xs">Belum ada riwayat transaksi
                            mutasi uang donasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
