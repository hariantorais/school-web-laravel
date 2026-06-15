<div class="fixed top-5 right-5 z-50 space-y-3 w-full max-w-sm" x-data="{
    toasts: [],
    add(message, type = 'success', duration = 4000) {
        const id = Date.now();
        this.toasts.push({ id, message, type });
        setTimeout(() => this.remove(id), duration);
    },
    remove(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    }
}"
    @toast.window="add($event.detail.message, $event.detail.type, $event.detail.duration)">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2 md:translate-y-0 md:translate-x-4"
            x-transition:enter-end="opacity-100 transform translate-y-0 md:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="flex items-start justify-between p-4 rounded-xl shadow-lg border backdrop-blur-md transition-all duration-300 w-full"
            :class="{
                'bg-emerald-950/90 border-emerald-800 text-emerald-200': toast.type === 'success',
                'bg-rose-950/90 border-rose-800 text-rose-200': toast.type === 'error',
                'bg-amber-950/90 border-amber-800 text-amber-200': toast.type === 'warning',
                'bg-slate-900/95 border-slate-800 text-slate-200': toast.type === 'info'
            }">

            <div class="flex items-start gap-3 min-w-0 flex-1">

                <template x-if="toast.type === 'success'">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" />
                </template>

                <template x-if="toast.type === 'error'">
                    <x-heroicon-o-exclamation-circle class="w-5 h-5 text-rose-400 flex-shrink-0 mt-0.5" />
                </template>

                <template x-if="toast.type === 'warning'">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" />
                </template>

                <template x-if="toast.type === 'info'">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" />
                </template>

                <p class="text-xs font-semibold break-words leading-relaxed text-left flex-1" x-text="toast.message">
                </p>
            </div>

            <button @click="remove(toast.id)"
                class="ml-4 p-1 rounded-lg hover:bg-white/10 text-white/40 hover:text-white transition-colors flex-shrink-0 mt-0.5">
                <x-heroicon-m-x-mark class="w-4 h-4" />
            </button>
        </div>
    </template>
</div>
