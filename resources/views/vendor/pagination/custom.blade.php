@if ($paginator->hasPages())
    <div class="custom-pagination" style="display: flex; justify-content: center; gap: 8px; margin-top: 20px;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-btn disabled"
                style="opacity: 0.5; padding: 8px 16px; background: #f3f4f6; border-radius: 10px; cursor: not-allowed;"><i
                    data-lucide="chevron-left"></i></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn"
                style="padding: 8px 16px; background: #fff; border: 2px solid #f3f4f6; border-radius: 10px; color: var(--db-purple); text-decoration: none;"><i
                    data-lucide="chevron-left"></i></a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="page-btn disabled" style="padding: 8px 16px;">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-btn active"
                            style="padding: 8px 16px; background: var(--db-purple); color: #fff; border-radius: 10px; font-weight: 700;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn"
                            style="padding: 8px 16px; background: #fff; border: 2px solid #f3f4f6; border-radius: 10px; color: var(--db-text-dark); text-decoration: none; font-weight: 600;">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn"
                style="padding: 8px 16px; background: #fff; border: 2px solid #f3f4f6; border-radius: 10px; color: var(--db-purple); text-decoration: none;"><i
                    data-lucide="chevron-right"></i></a>
        @else
            <span class="page-btn disabled"
                style="opacity: 0.5; padding: 8px 16px; background: #f3f4f6; border-radius: 10px; cursor: not-allowed;"><i
                    data-lucide="chevron-right"></i></span>
        @endif
    </div>
@endif