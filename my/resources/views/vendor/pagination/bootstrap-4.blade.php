<?php
$link_limit = 7;  //リンクの最大表示数
$half_links = floor($link_limit / 2);
$current = $paginator->currentPage();
$last = $paginator->lastPage();
$from = $current - $half_links;
$to = $current + $half_links;
if ($current < $half_links) {
   $to += $half_links - $current;
}
if ($last - $current < $half_links) {
    $from -= $half_links - ($last - $current) - 1;
}
?>
@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}" rel="first">&laquo;</a></li>
        @endif

        {{-- Array Of Links --}}
        @for ($i = 1; $i <= $last; $i++)
            @if ($from < $i && $i < $to)
                @if ($i == $current)
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endif
            @endif
        @endfor

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->url($last) }}" rel="last">&raquo;</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
        @endif
    </ul>
@endif
