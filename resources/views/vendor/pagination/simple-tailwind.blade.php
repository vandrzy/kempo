@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex justify-between items-center gap-2">
        {{-- Tombol Halaman Sebelumnya --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center h-9 px-4 text-xs font-semibold text-neutral-400 bg-neutral-100/70 border border-[#ebebeb]/70 rounded-xl cursor-not-allowed select-none">
                ‹ Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center h-9 px-4 text-xs font-semibold text-neutral-700 bg-white border border-[#ebebeb] rounded-xl hover:bg-[#fff0eb] hover:text-[#f2470c] hover:border-[#f2470c]/30 shadow-2xs hover:-translate-y-px transition-all no-underline">
                ‹ Sebelumnya
            </a>
        @endif

        {{-- Tombol Halaman Selanjutnya --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center h-9 px-4 text-xs font-semibold text-neutral-700 bg-white border border-[#ebebeb] rounded-xl hover:bg-[#fff0eb] hover:text-[#f2470c] hover:border-[#f2470c]/30 shadow-2xs hover:-translate-y-px transition-all no-underline">
                Selanjutnya ›
            </a>
        @else
            <span class="inline-flex items-center justify-center h-9 px-4 text-xs font-semibold text-neutral-400 bg-neutral-100/70 border border-[#ebebeb]/70 rounded-xl cursor-not-allowed select-none">
                Selanjutnya ›
            </span>
        @endif
    </nav>
@endif
