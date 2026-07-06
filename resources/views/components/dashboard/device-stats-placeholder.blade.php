<div class="bg-white rounded-2xl p-6 border border-slate-200/60 animate-pulse">
    <div class="h-4 w-32 bg-slate-200 rounded mb-4"></div>
    <div class="space-y-3">
        @for ($i = 0; $i < 3; $i++)
            <div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-slate-200 rounded"></div>
                        <div class="h-4 w-16 bg-slate-200 rounded"></div>
                    </div>
                    <div class="h-4 w-10 bg-slate-200 rounded"></div>
                </div>
                <div class="w-full h-2 bg-slate-200 rounded-full mt-1 overflow-hidden">
                    <div class="h-full bg-slate-300 rounded-full" style="width: {{ rand(30, 80) }}%"></div>
                </div>
            </div>
        @endfor
    </div>
</div>
