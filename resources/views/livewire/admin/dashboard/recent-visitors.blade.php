    <?php
    
    use Livewire\Volt\Component;
    use App\Models\Visitor;
    use Livewire\Attributes\On;
    
    new class extends Component {
        public $recentVisitors = [];
    
        public function mount()
        {
            $this->loadRecentVisitors();
        }
    
        public function placeholder()
        {
            return view('components.dashboard.recent-visitors-placeholder');
        }
    
        public function loadRecentVisitors()
        {
            $this->recentVisitors = Visitor::latest()->limit(10)->get();
        }
    
        #[On('refresh-stats')]
        public function refreshStats()
        {
            $this->loadRecentVisitors();
        }
    };
    
    ?>

    <div class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm">
        <h3 class="text-sm font-bold text-slate-700 mb-4">Kunjungan Terakhir</h3>
        <div class="space-y-3 max-h-60 overflow-y-auto">
            @forelse($recentVisitors as $visitor)
                <div class="flex items-center justify-between text-sm border-b border-slate-100 pb-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if ($visitor->device === 'desktop')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            @endif
                        </svg>
                        <div>
                            <p class="text-slate-700 font-medium text-xs">{{ Str::limit($visitor->url ?? '-', 40) }}</p>
                            <p class="text-slate-400 text-[10px]">
                                {{ $visitor->browser }} • {{ $visitor->os }}
                                @if ($visitor->country)
                                    • {{ $visitor->country }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <span class="text-[10px] text-slate-400">
                        {{ $visitor->created_at->diffForHumans() }}
                    </span>
                </div>
            @empty
                <p class="text-slate-400 text-sm">Belum ada kunjungan</p>
            @endforelse
        </div>
    </div>
