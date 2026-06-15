@props(['label', 'name', 'placeholder' => '', 'rows' => 4])

<div>
    <label for="{{ $name }}" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
        {{ $label }}
    </label>

    <textarea id="{{ $name }}" wire:model="{{ $name }}" rows="{{ $rows }}"
        placeholder="{{ $placeholder }}" {!! $attributes->merge([
            'class' =>
                'w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-900 font-medium placeholder:text-slate-400 focus:outline-none focus:border-[var(--accent-primary)] focus:ring-2 focus:ring-[var(--accent-focus)] shadow-xs resize-y min-h-[80px] transition-all duration-150',
        ]) !!}></textarea>

    @error($name)
        <span class="text-[10px] text-rose-600 font-bold mt-1 block animate-pulse">{{ $message }}</span>
    @enderror
</div>
