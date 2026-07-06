@props(['label', 'name'])

<div class="w-full">
    {{-- Container utama menggunakan group untuk mendeteksi interaksi baris --}}
    <div {!! $attributes->merge([
        'class' =>
            'flex items-center justify-between gap-4 p-3 rounded-xl bg-white border border-slate-200/60 shadow-xs hover:border-slate-300 transition-all',
    ]) !!}>

        {{-- Sisi Kiri: Teks Informasi Label --}}
        <label for="{{ $name }}" class="flex flex-col flex-1 cursor-pointer select-none">
            <span class="text-xs font-bold text-slate-700 group-hover:text-slate-900 transition-colors">
                {{ $label }}
            </span>
        </label>

        {{-- Sisi Kanan: Struktur Sakelar Toggle Premium (Murni CSS Tailwind) --}}
        <div class="relative flex-shrink-0">
            {{-- Input asli disembunyikan secara visual tetapi tetap aktif untuk Livewire & Aksesibilitas --}}
            <input type="checkbox" id="{{ $name }}" wire:model.live="{{ $name }}"
                class="sr-only peer cursor-pointer">

            {{-- Lintasan Jalur Sakelar (Latar Belakang) --}}
            <div @click="document.getElementById('{{ $name }}').click()"
                class="w-9 h-5 bg-slate-200 peer-focus:outline-hidden peer-focus:ring-2 peer-focus:ring-[#10A8E5]/20 rounded-full transition-all duration-300 peer-checked:bg-[#10A8E5] cursor-pointer">
            </div>

            {{-- Tombol Bulat Penggeser Aktif --}}
            <div @click="document.getElementById('{{ $name }}').click()"
                class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full border border-slate-200 shadow-xs transition-all duration-300 transform peer-checked:translate-x-4 cursor-pointer">
            </div>
        </div>

    </div>

    {{-- Render Pesan Validasi Error --}}
    @error($name)
        <span class="text-[10px] text-rose-600 font-bold mt-1 block pl-2 animate-pulse">
            {{ $message }}
        </span>
    @enderror
</div>
