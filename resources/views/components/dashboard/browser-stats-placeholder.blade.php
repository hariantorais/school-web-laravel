<div class="bg-white rounded-2xl p-6 border border-slate-200/60 animate-pulse">
    <div class="h-4 w-24 bg-slate-200 rounded mb-4"></div>
    <div class="space-y-2">
        @for ($i = 0; $i < 4; $i++)
            <div class="flex items-center justify-between">
                <div class="h-4 w-20 bg-slate-200 rounded"></div>
                <div class="h-4 w-10 bg-slate-200 rounded"></div>
            </div>
        @endfor
    </div>
</div>
