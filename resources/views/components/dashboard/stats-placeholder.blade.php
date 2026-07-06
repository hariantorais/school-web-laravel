<div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-pulse">
    @for ($i = 0; $i < 4; $i++)
        <div class="bg-white rounded-2xl p-6 border border-slate-200/60">
            <div class="flex items-center justify-between">
                <div class="space-y-2">
                    <div class="h-3 w-20 bg-slate-200 rounded"></div>
                    <div class="h-8 w-16 bg-slate-200 rounded"></div>
                </div>
                <div class="w-12 h-12 bg-slate-200 rounded-xl"></div>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <div class="h-3 w-12 bg-slate-200 rounded"></div>
                <div class="h-3 w-12 bg-slate-200 rounded"></div>
                <div class="h-3 w-8 bg-slate-200 rounded"></div>
                <div class="h-3 w-12 bg-slate-200 rounded"></div>
            </div>
        </div>
    @endfor
</div>
