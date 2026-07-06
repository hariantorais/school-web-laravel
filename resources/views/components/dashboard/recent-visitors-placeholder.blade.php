<div class="bg-white rounded-2xl p-6 border border-slate-200/60 animate-pulse">
    <div class="h-4 w-32 bg-slate-200 rounded mb-4"></div>
    <div class="space-y-3">
        @for ($i = 0; $i < 5; $i++)
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-slate-200 rounded"></div>
                    <div class="space-y-1">
                        <div class="h-3 w-32 bg-slate-200 rounded"></div>
                        <div class="h-2 w-24 bg-slate-200 rounded"></div>
                    </div>
                </div>
                <div class="h-3 w-12 bg-slate-200 rounded"></div>
            </div>
        @endfor
    </div>
</div>
