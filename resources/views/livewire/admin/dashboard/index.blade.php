<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Institution;

new class extends Component {
    public $institution;

    public function mount()
    {
        $this->institution = Institution::first();
    }

    public function refreshStats()
    {
        $this->dispatch('refresh-stats');
        $this->dispatch('toast', type: 'success', message: 'Data diperbarui!');
    }
};

?>

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">Selamat datang di panel admin {{ $this->institution->name ?? '' }}</p>
        </div>
        <button wire:click="refreshStats"
            class="px-4 py-2 bg-[var(--accent-primary)] hover:bg-[var(--accent-hover)] text-white rounded-xl text-sm font-medium transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh Data
        </button>
    </div>

    {{-- Stat Cards --}}
    <livewire:admin.dashboard.stats lazy />

    {{-- Chart & Device Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <livewire:admin.dashboard.chart lazy />
        <livewire:admin.dashboard.device-stats lazy />
    </div>

    {{-- Browser & Recent Visitors --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <livewire:admin.dashboard.browser-stats lazy />
        <livewire:admin.dashboard.recent-visitors lazy />
    </div>

    {{-- Popular Posts --}}
    <livewire:admin.dashboard.popular-posts lazy />
</div>
