@props([
    'name', // Nama properti model Livewire (contoh: form.customer_id)
    'placeholder' => 'Mulai mengetik untuk mencari...',
    'list' => '[]', // Data JSON Array kustom dari parent
    'icon' => 'magnifying-glass', // Emoji atau icon awalan input
])

<div class="space-y-1.5" x-data="{
    openDropdown: false,
    searchQuery: '',
    items: {{ $list }},

    get filteredItems() {
        if (!this.searchQuery) return this.items;
        return this.items.filter(i =>
            i.label.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
            (i.sub && i.sub.toString().includes(this.searchQuery))
        );
    },
    selectItem(item) {
        this.searchQuery = item.label;
        // 🔥 PERBAIKAN RADIKAL 1: Gunakan properti $wire magis Alpine, anti salah alamat ID komponen
        $wire.set('{{ $name }}', item.id);
        this.openDropdown = false;
    },
    syncLabel(value) {
        if (!value) {
            this.searchQuery = '';
            return;
        }
        let match = this.items.find(i => i.id == value);
        if (match) this.searchQuery = match.label;
    }
}" {{-- 🔥 PERBAIKAN RADIKAL 2: Dengarkan event global pendaftaran baru secara langsung di level komponen ini --}}
    @customer-registered-successfully.window="
    items.push($event.detail);
    if ($event.detail.id) {
        syncLabel($event.detail.id);
    }
"
    x-init="// Sinkronisasi awal saat komponen lahir
    let initialValue = $wire.get('{{ $name }}');
    if (initialValue) syncLabel(initialValue);
    
    // Pantau perubahan dari server live
    $watch('$wire.{{ $name }}', value => syncLabel(value));" {{-- 🔥 PERBAIKAN RADIKAL 3: Selalu sinkronkan array lokal dengan data kiriman server --}} x-effect="items = {{ $list }}">

    <div class="flex gap-2 items-center relative">
        <div class="flex-1 relative">
            {{-- INPUT SEARCH BOX --}}
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                <x-dynamic-component :component="'heroicon-m-' . $icon" class="w-4 h-4" />
            </span>
            <input type="text" x-model="searchQuery" @focus="openDropdown = true"
                @click.away="setTimeout(() => openDropdown = false, 250)" @keydown.escape="openDropdown = false"
                class="w-full bg-white border border-slate-300 text-slate-900 rounded-xl pl-9 pr-4 py-2.5 text-xs font-bold focus:border-amber-500 focus:outline-none transition-all shadow-xs"
                placeholder="{{ $placeholder }}" />

            {{-- DROPDOWN CONTAINER --}}
            <div x-show="openDropdown && filteredItems.length > 0"
                class="absolute z-30 w-full mt-1 bg-white border border-slate-300 rounded-xl shadow-xl max-h-48 overflow-y-auto divide-y divide-slate-100"
                x-transition style="display: none;">
                <template x-for="item in filteredItems" :key="item.id">
                    <button type="button" @click="selectItem(item)"
                        class="w-full text-left px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-900 flex justify-between items-center transition-colors">
                        <span x-text="item.label" class="font-extrabold text-slate-900"></span>
                        <template x-if="item.sub">
                            <span x-text="item.sub" class="font-mono text-[10px] text-slate-400"></span>
                        </template>
                    </button>
                </template>
            </div>

            {{-- EMPTY STATE STATE --}}
            <div x-show="openDropdown && searchQuery && filteredItems.length === 0"
                class="absolute z-30 w-full mt-1 bg-white border border-slate-300 rounded-xl shadow-md p-3 text-center text-xs text-slate-400 font-bold"
                style="display: none;">
                Data tidak ditemukan.
            </div>
        </div>

        {{-- SLOT TOMBOL PLUS --}}
        @if (isset($slot))
            {{ $slot }}
        @endif
    </div>

    @error($name)
        <span class="text-[10px] text-rose-600 font-bold block mt-1">{{ $message }}</span>
    @enderror
</div>
