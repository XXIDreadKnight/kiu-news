@if ($paginator->hasPages())
    <nav class="pager">
        @if ($paginator->onFirstPage())
            <span class="off">&larr; Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">&larr; Previous</a>
        @endif

        <span class="info">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">Next &rarr;</a>
        @else
            <span class="off">Next &rarr;</span>
        @endif
    </nav>
@endif
