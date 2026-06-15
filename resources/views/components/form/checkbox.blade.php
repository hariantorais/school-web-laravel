@props(['label', 'name'])

<div>
    <div {!! $attributes->merge(['class' => 'flex items-center gap-2 pt-1']) !!}>
        <input type="checkbox" id="{{ $name }}" wire:model="{{ $name }}"
            class="h-4 w-4 accent-[var(--accent-primary)] border-slate-300 rounded-xl bg-white cursor-pointer focus:ring-2 focus:ring-[var(--accent-focus)]">

        <label for="{{ $name }}" class="text-xs text-slate-600 font-medium select-none cursor-pointer">
            {{ $label }}
        </label>
    </div>

    @error($name)
        <span class="text-[10px] text-rose-600 font-bold mt-1 block pl-6 animate-pulse">{{ $message }}</span>
    @enderror
</div>
