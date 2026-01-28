@if (isset($paginator) && $paginator->hasPages())
  <div class="pagination-area">
    <nav>
      <ul class="page-numbers">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
          <li>
            <span class="page-number prev disabled" aria-disabled="true">
              <i class="icofont-long-arrow-left"></i>
            </span>
          </li>
        @else
          <li>
            <a class="page-number prev" href="{{ $paginator->previousPageUrl() }}" rel="prev">
              <i class="icofont-long-arrow-left"></i>
            </a>
          </li>
        @endif

        {{-- Page numbers --}}
        @php
          $current = $paginator->currentPage();
          $last = $paginator->lastPage();
          $range = 2;
          $pages = [];
          if ($last <= 7) {
            $pages = range(1, $last);
          } else {
            $pages[] = 1;
            if ($current > $range + 2) {
              $pages[] = '…';
            }
            $start = max(2, $current - $range);
            $end = min($last - 1, $current + $range);
            for ($i = $start; $i <= $end; $i++) {
              if (!in_array($i, $pages)) {
                $pages[] = $i;
              }
            }
            if ($current < $last - $range - 1) {
              $pages[] = '…';
            }
            if ($last > 1 && !in_array($last, $pages)) {
              $pages[] = $last;
            }
          }
        @endphp

        @foreach ($pages as $page)
          @if ($page === '…')
            <li>
              <span class="page-number">…</span>
            </li>
          @else
            <li>
              <a class="page-number {{ (int) $page === $current ? 'active' : '' }}" href="{{ $paginator->url($page) }}">
                {{ $page }}
              </a>
            </li>
          @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
          <li>
            <a class="page-number next" href="{{ $paginator->nextPageUrl() }}" rel="next">
              <i class="icofont-long-arrow-right"></i>
            </a>
          </li>
        @else
          <li>
            <span class="page-number next disabled" aria-disabled="true">
              <i class="icofont-long-arrow-right"></i>
            </span>
          </li>
        @endif
      </ul>
    </nav>
  </div>
@endif
