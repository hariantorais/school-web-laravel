<div x-data="{
    show: false,
    loading: false,

    open() {
        this.show = true;
        this.loading = false;
    },
    close() {
        if (this.loading) return;
        this.show = false;
    },
    submit() {
        this.loading = true;
        document.getElementById('logout-form').submit();
    }
}" x-show="show" x-on:open-logout-modal.window="open()" x-on:keydown.escape.window="close()"
    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center" style="display: none;">

    {{-- BACKDROP BLUR --}}
    <div class="fixed inset-0 transform transition-all bg-slate-900/40 backdrop-blur-xs" @click="close()"></div>

    {{-- KOTAK DIALOG MODAL --}}
    <div
        class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-md z-50 p-6 space-y-6 animate-fade-in">

        <div class="flex items-start gap-4">
            {{-- CONTAINER IKON --}}
            <div
                class="shrink-0 w-10 h-10 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl flex items-center justify-center shadow-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            {{-- BLURB TEKS --}}
            <div class="space-y-1 flex-1">
                <h3 class="text-sm font-bold text-slate-800 tracking-wide">Konfirmasi Logout</h3>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">
                    Apakah Anda yakin ingin keluar dari sistem?
                </p>
            </div>
        </div>

        {{-- FOOTER AKSI --}}
        <div class="flex items-center justify-end gap-2">
            {{-- 1. TOMBOL BATAL --}}
            <button type="button" @click="close()" :disabled="loading"
                class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all duration-200">
                Batal
            </button>

            {{-- 2. TOMBOL KONFIRMASI LOGOUT --}}
            <button type="button" @click="submit()" :disabled="loading"
                class="px-6 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-2 min-w-[125px] justify-center">
                <svg x-show="loading" class="animate-spin h-4 w-4 text-white" style="display: none;" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span x-text="loading ? 'Memproses...' : 'Ya, Keluar'"></span>
            </button>
        </div>
    </div>
</div>
