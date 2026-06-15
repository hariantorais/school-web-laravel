@props([
    'label',
    'name',
    'modelId' => null,
    'modelClass' => null,
    'placeholder' => 'Pilih berkas sampul',
    'hint' => 'JPG, PNG, WebP (Maks 2MB)',
])

@php
    $propertyName = str_replace('form.', '', $name);
    $hasOldImage = false;
    $oldImageUrl = null;

    if ($modelId && $modelClass && class_exists($modelClass)) {
        $modelInstance = $modelClass::find($modelId);

        if ($modelInstance) {
            // DETEKSI KOLOM DINAMIS: Mendukung featured_image (Post) & image_path (Donasi)
            $rawImagePath = $modelInstance->featured_image ?? ($modelInstance->image_path ?? null);

            if ($rawImagePath) {
                $hasOldImage = true;

                // Ambil dari accessor image_url jika tersedia, jika tidak gunakan Storage URL standar
                $oldImageUrl = $modelInstance->image_url ?? \Illuminate\Support\Facades\Storage::url($rawImagePath);
            }
        }
    }

    $livewireFile = $this->getPropertyValue($name);
@endphp

<div class="w-full">
    {{-- Label Komponen --}}
    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
        {{ $label }}
    </label>

    {{-- Kotak Area Bingkai Utama (p-0 agar gambar bisa menempel full ke tepi) --}}
    <div
        class="border border-dashed border-slate-300 bg-slate-50/50 rounded-xl text-center hover:border-[var(--accent-primary)] transition-colors relative group flex flex-col items-center justify-center min-h-[178px] overflow-hidden p-0">

        <div wire:loading wire:target="{{ $name }}"
            class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs rounded-xl flex flex-col items-center justify-center z-30 transition-all">
            <div class="flex flex-col items-center gap-2 text-white">
                <svg class="animate-spin h-6 w-6 text-[var(--accent-text)]" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="text-[10px] font-bold uppercase tracking-wider">Mengunggah Berkas...</span>
            </div>
        </div>

        @if ($livewireFile || ($hasOldImage && $oldImageUrl))

            {{-- KONTANER GAMBAR FULL (W-FULL H-FULL ABSOLUTE UNTUK MEMENUHI BINGKAI) --}}
            <div class="absolute inset-0 w-full h-full group/preview shadow-xs">

                @if ($livewireFile)
                    {{-- TAMPILAN GAMBAR BARU TEMPORER --}}
                    <img src="{{ $livewireFile->temporaryUrl() }}" class="w-full h-full object-cover">

                    {{-- Tombol Hapus Sementara (Pojok Kanan Atas) --}}
                    <button type="button" wire:click="$set('{{ $name }}', null)"
                        class="absolute top-3 right-3 bg-slate-900/70 hover:bg-rose-600 text-white rounded-full p-1.5 transition-colors shadow-xs focus:outline-hidden z-20 cursor-pointer">
                        <x-heroicon-o-trash class="w-3.5 h-3.5" />
                    </button>
                @else
                    {{-- TAMPILAN GAMBAR LAMA DATABASE --}}
                    <img src="{{ $oldImageUrl }}" class="w-full h-full object-cover">
                @endif

                {{-- Masking Gelap & Tombol Tindih Melayang di Tengah Gambar --}}
                <div
                    class="absolute inset-0 bg-slate-950/20 group-hover/preview:bg-slate-950/50 transition-colors flex flex-col items-center justify-center z-10">

                    <label
                        class="cursor-pointer inline-flex items-center px-3 py-2 bg-white backdrop-blur-md border border-slate-200 rounded-xl text-[11px] font-bold text-slate-800 shadow-md hover:bg-slate-50 transition-all transform scale-95 opacity-0 group-hover/preview:scale-100 group-hover/preview:opacity-100">
                        <x-heroicon-m-arrow-path class="w-3.5 h-3.5 mr-1.5 text-slate-500" />
                        <span>Ganti Berkas</span>
                        <input type="file" wire:model="{{ $name }}" class="sr-only" accept="image/*">
                    </label>

                </div>
            </div>
        @else
            {{-- KEADAAN DEFAULT: Kosong Tanpa Gambar Sama Sekali --}}
            <div class="p-4 flex flex-col items-center justify-center">
                <div class="py-2 text-slate-400 text-xs flex flex-col items-center justify-center">
                    <x-heroicon-o-photo
                        class="h-7 w-7 mb-1 text-slate-300 group-hover:text-[var(--accent-primary)] transition-colors" />
                    <span class="font-semibold text-slate-500 text-[11px]">{{ $placeholder }}</span>
                    <span class="text-[9px] text-slate-400 mt-0.5">{{ $hint }}</span>
                </div>

                <label
                    class="mt-3 cursor-pointer inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[11px] font-bold text-slate-700 shadow-xs hover:bg-slate-50 transition-colors">
                    <x-heroicon-m-plus class="w-3 h-3 mr-1 text-slate-400" />
                    <span>Pilih Berkas</span>
                    <input type="file" wire:model="{{ $name }}" class="sr-only" accept="image/*">
                </label>
            </div>
        @endif
    </div>

    {{-- Validasi Error Output --}}
    @error($name)
        <span class="text-[10px] text-rose-600 font-bold mt-1 block animate-pulse">{{ $message }}</span>
    @enderror
</div>
