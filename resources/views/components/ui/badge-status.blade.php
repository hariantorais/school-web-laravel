@props(['status'])

@php
    // Petakan status ke konfigurasi style, teks, dan animasi pulse
    $config = match ($status) {
        'success' => [
            'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'dot' => 'bg-emerald-500',
            'pulse' => false,
            'label' => 'Berhasil',
        ],
        'pending' => [
            'class' => 'bg-amber-50 text-amber-700 border-amber-200',
            'dot' => 'bg-amber-500',
            'pulse' => true,
            'label' => 'Pending',
        ],
        default => [
            'class' => 'bg-rose-50 text-rose-700 border-rose-200',
            'dot' => 'bg-rose-500',
            'pulse' => false,
            'label' => 'Gagal',
        ],
    };
@endphp

<span
    {{ $attributes->merge([
        'class' =>
            'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border shadow-xs ' .
            $config['class'],
    ]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }} {{ $config['pulse'] ? 'animate-pulse' : '' }}"></span>
    <span>{{ $config['label'] }}</span>
</span>
