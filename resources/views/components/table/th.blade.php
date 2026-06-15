@props(['align' => 'left'])

@php
    $aligns = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ];
    $classes = 'px-6 py-4  text-xs  ' . ($aligns[$align] ?? 'text-left');
@endphp

<th {!! $attributes->merge(['class' => $classes]) !!}>
    {{ $slot }}
</th>
