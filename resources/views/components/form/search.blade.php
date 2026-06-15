@props([
    'placeholder' => 'Cari data...',
    'name' => 'search',
])

<div class="relative w-full sm:max-w-xs">

    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
        <x-heroicon-o-magnifying-glass class="w-4 h-4" />
    </div>

    <input type="text" wire:model.live.debounce.500ms="{{ $name }}" placeholder="{{ $placeholder }}"
        {!! $attributes->merge([
            'class' =>
                'w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-900 font-medium placeholder-slate-400 focus:outline-none focus:border-[var(--accent-primary)] focus:ring-2 focus:ring-[var(--accent-focus)] transition-all shadow-xs',
        ]) !!}>

</div>
