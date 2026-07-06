<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Testimonial;
use Livewire\Attributes\{Computed, On, Title};

new class extends Component {
    use WithPagination;

    #[Title('Testimoni')]
    public string $search = '';
    public string $filterStatus = '';
    public string $filterRating = '';

    #[On('refresh-table')]
    public function refreshTable(): void {}

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }
    public function updatingFilterRating(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterStatus', 'filterRating']);
        $this->resetPage();
        $this->dispatch('filters-reseted');
    }

    #[Computed]
    public function testimonials()
    {
        return Testimonial::query()
            ->when(trim($this->search), function ($query) {
                $query->where('name', 'like', '%' . trim($this->search) . '%')->orWhere('role', 'like', '%' . trim($this->search) . '%');
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus === 'active');
            })
            ->when($this->filterRating !== '', function ($query) {
                $query->where('rating', $this->filterRating);
            })
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(9);
    }

    public function delete(int $id)
    {
        try {
            $testimonial = Testimonial::findOrFail($id);
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $testimonial->delete();
            $this->dispatch('toast', type: 'success', message: 'Testimoni berhasil dihapus!');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function toggleStatus(int $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update(['is_active' => !$testimonial->is_active]);
        $this->dispatch('toast', type: 'success', message: 'Status testimoni berhasil diubah!');
    }
};

?>

<div class="space-y-6">

    {{-- FILTER & SEARCH --}}
    <div class="bg-white p-4 border border-slate-200/60 rounded-2xl shadow-xl shadow-slate-100/40" x-data="{
        localSearch: @entangle('search'),
        localStatus: @entangle('filterStatus'),
        localRating: @entangle('filterRating'),
        isTyping: false
    }"
        @filters-reseted.window="localSearch = ''; localStatus = ''; localRating = ''; isTyping = false">

        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">

            <div class="grid grid-cols-1 sm:grid-cols-3 flex-1 gap-3">

                {{-- Search --}}
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <template x-if="isTyping">
                            <svg class="animate-spin h-4 w-4 text-[var(--accent-primary)]" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </template>
                        <template x-if="!isTyping">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </template>
                    </span>
                    <input type="text" x-model="localSearch"
                        @input="isTyping = true; setTimeout(() => { isTyping = false }, 400)"
                        wire:model.live.debounce.400ms="search" placeholder="Cari nama atau role..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-4 focus:ring-[var(--accent-focus)] focus:border-[var(--accent-primary)] focus:outline-hidden transition-all placeholder-slate-400 shadow-inner" />
                </div>

                {{-- Filter Status --}}
                <div>
                    <select x-model="localStatus" wire:model.live="filterStatus"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-4 focus:ring-[var(--accent-focus)] focus:border-[var(--accent-primary)] focus:outline-hidden transition-all cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>

                {{-- Filter Rating --}}
                <div>
                    <select x-model="localRating" wire:model.live="filterRating"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:ring-4 focus:ring-[var(--accent-focus)] focus:border-[var(--accent-primary)] focus:outline-hidden transition-all cursor-pointer">
                        <option value="">Semua Rating</option>
                        <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                        <option value="4">⭐⭐⭐⭐ (4)</option>
                        <option value="3">⭐⭐⭐ (3)</option>
                        <option value="2">⭐⭐ (2)</option>
                        <option value="1">⭐ (1)</option>
                    </select>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-2.5 justify-end">
                <template x-if="localSearch || localStatus !== '' || localRating !== ''">
                    <button type="button" wire:click="resetFilters"
                        class="inline-flex items-center gap-1.5 px-3 py-2.5 text-xs font-bold text-slate-500 hover:text-rose-600 bg-slate-50 border border-slate-200 rounded-xl hover:bg-rose-50 transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </button>
                </template>

                <button type="button" @click="$dispatch('open-modal', { name: 'modal-testimonial' })"
                    class="inline-flex items-center gap-2 justify-center px-4 py-2.5 bg-[var(--accent-primary)] hover:bg-[var(--accent-hover)] text-white font-medium rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-xs cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Testimoni
                </button>
            </div>
        </div>
    </div>

    {{-- GRID TESTIMONI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($this->testimonials as $testimonial)
            <div
                class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm hover:shadow-lg transition-all duration-300 group relative">
                {{-- Rating --}}
                <div class="flex items-center gap-1 mb-3">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-slate-300' }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>

                {{-- Content --}}
                <p class="text-sm text-slate-600 leading-relaxed line-clamp-3 mb-4">
                    "{{ $testimonial->content }}"
                </p>

                {{-- Author --}}
                <div class="flex items-center justify-between pt-4 border-t border-slate-200/60">
                    <div class="flex items-center gap-3">
                        <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}"
                            class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                        <div>
                            <p class="font-bold text-sm text-[#1E293B]">{{ $testimonial->name }}</p>
                            @if ($testimonial->role)
                                <p class="text-xs text-slate-400">{{ $testimonial->role }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button"
                            @click="$dispatch('open-modal', { name: 'modal-testimonial', id: {{ $testimonial->id }} })"
                            class="p-1.5 text-slate-400 hover:text-[var(--accent-primary)] transition-colors rounded-lg hover:bg-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button type="button" wire:click="toggleStatus({{ $testimonial->id }})"
                            class="p-1.5 {{ $testimonial->is_active ? 'text-green-500 hover:text-green-700' : 'text-slate-400 hover:text-slate-600' }} transition-colors rounded-lg hover:bg-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        <button type="button" wire:click="delete({{ $testimonial->id }})"
                            wire:confirm="Yakin ingin menghapus testimoni ini?"
                            class="p-1.5 text-slate-400 hover:text-red-500 transition-colors rounded-lg hover:bg-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Order Badge --}}
                @if ($testimonial->order > 0)
                    <div
                        class="absolute top-3 right-3 bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded-full">
                        #{{ $testimonial->order }}
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3">
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="p-4 bg-slate-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <p class="text-slate-500 font-medium">Belum ada testimoni</p>
                    <p class="text-slate-400 text-sm mt-1">Tambahkan testimoni pertama Anda</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if ($this->testimonials->hasPages())
        <div class="mt-4">
            {{ $this->testimonials->links() }}
        </div>
    @endif

    {{-- MODAL FORM --}}
    <x-ui.modal name="modal-testimonial" title="Form Testimoni">
        <livewire:admin.testimonials.form />
    </x-ui.modal>

    <x-ui.confirm confirmLabel="Ya, Hapus Testimoni" />
</div>
