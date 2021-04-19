@if ($paginator->hasPages())
    <nav class="blog-pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <a class="btn btn-outline-secondary disabled" href="#">{{__('Pagination.prev')}}</a>
            @else
                <a class="btn btn-outline-primary" href="{{ $paginator->previousPageUrl() }}" rel="prev">{{__('Pagination.prev')}}</a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="btn btn-outline-primary" href="{{ $paginator->nextPageUrl() }}" rel="next">{{__('Pagination.next')}}</a>
            @else
                <a class="btn btn-outline-secondary disabled" href="#">{{__('Pagination.next')}}</a>
            @endif
    </nav>
@endif
