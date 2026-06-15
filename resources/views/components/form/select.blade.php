@props(['label', 'name', 'disabled' => false])

<div>
    <label for="{{ $name }}" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
        {{ $label }}
    </label>

    <div class="relative rounded-xl shadow-xs">
        <select id="{{ $name }}" wire:model="{{ $name }}" @disabled($disabled)
            {{ $attributes->merge([
                'class' =>
                    'w-full bg-white border border-slate-200 text-slate-900 rounded-xl px-3 py-2.5 pr-10 text-xs font-medium focus:outline-none focus:border-[var(--accent-primary)] focus:ring-2 focus:ring-[var(--accent-focus)] appearance-none cursor-pointer transition-all ' .
                    ($disabled ? 'bg-slate-50 text-slate-400 border-slate-200 cursor-not-allowed opacity-75' : ''),
            ]) }}>
            {{ $slot }}
        </select>

        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
            <x-heroicon-m-chevron-down class="w-4 h-4" />
        </div>
    </div>

    @error($name)
        <span class="text-[10px] text-rose-600 font-bold block mt-1 animate-pulse">{{ $message }}</span>
    @enderror
</div>
