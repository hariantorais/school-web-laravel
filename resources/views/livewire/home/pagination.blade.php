@props(['paginator', 'showInfo' => true, 'showPerPage' => true, 'perPageOptions' => [6, 12, 24, 48]])

@if ($paginator->hasPages())
    <div class="flex flex-col gap-4 mt-8 pt-6 border-t border-slate-200/60">

        {{-- Info & Per Page - Stack di mobile, row di desktop --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            {{-- Info Jumlah Data --}}
            @if ($showInfo)
                <div class="text-xs sm:text-sm text-slate-500 text-center sm:text-left">
                    Menampilkan
                    <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}</span>
                    -
                    <span class="font-semibold text-slate-700">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span>
                    data
                </div>
            @endif

            {{-- Per Page Selector --}}
            @if ($showPerPage)
                <div class="flex items-center gap-2">
                    <span class="text-xs sm:text-sm text-slate-500">Tampilkan</span>
                    <select wire:model.live="perPage"
                        class="px-2 py-1 text-xs sm:text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#A31D1D]/20 focus:border-[#A31D1D] outline-none bg-white">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    <span class="text-xs sm:text-sm text-slate-500">per halaman</span>
                </div>
            @endif
        </div>

        {{-- Pagination Links - Responsive --}}
        <div class="flex items-center justify-center gap-1 flex-wrap">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-2 sm:px-3 py-2 text-sm text-slate-300 bg-slate-50 rounded-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled"
                    class="px-2 sm:px-3 py-2 text-sm text-slate-600 bg-white hover:bg-[#A31D1D] hover:text-white rounded-lg transition-all duration-200 border border-slate-200 hover:border-[#A31D1D]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($paginator->links()->elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-2 sm:px-3 py-2 text-xs sm:text-sm text-slate-400">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-[#A31D1D] rounded-lg shadow-sm min-w-[32px] sm:min-w-[40px] text-center">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }})"
                                class="px-3 sm:px-4 py-2 text-xs sm:text-sm text-slate-600 hover:bg-[#A31D1D] hover:text-white rounded-lg transition-all duration-200 min-w-[32px] sm:min-w-[40px] text-center">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled"
                    class="px-2 sm:px-3 py-2 text-sm text-slate-600 bg-white hover:bg-[#A31D1D] hover:text-white rounded-lg transition-all duration-200 border border-slate-200 hover:border-[#A31D1D]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @else
                <span class="px-2 sm:px-3 py-2 text-sm text-slate-300 bg-slate-50 rounded-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </div>
@endif
