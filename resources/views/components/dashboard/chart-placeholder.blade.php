<div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/60 animate-pulse">
    <div class="h-4 w-48 bg-slate-200 rounded mb-4"></div>
    <div class="h-48 flex items-end gap-2">
        @for ($i = 0; $i < 7; $i++)
            <div class="flex-1 flex flex-col items-center gap-1">
                <div class="w-full flex flex-col items-center gap-0.5">
                    <div class="w-full flex justify-center gap-0.5">
                        @php
                            $h1 = rand(30, 140);
                            $h2 = rand(20, 120);
                        @endphp
                        <div class="w-1/2 bg-slate-200 rounded-t" style="height: {{ $h1 }}px"></div>
                        <div class="w-1/2 bg-slate-200 rounded-t" style="height: {{ $h2 }}px"></div>
                    </div>
                    <div class="h-3 w-8 bg-slate-200 rounded"></div>
                </div>
            </div>
        @endfor
    </div>
    <div class="flex items-center justify-center gap-6 mt-4">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded bg-slate-200"></div>
            <div class="h-3 w-8 bg-slate-200 rounded"></div>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded bg-slate-200"></div>
            <div class="h-3 w-10 bg-slate-200 rounded"></div>
        </div>
    </div>
</div>
