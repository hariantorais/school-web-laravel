@props([
    'name' => 'modal-form',
    'title' => '',
    'maxWidth' => '2xl',
])

@php
    // Tambahkan opsi 'full' ke dalam pemetaan ukuran modal
    $maxWidthClass =
        [
            'sm' => 'max-w-sm',
            'md' => 'max-w-md',
            'lg' => 'max-w-lg',
            'xl' => 'max-w-xl',
            '2xl' => 'max-w-2xl',
            '3xl' => 'max-w-3xl',
            'full' => 'max-w-full w-screen h-screen', // Pengunci layar penuh
        ][$maxWidth] ?? 'max-w-2xl';

    // Atur height dan rounded secara kondisional khusus untuk varian full size
    $isFull = $maxWidth === 'full';
    $customWrapperClasses = $isFull ? 'h-screen max-h-screen rounded-none' : 'max-h-[85vh] rounded-2xl';
@endphp

<div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.name === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail.name === '{{ $name }}') show = false"
    x-on:program-created.window="show = false" x-show="show"
    class="fixed inset-0 z-50 flex items-center justify-center {{ $isFull ? 'p-0' : 'p-4 sm:p-6' }}"
    style="display: none;" x-on:keydown.escape.window="show = false">

    <div x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 backdrop-blur-none" x-transition:enter-end="opacity-100 backdrop-blur-sm"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 backdrop-blur-sm"
        x-transition:leave-end="opacity-0 backdrop-blur-none" class="absolute inset-0 bg-slate-950/40"
        @click="show = false">
    </div>

    <div x-show="show"
        x-transition:enter="transition ease-out duration-300 {{ $isFull ? '' : 'cubic-bezier(0.34, 1.56, 0.64, 1)' }}"
        x-transition:enter-start="opacity-0 {{ $isFull ? 'translate-y-full' : 'scale-95 translate-y-8 sm:translate-y-4' }}"
        x-transition:enter-end="opacity-100 {{ $isFull ? 'translate-y-0' : 'scale-100 translate-y-0' }}"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 {{ $isFull ? 'translate-y-0' : 'scale-100 translate-y-0' }}"
        x-transition:leave-end="opacity-0 {{ $isFull ? 'translate-y-full' : 'scale-95 translate-y-4' }}"
        class="relative w-full {{ $maxWidthClass }} {{ $customWrapperClasses }} bg-white shadow-2xl overflow-y-auto border border-slate-100/80 z-10 flex flex-col">

        @if ($title)
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between shrink-0">
                <h3 class="text-base font-bold text-slate-900 tracking-tight">{{ $title }}</h3>
            </div>
        @endif

        <button @click="show = false"
            class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 hover:bg-slate-50 p-1.5 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-slate-100 z-50">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="p-6 flex-1 overflow-y-auto">
            {{ $slot }}
        </div>
    </div>
</div>
