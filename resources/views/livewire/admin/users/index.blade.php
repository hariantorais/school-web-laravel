<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\{Computed, On};

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';

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

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterStatus']);
        $this->resetPage();
        $this->dispatch('filters-reseted');
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->when(trim($this->search), function ($query) {
                $query->where('name', 'like', '%' . trim($this->search) . '%')->orWhere('email', 'like', '%' . trim($this->search) . '%');
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9);
    }

    public function delete(int $id)
    {
        try {
            $user = User::withCount(['posts', 'comments', 'orders'])->findOrFail($id);

            if ($user->id === auth()->id()) {
                $this->dispatch('toast', type: 'error', message: 'Tidak bisa menghapus akun sendiri!');
                return;
            }

            // Cek relasi
            $related = [];
            if ($user->posts_count > 0) {
                $related[] = "{$user->posts_count} postingan";
            }
            if ($user->comments_count > 0) {
                $related[] = "{$user->comments_count} komentar";
            }
            if ($user->orders_count > 0) {
                $related[] = "{$user->orders_count} pesanan";
            }

            if (!empty($related)) {
                $this->dispatch('toast', type: 'error', message: 'Pengguna tidak dapat dihapus karena masih memiliki: ' . implode(', ', $related));
                return;
            }

            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->delete();
            $this->dispatch('toast', type: 'success', message: 'Pengguna berhasil dihapus!');
        } catch (ModelNotFoundException $e) {
            $this->dispatch('toast', type: 'error', message: 'Pengguna tidak ditemukan!');
        } catch (QueryException $e) {
            Log::error('Delete user query error: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Pengguna tidak dapat dihapus karena masih terhubung dengan data lain!');
        } catch (\Exception $e) {
            Log::error('Delete user error: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan saat menghapus pengguna!');
        }
    }

    public function toggleStatus(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            $this->dispatch('toast', type: 'error', message: 'Tidak bisa mengubah status akun sendiri!');
            return;
        }

        $user->update(['is_active' => !$user->is_active]);
        $this->dispatch('toast', type: 'success', message: 'Status pengguna berhasil diubah!');
    }
};

?>

<div class="space-y-6">

    {{-- FILTER & SEARCH --}}
    <div class="bg-white p-4 border border-slate-200/60 rounded-2xl shadow-xl shadow-slate-100/40" x-data="{
        localSearch: @entangle('search'),
        localStatus: @entangle('filterStatus'),
        isTyping: false
    }"
        @filters-reseted.window="localSearch = ''; localStatus = ''; isTyping = false">

        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">

            <div class="grid grid-cols-1 sm:grid-cols-2 flex-1 gap-3">

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
                        wire:model.live.debounce.400ms="search" placeholder="Cari nama atau email..."
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
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-2.5 justify-end">
                <template x-if="localSearch || localStatus !== ''">
                    <button type="button" wire:click="resetFilters"
                        class="inline-flex items-center gap-1.5 px-3 py-2.5 text-xs font-bold text-slate-500 hover:text-rose-600 bg-slate-50 border border-slate-200 rounded-xl hover:bg-rose-50 transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </button>
                </template>

                <button type="button" @click="$dispatch('open-modal', { name: 'modal-user' })"
                    class="inline-flex items-center gap-2 justify-center px-4 py-2.5 bg-[var(--accent-primary)] hover:bg-[var(--accent-hover)] text-white font-medium rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-xs cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pengguna
                </button>
            </div>
        </div>
    </div>

    {{-- TABLE USERS --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/60">
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Dibuat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->users as $index => $user)
                        <tr class="border-b border-slate-200/40 hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                        class="w-8 h-8 rounded-full object-cover border border-slate-200">
                                    <span class="font-medium text-slate-700">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <x-table.actions :id="$user->id" modalName="modal-user" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <p>Belum ada pengguna</p>
                                    <button type="button" @click="$dispatch('open-modal', { name: 'modal-user' })"
                                        class="text-[var(--accent-primary)] hover:underline text-sm">
                                        Tambah pengguna pertama
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <x-table.pagination :paginator="$this->users" />

        {{-- MODAL FORM --}}
        <livewire:admin.users.form />

        <x-ui.confirm confirmLabel="Ya, Hapus Pengguna" />
    </div>
