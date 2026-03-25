@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';

$isLivewire = isset($this) && (method_exists($this, 'gotoPage') || method_exists($this, 'previousPage'));
@endphp

<div class="mt-6">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between p-4 rounded-2xl border" 
             style="background: rgba(255,255,255,0.03); backdrop-filter: blur(12px); border-color: rgba(255,255,255,0.05);">
            
            <div class="flex justify-between flex-1 sm:hidden">
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-white/20 bg-white/5 border border-white/5 cursor-default rounded-xl">
                        &laquo; Previous
                    </span>
                @else
                    @if ($isLivewire)
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                                class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-white/5 border border-white/10 rounded-xl hover:bg-blue-500/20 hover:border-blue-500/30 transition-all">
                            &laquo; Previous
                        </button>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" 
                           class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-white/5 border border-white/10 rounded-xl hover:bg-blue-500/20 hover:border-blue-500/30 transition-all">
                            &laquo; Previous
                        </a>
                    @endif
                @endif

                @if ($paginator->hasMorePages())
                    @if ($isLivewire)
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                                class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-bold text-white bg-white/5 border border-white/10 rounded-xl hover:bg-blue-500/20 hover:border-blue-500/30 transition-all">
                            Next &raquo;
                        </button>
                    @else
                        <a href="{{ $paginator->nextPageUrl() }}" 
                           class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-bold text-white bg-white/5 border border-white/10 rounded-xl hover:bg-blue-500/20 hover:border-blue-500/30 transition-all">
                            Next &raquo;
                        </a>
                    @endif
                @else
                    <span class="relative inline-flex items-center px-4 py-2 ml-3 text-xs font-bold text-white/20 bg-white/5 border border-white/5 cursor-default rounded-xl">
                        Next &raquo;
                    </span>
                @endif
            </div>

            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs text-white/40 font-medium">
                        Showing
                        <span class="text-white font-bold">{{ $paginator->firstItem() }}</span>
                        to
                        <span class="text-white font-bold">{{ $paginator->lastItem() }}</span>
                        of
                        <span class="text-white font-bold">{{ $paginator->total() }}</span>
                        results
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex shadow-sm rounded-xl overflow-hidden border border-white/10 bg-white/5">
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <span aria-disabled="true" class="relative inline-flex items-center px-3 py-2 text-white/20 cursor-default">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @else
                            @if ($isLivewire)
                                <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                                        class="relative inline-flex items-center px-3 py-2 text-white hover:bg-white/10 transition-all border-r border-white/10 text-center flex justify-center">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <a href="{{ $paginator->previousPageUrl() }}" 
                                   class="relative inline-flex items-center px-3 py-2 text-white hover:bg-white/10 transition-all border-r border-white/10 text-center flex justify-center">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-white/30 border-r border-white/10">{{ $element }}</span>
                                </span>
                            @endif

                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($isLivewire)
                                        <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                            @if ($page == $paginator->currentPage())
                                                <span aria-current="page">
                                                    <span class="relative inline-flex items-center px-4 py-2 text-xs font-black text-white bg-blue-600 border-r border-white/10">{{ $page }}</span>
                                                </span>
                                            @else
                                                <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                                                        class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-white/60 hover:text-white hover:bg-white/10 transition-all border-r border-white/10">
                                                    {{ $page }}
                                                </button>
                                            @endif
                                        </span>
                                    @else
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span class="relative inline-flex items-center px-4 py-2 text-xs font-black text-white bg-blue-600 border-r border-white/10">{{ $page }}</span>
                                            </span>
                                        @else
                                            <a href="{{ $url }}" 
                                               class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-white/60 hover:text-white hover:bg-white/10 transition-all border-r border-white/10">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            @if ($isLivewire)
                                <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" 
                                        class="relative inline-flex items-center px-3 py-2 text-white hover:bg-white/10 transition-all text-center flex justify-center">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <a href="{{ $paginator->nextPageUrl() }}" 
                                   class="relative inline-flex items-center px-3 py-2 text-white hover:bg-white/10 transition-all text-center flex justify-center">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                        @else
                            <span aria-disabled="true" class="relative inline-flex items-center px-3 py-2 text-white/20 cursor-default">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
