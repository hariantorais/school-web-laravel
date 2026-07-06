@foreach ($sidebarMenu as $menuGroup)
    {{-- Judul Kelompok Menu --}}
    <div class="px-3 mb-2 text-[10px] font-semibold text-slate-500 uppercase tracking-widest block whitespace-nowrap overflow-hidden pt-4 first:pt-0 transition-all duration-200"
        @if (!$isMobile) x-show="sidebarOpen" x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" @endif>
        {{ $menuGroup['group'] }}
    </div>

    @foreach ($menuGroup['items'] as $item)
        @php
            // Evaluasi Status Aktif Parent Berdasarkan Array active_on
            $isGroupActive = false;
            foreach ($item['active_on'] as $activeRoute) {
                if (request()->routeIs($activeRoute)) {
                    $isGroupActive = true;
                    break;
                }
            }
        @endphp

        @if ($item['has_children'])
            {{-- OPTION A: MENU BERANAK (DROPDOWN) --}}
            <div class="space-y-1 block clear-both" x-init="if ({{ $isGroupActive ? 'true' : 'false' }}) { if (!openMenus.includes('{{ $item['id'] }}')) openMenus.push('{{ $item['id'] }}') }">
                <button type="button"
                    x-on:click="openMenus.includes('{{ $item['id'] }}') ? openMenus = openMenus.filter(m => m !== '{{ $item['id'] }}') : openMenus.push('{{ $item['id'] }}')"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all group overflow-hidden whitespace-nowrap text-left cursor-pointer {{ $isGroupActive ? 'text-[var(--accent-text)] bg-[var(--accent-muted)]/30 border border-teal-900/10 shadow-xs' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100 border border-transparent' }}"
                    title="{{ $item['name'] }}">

                    <div class="flex items-center gap-3 min-w-0">
                        <x-dynamic-component :component="'heroicon-o-' . $item['icon']"
                            class="w-5 h-5 flex-shrink-0 {{ $isGroupActive ? 'text-[var(--accent-text)]' : 'text-slate-500 group-hover:text-slate-300' }}" />

                        <span @if (!$isMobile) x-show="sidebarOpen" @endif
                            class="transition-opacity duration-200 truncate">{{ $item['name'] }}</span>
                    </div>

                    {{-- INDIKATOR PANAH CHEVRON --}}
                    <div @if (!$isMobile) x-show="sidebarOpen" @endif
                        class="transition-transform duration-200 flex-shrink-0 text-slate-500 {{ $isGroupActive ? 'text-[var(--accent-text)]' : '' }}"
                        :class="{ 'rotate-180': openMenus.includes('{{ $item['id'] }}') }">
                        <x-heroicon-o-chevron-down class="w-4 h-4 text-current" />
                    </div>
                </button>

                {{-- AREA SUB-MENU (CHILDREN LINK) --}}
                <div x-show="openMenus.includes('{{ $item['id'] }}') {{ $isMobile ? '' : '&& sidebarOpen' }}"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 transform -translate-y-1"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="pl-8 space-y-1 overflow-hidden relative">

                    @foreach ($item['children'] as $child)
                        @php
                            // Evaluasi Status Aktif Anak Secara Presisi
                            $isChildActive = false;
                            foreach ($child['active_on'] as $childRoute) {
                                if (request()->routeIs($childRoute)) {
                                    $isChildActive = true;
                                    break;
                                }
                            }
                        @endphp

                        {{-- 🔥 PERBAIKAN TARGET: Pengamanan Pemanggilan Route Menggunakan Route::has() dan Pengecekan String --}}
                        <a href="{{ Route::has($child['route']) ? route($child['route']) : '#' }}" wire:navigate
                            @if ($isMobile) x-on:click="mobileSidebarOpen = false" @endif
                            class="flex items-center px-3 py-2 text-xs rounded-md transition-all truncate whitespace-nowrap relative group cursor-pointer
                           {{ $isChildActive
                               ? 'text-slate-100 font-extrabold'
                               : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/40 font-medium' }}">

                            {{-- Bulatan Titik Pelengkap (Semua Anak Memiliki Bullet Dot) --}}
                            <span
                                class="absolute left-0 w-1 h-1 rounded-full transition-all duration-150
                                {{ $isChildActive ? 'bg-[var(--accent-text)] animate-pulse shadow-md' : 'bg-slate-600 group-hover:bg-slate-400' }}">
                            </span>

                            <span class="pl-3 transition-all duration-150">
                                {{ $child['name'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            {{-- OPTION B: MENU TUNGGAL --}}
            @php
                $isSingleActive = false;
                foreach ($item['active_on'] as $singleRoute) {
                    if (request()->routeIs($singleRoute)) {
                        $isSingleActive = true;
                        break;
                    }
                }
            @endphp
            <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" wire:navigate
                @if ($isMobile) x-on:click="mobileSidebarOpen = false" @endif
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all group overflow-hidden whitespace-nowrap {{ $isSingleActive ? 'bg-[var(--accent-muted)]/50 text-[var(--accent-text)] border border-teal-900/30' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100 border border-transparent' }}"
                title="{{ $item['name'] }}">

                <x-dynamic-component :component="'heroicon-o-' . $item['icon']"
                    class="w-5 h-5 flex-shrink-0 {{ $isSingleActive ? 'text-[var(--accent-text)]' : 'text-slate-500 group-hover:text-slate-300' }}" />

                <span @if (!$isMobile) x-show="sidebarOpen" @endif
                    class="transition-opacity duration-200 truncate">{{ $item['name'] }}</span>
            </a>
        @endif
    @endforeach
@endforeach
