@if ($paginator->hasPages())
    <nav>
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="page-link" style="opacity:.4;cursor:default;"><i class="fas fa-chevron-left"></i></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-link"><i class="fas fa-chevron-left"></i></a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-link" style="cursor:default;">...</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-link active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-link"><i class="fas fa-chevron-right"></i></a>
        @else
            <span class="page-link" style="opacity:.4;cursor:default;"><i class="fas fa-chevron-right"></i></span>
        @endif
    </nav>
@endif
