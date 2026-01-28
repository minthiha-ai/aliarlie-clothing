<div class="shop-filters-inline-content">
  <div class="widget widget-search-inline">
    <div class="widget-search-box">
      <form action="{{ route('shop.index') }}" method="get">
        <div class="form-input-item">
          <label for="search-inline" class="sr-only">Search</label>
          <input type="text" id="search-inline" name="search" placeholder="Search…" value="{{ request('search') }}">
          <button type="submit" class="btn-src">
            <i class="icofont-search-1"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget widget-categories-inline">
    <details class="categories-details-inline" @if(count($categories) <= 4) open @endif>
      <summary class="categories-summary-inline">Categories</summary>
      <div class="widget-custom-menu">
        <ul>
          @forelse ($categories as $category)
            <li>
              <a href="{{ route('shop.index', ['category' => $category->id]) }}">
                <span class="cat-name">{{ $category->name }}</span>
                <span class="cat-count">({{ $category->products_count }})</span>
              </a>
            </li>
          @empty
            <li>No categories yet.</li>
          @endforelse
        </ul>
      </div>
    </details>
  </div>
</div>
