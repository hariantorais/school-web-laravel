@props(['label', 'name', 'placeholder' => 'Mulai menulis konten di sini...', 'height' => 'min-h-[250px]'])

@php
    // Ganti titik menjadi underscore agar aman bagi ID DOM
    $safeId = str_replace('.', '_', $name);
@endphp

<div class="w-full space-y-1.5" wire:ignore x-data="{
    value: @entangle($name),

    clearForm() {
        const editor = this.$refs.trixEditor?.editor;
        if (editor) {
            editor.loadHTML('');
        }
    },

    initTrix() {
        // Jalankan di dalam nextTick agar modal selesai merender dirinya terlebih dahulu
        this.$nextTick(() => {
            const editor = this.$refs.trixEditor?.editor;
            if (!editor) return;

            // 1. Kondisi Awal jika data sudah ada sejak lahir (Mode Edit Post)
            if (this.value) {
                editor.loadHTML(this.value);
            }

            // 2. Pantau perubahan state Livewire secara dinamis (Aman untuk Reset Form)
            this.$watch('value', newValue => {
                if (newValue === '' || newValue === null || newValue === undefined) {
                    this.clearForm();
                    return;
                }

                if (newValue !== editor.getInputElement().value) {
                    editor.loadHTML(newValue);
                }
            });

            // 3. JALUR KHUSUS MODAL ASINKRON (Donasi): Dengarkan event khusus dengan penundaan mikro
            window.addEventListener('fill-trix-{{ $safeId }}', (e) => {
                this.$nextTick(() => {
                    const directEditor = this.$refs.trixEditor?.editor;
                    if (directEditor) {
                        directEditor.loadHTML(e.detail.content || '');
                        // Paksa sinkronisasi balik ke properti entangle
                        this.value = e.detail.content || '';
                    }
                });
            });
        });
    }
}" @trix-clear.window="clearForm()">

    @if ($label)
        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
            {{ $label }}
        </label>
    @endif

    <div
        class="bg-white border border-slate-200 rounded-xl overflow-hidden focus-within:ring-4 focus-within:ring-[var(--accent-focus)] focus-within:border-[var(--accent-primary)] transition-all">

        <input id="{{ $safeId }}" type="hidden" name="{{ $name }}" x-bind:value="value">

        <trix-editor x-ref="trixEditor" input="{{ $safeId }}" placeholder="{{ $placeholder }}"
            x-init="initTrix()" @trix-change="value = $event.target.value"
            class="prose max-w-none p-4 text-sm text-slate-700 placeholder-slate-400 focus:outline-hidden break-words {{ $height }}">
        </trix-editor>

    </div>

    @error($name)
        <span class="text-[10px] text-rose-600 font-bold mt-1 block animate-pulse">
            {{ $message }}
        </span>
    @enderror
</div>
