@if ($products->hasPages())
    <div class="flex items-center justify-between border-t border-gray-100 pt-6">
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($products->onFirstPage())
                <span class="relative inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-300 pointer-events-none">Sebelumnya</span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="relative inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Sebelumnya</a>
            @endif

            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Berikutnya</a>
            @else
                <span class="relative ml-3 inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-300 pointer-events-none">Berikutnya</span>
            @endif
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Menampilkan
                    <span class="font-medium text-gray-900">{{ $products->firstItem() }}</span>
                    sampai
                    <span class="font-medium text-gray-900">{{ $products->lastItem() }}</span>
                    dari
                    <span class="font-medium text-gray-900">{{ $products->total() }}</span>
                    produk
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-xl shadow-xs" aria-label="Pagination">
                    {{-- Previous Page Link --}}
                    @if ($products->onFirstPage())
                        <span class="relative inline-flex items-center rounded-l-xl border border-gray-200 bg-white p-2 text-sm font-medium text-gray-300 pointer-events-none">&larr;</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-xl border border-gray-200 bg-white p-2 text-sm font-medium text-gray-500 hover:bg-gray-50">&larr;</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($products->links()->elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="relative inline-flex items-center border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $products->currentPage())
                                    <span aria-current="page" class="relative z-10 inline-flex items-center bg-primary px-4 py-2 text-sm font-semibold text-white focus:z-20">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-xl border border-gray-200 bg-white p-2 text-sm font-medium text-gray-500 hover:bg-gray-50">&rarr;</a>
                    @else
                        <span class="relative inline-flex items-center rounded-r-xl border border-gray-200 bg-white p-2 text-sm font-medium text-gray-300 pointer-events-none">&rarr;</span>
                    @endif
                </nav>
            </div>
        </div>
    </div>
@endif
