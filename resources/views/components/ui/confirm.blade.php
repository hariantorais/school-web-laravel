@props([
    'name' => 'delete-confirm',
    'title' => 'Konfirmasi Hapus Data',
    'message' =>
        'Apakah Anda yakin ingin melanjutkan tindakan ini? Data yang dihapus akan hilang secara permanen dan tidak dapat dikembalikan.',
    'confirmLabel' => 'Ya, Hapus Data',
    'cancelLabel' => 'Batal',
])

<div x-data="{
    show: false,
    loading: false, // State dikendalikan Alpine untuk kecepatan UI
    wireAction: 'delete',
    wireId: null,

    open(e) {
        if (e.detail.name === '{{ $name }}') {
            this.show = true;
            this.loading = false;
            this.wireAction = e.detail.action || 'delete';
            this.wireId = e.detail.id || null;
        }
    },
    close() {
        if (this.loading) return; // Kunci modal jika sedang menghapus
        this.show = false;
        this.wireAction = 'delete';
        this.wireId = null;
    },
    submit() {
        this.loading = true;

        let callPromise;
        if (this.wireId) {
            callPromise = this.$wire.call(this.wireAction, this.wireId);
        } else {
            callPromise = this.$wire.call(this.wireAction);
        }

        // Jalankan transisi tutup setelah database selesai memotong data
        callPromise.then(() => {
            this.loading = false;
            this.show = false;
        }).catch(() => {
            this.loading = false;
        });
    }
}" x-show="show" x-on:open-confirm.window="open($event)" x-on:close-confirm.window="close()"
    x-on:keydown.escape.window="close()"
    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center" style="display: none;">

    {{-- BACKDROP BLUR --}}
    <div class="fixed inset-0 transform transition-all bg-slate-900/40 backdrop-blur-xs" x-on:click="close()"></div>

    {{-- KOTAK DIALOG MODAL --}}
    <div
        class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-md z-50 p-6 space-y-6 animate-fade-in">

        <div class="flex items-start gap-4">
            {{-- CONTAINER IKON --}}
            <div
                class="shrink-0 w-10 h-10 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl flex items-center justify-center shadow-xs">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
            </div>

            {{-- BLURB TEKS --}}
            <div class="space-y-1 flex-1">
                <h3 class="text-sm font-bold text-slate-800 tracking-wide">
                    {{ $title }}
                </h3>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">
                    {{ $message }}
                </p>
            </div>
        </div>

        {{-- FOOTER AKSI: Menggunakan x-ui.button reusable Anda --}}
        <div class="flex items-center justify-end gap-2 ">

            {{-- 1. TOMBOL BATAL --}}
            <x-ui.button type="button" variant="secondary" size="sm" x-on:click="close()"
                x-bind:disabled="loading">
                {{ $cancelLabel }}
            </x-ui.button>

            {{-- 2. TOMBOL KONFIRMASI HAPUS --}}
            <x-ui.button type="button" variant="danger" size="sm" x-on:click="submit()"
                x-bind:disabled="loading" class="min-w-[125px]">
                {{-- Menyisipkan Ikon Spinner Heroicon yang sinkron dengan state loading Alpine --}}
                <x-heroicon-o-arrow-path x-show="loading" class="animate-spin h-3.5 w-3.5 text-white"
                    style="display: none;" />

                {{-- Teks dinamis agar berubah jadi 'Memproses...' saat di-klik --}}
                <span x-text="loading ? 'Memproses...' : '{{ $confirmLabel }}'"></span>
            </x-ui.button>

        </div>
    </div>
</div>
