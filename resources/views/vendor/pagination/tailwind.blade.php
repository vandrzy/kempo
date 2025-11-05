@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-center mt-6">
        <ul class="inline-flex items-center space-x-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        ‹ Previous
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 hover:text-blue-600 transition">
                        ‹ Previous
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>
                        <span class="px-3 py-2 text-gray-500 bg-gray-100 border border-gray-300 rounded-md">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="px-3 py-2 font-semibold text-white rounded-md cursor-default" style="color: blue;">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 hover:text-blue-600 transition">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 hover:text-blue-600 transition">
                        Next ›
                    </a>
                </li>
            @else
                <li>
                    <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                        Next ›
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
