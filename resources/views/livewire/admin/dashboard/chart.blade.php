<?php

use Livewire\Volt\Component;
use App\Models\Visitor;
use Livewire\Attributes\On;

new class extends Component {
    public $visitorChart;

    public function mount()
    {
        $this->loadChart();
    }

    public function placeholder()
    {
        return view('components.dashboard.chart-placeholder');
    }

    public function loadChart()
    {
        $labels = [];
        $visitors = [];
        $uniques = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');
            $visitors[] = Visitor::whereDate('created_at', $date)->count();
            $uniques[] = Visitor::whereDate('created_at', $date)->unique()->count();
        }

        $this->visitorChart = [
            'labels' => $labels,
            'visitors' => $visitors,
            'uniques' => $uniques,
        ];
    }

    #[On('refresh-stats')]
    public function refreshStats()
    {
        $this->loadChart();
    }
};

?>

<div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-slate-700">Kunjungan 7 Hari Terakhir</h3>
        <div class="flex items-center gap-4 text-xs">
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-0.5 bg-[#A31D1D]"></span>
                <span class="text-slate-500">Total</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-3 h-0.5 bg-[#D4AF37]"></span>
                <span class="text-slate-500">Unique</span>
            </div>
        </div>
    </div>

    {{-- Chart dengan CSS murni --}}
    <div class="h-56 relative">
        @php
            $maxValue = max(array_merge($visitorChart['visitors'], $visitorChart['uniques']));
            $maxValue = $maxValue > 0 ? $maxValue : 1;
            $chartHeight = 140;
        @endphp

        <div class="flex items-end justify-between h-full gap-1">
            @foreach ($visitorChart['labels'] as $index => $label)
                @php
                    $totalHeight = ($visitorChart['visitors'][$index] / $maxValue) * $chartHeight;
                    $uniqueHeight = ($visitorChart['uniques'][$index] / $maxValue) * $chartHeight;
                    $totalHeight = max($totalHeight, 2);
                    $uniqueHeight = max($uniqueHeight, 2);
                @endphp
                <div class="flex-1 flex flex-col items-center gap-1 h-full justify-end">
                    <div class="w-full flex justify-center gap-0.5 items-end h-[{{ $chartHeight }}px]">
                        <div class="w-1/2 bg-[#A31D1D] rounded-t transition-all duration-500 hover:opacity-80 min-h-[2px]"
                            style="height: {{ $totalHeight }}px">
                        </div>
                        <div class="w-1/2 bg-[#D4AF37] rounded-t transition-all duration-500 hover:opacity-80 min-h-[2px]"
                            style="height: {{ $uniqueHeight }}px">
                        </div>
                    </div>
                    <span class="text-[8px] text-slate-400 font-medium">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Nilai Maksimum --}}
    <div class="flex items-center justify-between mt-2 text-[10px] text-slate-400">
        <span>0</span>
        <span>Maks: {{ $maxValue }}</span>
    </div>
</div>
