@props(['label', 'name', 'placeholder' => '0'])

<div class="w-full" x-data="{
    displayValue: '',
    init() {
        let initialValue = $wire.get('{{ $name }}');
        this.displayValue = (initialValue !== undefined && initialValue !== null) ? initialValue.toString() : '';

        this.$watch('$wire.{{ $name }}', value => {
            if (value !== undefined && value !== null && value !== '') {
                this.displayValue = value.toString();
            } else {
                this.displayValue = '';
            }
        });
    }
}">

    <label for="{{ $name }}" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
        {{ $label }}
    </label>

    <div class="relative flex items-center rounded-xl shadow-xs w-full">
        <span
            class="absolute left-0 inset-y-0 px-3.5 flex items-center bg-slate-50 border-r border-slate-200 text-xs font-semibold text-slate-400 select-none rounded-l-xl">
            Rp
        </span>

        <input type="text" x-model="displayValue" x-mask:dynamic="$money($input, ',', '.', 0)"
            @input="
                let rawValue = displayValue.replaceAll('.', '');
                $wire.set('{{ $name }}', rawValue !== '' ? parseInt(rawValue) : 0);
            "
            placeholder="{{ $placeholder }}"
            class="w-full bg-white border border-slate-200 text-slate-900 rounded-xl pl-14 pr-4 py-2.5 text-xs font-medium focus:outline-none focus:border-[var(--accent-primary)] focus:ring-2 focus:ring-[var(--accent-focus)] transition-all duration-150" />
    </div>

    @error($name)
        <span class="text-[10px] text-rose-600 font-bold block mt-1 animate-pulse">
            {{ $message }}
        </span>
    @enderror
</div>
