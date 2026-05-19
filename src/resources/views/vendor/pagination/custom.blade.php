@if ($paginator->hasPages())
  <div class="pagination-wrapper">
    <!-- 前ページへのリンク -->
    @if ($paginator->onFirstPage())
      <span class="pagination-item pagination-item--disabled">&lt;</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" class="pagination-item">&lt;</a>
    @endif

    <!-- ページ番号 -->
    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="pagination-item pagination-item--disabled">{{ $element }}</span>
      @endif

      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="pagination-item pagination-item--active">{{ $page }}</span>
          @else
            <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    <!-- 次ページへのリンク -->
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" class="pagination-item">&gt;</a>
    @else
      <span class="pagination-item pagination-item--disabled">&gt;</span>
    @endif
  </div>

@endif
