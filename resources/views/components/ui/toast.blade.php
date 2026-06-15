<div class="fixed top-5 right-5 z-50 space-y-3 w-full max-w-sm"
     x-data="{ 
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
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2 md:translate-y-0 md:translate-x-4"
             x-transition:enter-end="opacity-100 transform translate-y-0 md:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="flex items-center justify-between p-4 rounded-xl shadow-lg border backdrop-blur-md transition-all duration-300"
             :class="{
                'bg-emerald-950/90 border-emerald-800 text-emerald-200': toast.type === 'success',
                'bg-rose-950/90 border-rose-800 text-rose-200': toast.type === 'error',
                'bg-amber-950/90 border-amber-800 text-amber-200': toast.type === 'warning',
                'bg-slate-900/95 border-slate-800 text-slate-200': toast.type === 'info'
             }">
            
            <div class="flex items-center gap-3 min-w-0">
                <template x-if="toast.type === 'success'">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-5 h-5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
                <template x-if="toast.type === 'warning'">
                    <svg class="w-5 h-5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </template>

                <p class="text-sm font-medium truncate" x-text="toast.message"></p>
            </div>

            <button @click="remove(toast.id)" 
                    class="ml-4 p-1 rounded-lg hover:bg-white/10 text-white/40 hover:text-white transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </template>
</div>