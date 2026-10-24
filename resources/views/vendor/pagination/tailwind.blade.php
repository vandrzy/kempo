@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center justify-center my-0">
        <ul class="inline-flex items-center gap-1.5 flex-wrap justify-center list-none p-0 m-0">
            {{-- Tombol Halaman Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="inline-flex items-center justify-center h-9 px-3.5 text-xs font-semibold text-neutral-400 bg-neutral-100/70 border border-[#ebebeb]/70 rounded-xl cursor-not-allowed select-none">
                        ‹ Sebelumnya
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center h-9 px-3.5 text-xs font-semibold text-neutral-700 bg-white border border-[#ebebeb] rounded-xl hover:bg-[#fff0eb] hover:text-[#f2470c] hover:border-[#f2470c]/30 shadow-2xs hover:-translate-y-px transition-all no-underline">
                        ‹ Sebelumnya
                    </a>
                </li>
            @endif

            {{-- Elemen Pagination --}}
            @foreach ($elements as $element)
                {{-- Separator Titik Tiga ("...") --}}
                @if (is_string($element))
                    <li>
                        <span class="inline-flex items-center justify-center min-w-[36px] h-9 px-2 text-xs font-bold text-neutral-400 select-none">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                {{-- Daftar Nomor Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 text-xs font-bold text-white bg-[#f2470c] rounded-xl shadow-xs cursor-default tabular-nums select-none" aria-current="page">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="inline-flex items-center justify-center min-w-[36px] h-9 px-3 text-xs font-semibold text-neutral-700 bg-white border border-[#ebebeb] rounded-xl hover:bg-[#fff0eb] hover:text-[#f2470c] hover:border-[#f2470c]/30 shadow-2xs hover:-translate-y-px transition-all tabular-nums no-underline">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Halaman Selanjutnya --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center h-9 px-3.5 text-xs font-semibold text-neutral-700 bg-white border border-[#ebebeb] rounded-xl hover:bg-[#fff0eb] hover:text-[#f2470c] hover:border-[#f2470c]/30 shadow-2xs hover:-translate-y-px transition-all no-underline">
                        Selanjutnya ›
                    </a>
                </li>
            @else
                <li>
                    <span class="inline-flex items-center justify-center h-9 px-3.5 text-xs font-semibold text-neutral-400 bg-neutral-100/70 border border-[#ebebeb]/70 rounded-xl cursor-not-allowed select-none">
                        Selanjutnya ›
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
