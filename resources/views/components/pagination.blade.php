@props(['items', 'wirePath' => null])

<style>
    .pagination {
        margin: 0;
    }

    .pagination .page-link {
        color: #5b93ff;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        margin: 0 2px;
        padding: 6px 10px;
        font-size: 13px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .pagination .page-link:hover {
        color: #fff;
        background-color: #5b93ff;
        border-color: #5b93ff;
        box-shadow: 0 2px 8px rgba(91, 147, 255, 0.2);
    }

    .pagination .page-item.active .page-link {
        color: #fff;
        background-color: #5b93ff;
        border-color: #5b93ff;
        box-shadow: 0 2px 8px rgba(91, 147, 255, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        color: #ccc;
        background-color: #f5f5f5;
        border-color: #e0e0e0;
        cursor: not-allowed;
    }

    .pagination .page-link i {
        margin: 0 4px;
        font-size: 12px;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .pagination {
            font-size: 12px;
        }

        .pagination .page-link {
            padding: 5px 8px;
            margin: 0 1px;
        }

        .d-flex.flex-wrap {
            flex-direction: column-reverse;
        }

        .d-flex.justify-content-between {
            justify-content: center;
        }

        .text-muted.font-14 {
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
        }
    }
</style>

{{-- Professional Pagination Component --}}
@if($items->hasPages())
<div class="mt-4 pt-3 border-top">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="text-muted font-14">
            Showing <span class="font-weight-bold">{{ $items->firstItem() }}</span>
            to <span class="font-weight-bold">{{ $items->lastItem() }}</span>
            of <span class="font-weight-bold">{{ $items->total() }}</span>
            records
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                {{-- Previous Page Link --}}
                @if($items->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="icon-copy fa fa-chevron-left"></i> Previous
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        @if($wirePath)
                            <a class="page-link cursor-pointer" wire:click="gotoPage({{ $items->currentPage() - 1 }})"
                               wire:loading.attr="disabled">
                                <i class="icon-copy fa fa-chevron-left"></i> Previous
                            </a>
                        @else
                            <a class="page-link cursor-pointer" href="{{ $items->url($items->currentPage() - 1) }}">
                                <i class="icon-copy fa fa-chevron-left"></i> Previous
                            </a>
                        @endif
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                    {{-- Three Dots --}}
                    @if($page > 2 && $page === 3 && $items->currentPage() > 3)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif

                    {{-- Page Numbers --}}
                    @if($page >= $items->currentPage() - 1 && $page <= $items->currentPage() + 1)
                        @if($page == $items->currentPage())
                            <li class="page-item active">
                                <span class="page-link">
                                    {{ $page }}
                                    <span class="sr-only">(current)</span>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                @if($wirePath)
                                    <a class="page-link cursor-pointer" wire:click="gotoPage({{ $page }})"
                                       wire:loading.attr="disabled">
                                        {{ $page }}
                                    </a>
                                @else
                                    <a class="page-link cursor-pointer" href="{{ $url }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            </li>
                        @endif
                    @endif

                    {{-- Three Dots --}}
                    @if($page === $items->lastPage() - 1 && $items->currentPage() < $items->lastPage() - 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if($items->hasMorePages())
                    <li class="page-item">
                        @if($wirePath)
                            <a class="page-link cursor-pointer" wire:click="gotoPage({{ $items->currentPage() + 1 }})"
                               wire:loading.attr="disabled">
                                Next <i class="icon-copy fa fa-chevron-right"></i>
                            </a>
                        @else
                            <a class="page-link cursor-pointer" href="{{ $items->nextPageUrl() }}">
                                Next <i class="icon-copy fa fa-chevron-right"></i>
                            </a>
                        @endif
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            Next <i class="icon-copy fa fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endif
