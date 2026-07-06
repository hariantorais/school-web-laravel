<?php

use Livewire\Volt\Component;
use App\Models\Visitor;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $browserStats = [];

    public function mount()
    {
        $this->loadBrowserStats();
    }

    public function placeholder()
    {
        return view('components.dashboard.browser-stats-placeholder');
    }

    public function loadBrowserStats()
    {
        $total = Visitor::count() ?: 1;
        $stats = [];

        $browsers = Visitor::select('browser', DB::raw('count(*) as total'))->groupBy('browser')->get();

        foreach ($browsers as $browser) {
            $name = $browser->browser ?: 'Other';
            $stats[] = [
                'name' => $name,
                'total' => $browser->total,
                'percentage' => round(($browser->total / $total) * 100, 1),
            ];
        }

        $this->browserStats = $stats;
    }

    #[On('refresh-stats')]
    public function refreshStats()
    {
        $this->loadBrowserStats();
    }
};

?>

<div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
    <h3 class="text-sm font-bold text-slate-700 mb-4">Browser</h3>
    <div class="space-y-2">
        @forelse($browserStats as $browser)
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-600">{{ $browser['name'] }}</span>
                <span class="font-semibold text-slate-800">{{ $browser['percentage'] }}%</span>
            </div>
        @empty
            <p class="text-slate-400 text-sm">Belum ada data</p>
        @endforelse
    </div>
</div>
