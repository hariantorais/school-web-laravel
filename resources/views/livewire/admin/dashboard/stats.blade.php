<?php

use Livewire\Volt\Component;
use App\Models\Visitor;
use App\Models\Post;
use App\Models\User;
use App\Models\Testimonial;
use Livewire\Attributes\On;

new class extends Component {
    public $totalVisitors;
    public $uniqueVisitors;
    public $todayVisitors;
    public $totalPosts;
    public $totalUsers;
    public $totalTestimonials;

    public function mount()
    {
        $this->loadStats();
    }

    public function placeholder()
    {
        return view('components.dashboard.stats-placeholder');
    }

    public function loadStats()
    {
        $this->totalVisitors = Visitor::count();
        $this->uniqueVisitors = Visitor::unique()->count();
        $this->todayVisitors = Visitor::today()->count();
        $this->totalPosts = Post::count();
        $this->totalUsers = User::count();
        $this->totalTestimonials = Testimonial::active()->count();
    }

    #[On('refresh-stats')]
    public function refreshStats()
    {
        $this->loadStats();
    }
};

?>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div
        class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Total Visitor</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalVisitors) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-3 flex items-center gap-2 flex-wrap">
            <span class="text-xs text-slate-400">Unique:</span>
            <span class="text-xs font-semibold text-slate-700">{{ number_format($uniqueVisitors) }}</span>
            <span class="text-xs text-slate-400">• Hari ini:</span>
            <span class="text-xs font-semibold text-slate-700">{{ number_format($todayVisitors) }}</span>
        </div>
    </div>

    <div
        class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Total Postingan</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalPosts) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            </div>
        </div>
    </div>

    <div
        class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Pengguna</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div
        class="bg-white rounded-2xl p-6 border border-slate-200/60 shadow-sm hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Testimoni Aktif</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalTestimonials) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
        </div>
    </div>
</div>
