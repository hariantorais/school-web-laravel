<?php

use Livewire\Volt\Component;
use App\Models\Visitor;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $deviceStats = [];

    public function mount()
    {
        $this->loadDeviceStats();
    }

    public function placeholder()
    {
        return view('components.dashboard.device-stats-placeholder');
    }

    public function loadDeviceStats()
    {
        $total = Visitor::count() ?: 1;
        $stats = [];

        $devices = Visitor::select('device', DB::raw('count(*) as total'))->groupBy('device')->get();

        foreach ($devices as $device) {
            $name = $device->device ?: 'Unknown';
            $stats[] = [
                'name' => $name,
                'total' => $device->total,
                'percentage' => round(($device->total / $total) * 100, 1),
            ];
        }

        $this->deviceStats = $stats;
    }

    #[On('refresh-stats')]
    public function refreshStats()
    {
        $this->loadDeviceStats();
    }
};

?>

<div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
    <h3 class="text-sm font-bold text-slate-700 mb-4">Perangkat</h3>
    <div class="space-y-3">
        @forelse($deviceStats as $device)
            <div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if ($device['name'] === 'desktop')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            @elseif($device['name'] === 'mobile')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            @elseif($device['name'] === 'tablet')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9" />
                            @endif
                        </svg>
                        {{ ucfirst($device['name']) }}
                    </span>
                    <span class="font-semibold text-slate-800">{{ $device['percentage'] }}%</span>
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full mt-1 overflow-hidden">
                    <div class="h-full bg-[var(--accent-primary)] rounded-full transition-all duration-500"
                        style="width: {{ $device['percentage'] }}%"></div>
                </div>
            </div>
        @empty
            <p class="text-slate-400 text-sm">Belum ada data</p>
        @endforelse
    </div>
</div>
