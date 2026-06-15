@props([
    'modalName' => 'modal-form',
    'target' => 'save',
    'submitLabel' => 'Simpan Data',
    'loadingLabel' => 'Memproses...',
    'cancelLabel' => 'Batal',
])

{{-- AMAN: Menggunakan border-slate-300 untuk pembatas footer modal --}}
<div {!! $attributes->merge(['class' => 'pt-4 flex justify-end gap-2 shrink-0']) !!}>

    {{-- BUTTON BATAL: Memanfaatkan varian secondary baru --}}
    <x-ui.button type="button" variant="secondary" size="sm"
        @click="$dispatch('close-modal', { name: '{{ $modalName }}' })">
        {{ $cancelLabel }}
    </x-ui.button>

    {{-- BUTTON SUBMIT/SIMPAN: Memanfaatkan varian primary terintegrasi dengan Livewire Loading Target --}}
    <x-ui.button type="submit" variant="primary" size="sm" :target="$target">
        {{-- Teks normal yang akan hilang saat Livewire memproses simpan --}}
        <span wire:loading.remove wire:target="{{ $target }}">{{ $submitLabel }}</span>

        {{-- Teks pemrosesan yang otomatis muncul bergandengan dengan spinner --}}
        <span wire:loading wire:target="{{ $target }}">{{ $loadingLabel }}</span>
    </x-ui.button>

</div>
