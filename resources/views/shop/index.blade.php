@extends('layouts.app')

@section('title', 'Shop - AliarLIE Clothing')

@section('wrapper-class', 'page-Shop-wrapper')

@section('content')
  @php
      $bannerImage = $banner?->image
          ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image)
          : asset('assets/img/photos/bg-page1.jpg');
  @endphp

  <!--== Start Page Title Area ==-->
  <section class="page-title-area bg-img" data-bg-img="{{ $bannerImage }}">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="page-title-content">
            <h2 class="title">{{ $banner->title ?? 'Shop' }}</h2>
            <div class="bread-crumbs">
              <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
              <span class="active">Shop</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Page Title Area ==-->

  <!--== Start Product Area Wrapper ==-->
  <section class="product-area product-shop-inner-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 order-0 order-lg-0 d-none d-lg-block">
          <div class="sidebar-area inner-right-padding shop-sidebar-area">
            <div class="shop-mobile-filters">
              <div class="widget widget-search-mobile">
                <div class="widget-search-box">
                  <form action="{{ route('shop.index') }}" method="get">
                    @if (request('category'))
                      <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if (request('per_page'))
                      <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    @endif
                    @if (request('sort'))
                      <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <div class="form-input-item">
                      <label for="search2" class="sr-only">Search Here</label>
                      <input type="text" id="search2" name="search" placeholder="Search…" value="{{ request('search') }}">
                      <button type="submit" class="btn-src">
                        <i class="icofont-search-1"></i>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="widget widget-categories-mobile">
                <details class="categories-details-mobile" @if(count($categories) <= 4) open @endif>
                  <summary class="categories-summary">Categories</summary>
                  <div class="widget-custom-menu">
                    <ul>
                      @forelse ($categories as $category)
                        <li>
                          <a href="{{ route('shop.index', array_merge(request()->only(['search', 'per_page', 'sort']), ['category' => $category->id])) }}">
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
            {{-- <div class="widget">
              <h4 class="widget-title">By price</h4>
              <div class="widget-price-filter">
                <div class="slider-range" id="slider-range"></div>
                <div class="slider-labels">
                  <span class="range-price-title">Price:</span>
                  <div class="caption">
                    <span id="slider-range-value1"></span>
                  </div>
                  <span class="range-separator"> — </span>
                  <div class="caption">
                    <span id="slider-range-value2"></span>
                  </div>
                </div>
                <a class="btn-filter" href="{{ route('shop.index') }}">Filter</a>
              </div>
            </div> --}}
            {{-- <div class="widget">
              <h4 class="widget-title">By Color</h4>
              <div class="widget-color-menu">
                <ul>
                  <li class="blue"></li>
                  <li class="brown"></li>
                  <li class="red"></li>
                  <li class="violet"></li>
                </ul>
              </div>
            </div>
            <div class="widget">
              <h4 class="widget-title">By Size</h4>
              <div class="widget-size-menu">
                <ul>
                  <li><a href="#/">Small</a>(9)</li>
                  <li><a href="#/">Medium</a>(10)</li>
                  <li><a href="#/">Large</a>(8)</li>
                  <li><a href="#/">XL</a>(6)</li>
                </ul>
              </div>
            </div>
            <div class="widget">
              <h4 class="widget-title">By brands</h4>
              <div class="widget-size-menu">
                <ul>
                  <li><a href="#/">Aliarlie</a>(9)</li>
                  <li><a href="#/">AliarLIE</a>(10)</li>
                  <li><a href="#/">Studio</a>(7)</li>
                  <li><a href="#/">Basics</a>(4)</li>
                </ul>
              </div>
            </div>
            <div class="widget">
              <div class="widget-banner">
                <a href="{{ route('shop.index') }}">
                  <img src="{{ asset('assets/img/photos/banner1.jpg') }}" alt="Banner">
                </a>
              </div>
            </div>
            <div class="widget">
              <h3 class="widget-title">Instagram</h3>
              <div class="widget-gallery">
                <div class="row row-cols-3 row-gutter-4">
                  @foreach (range(1, 6) as $galleryIndex)
                    <div class="col">
                      <div class="gallery-item">
                        <img src="{{ asset("assets/img/photos/gallery{$galleryIndex}.jpg") }}" alt="Gallery">
                        <a class="icon" href="#/"><i class="icofont-instagram"></i></a>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div> --}}
          </div>
        </div>
        <div class="col-lg-9 order-1 order-lg-1">
          <div class="inner-left-padding">
            @php
              $shopQuery = request()->only(['search', 'category', 'per_page', 'sort']);
              $currentPerPage = (int) (request('per_page', 12));
              $currentPerPage = in_array($currentPerPage, [12, 15, 30], true) ? $currentPerPage : 12;
              $currentSort = request('sort', 'default');
              $currentSort = in_array($currentSort, ['default', 'latest', 'oldest', 'lowest_price', 'highest_price'], true) ? $currentSort : 'default';
              $sortLabels = ['default' => 'Default', 'latest' => 'Latest', 'oldest' => 'Oldest', 'lowest_price' => 'Lowest price', 'highest_price' => 'Highest price'];
            @endphp
            <div class="shop-toolbar-wrap shop-toolbar-mobile-wrap">
              <div class="shop-toolbar-left">
                <div class="product-showing-status">
                  <p class="count-result mb-0">
                    <span class="d-none d-sm-inline">Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of </span>{{ $products->total() }} results
                  </p>
                </div>
              </div>
              <div class="shop-toolbar-right">
                <div class="product-sorting-menu product-view-count d-none d-md-block">
                  <span class="current">Show {{ $currentPerPage }} <i class="lastudioicon-down-arrow"></i></span>
                  <ul>
                    @foreach ([12, 15, 30] as $n)
                      <li class="{{ $currentPerPage === $n ? 'active' : '' }}"><a href="{{ route('shop.index', array_merge($shopQuery, ['per_page' => $n])) }}" class="{{ $currentPerPage === $n ? 'active' : '' }}">Show {{ $n }}</a></li>
                    @endforeach
                  </ul>
                </div>
                <div class="product-sorting-menu product-view-count d-none d-md-block">
                  <span class="current">Filters <i class="lastudioicon-down-arrow"></i></span>
                  <ul>
                    <li class="{{ ! request('category') ? 'active' : '' }}"><a href="{{ route('shop.index', request()->only(['search', 'per_page', 'sort'])) }}" class="{{ ! request('category') ? 'active' : '' }}">All</a></li>
                    <li><a href="{{ route('shop.index', $shopQuery) }}">Categories</a></li>
                    <li><a href="{{ route('shop.index', $shopQuery) }}">Tags</a></li>
                  </ul>
                </div>
                <div class="product-sorting-menu product-sorting">
                  <span class="current">{{ $sortLabels[$currentSort] }} <i class="lastudioicon-down-arrow"></i></span>
                  <ul>
                    @foreach ($sortLabels as $value => $label)
                      <li class="{{ $currentSort === $value ? 'active' : '' }}"><a href="{{ route('shop.index', array_merge($shopQuery, ['sort' => $value])) }}" class="{{ $currentSort === $value ? 'active' : '' }}">{{ $label }}</a></li>
                    @endforeach
                  </ul>
                </div>
                <div class="product-view-mode product-view-mode-mobile">
                  <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      <button class="nav-link" id="nav-list-tab" data-bs-toggle="tab" data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-list" aria-selected="false" title="List view"><i class="lastudioicon-list-bullet-2"></i></button>
                      <button class="nav-link active" id="nav-grid-tab" data-bs-toggle="tab" data-bs-target="#nav-grid" type="button" role="tab" aria-controls="nav-grid" aria-selected="true" title="Grid view"><i class="lastudioicon-microsoft"></i></button>
                    </div>
                  </nav>
                </div>
              </div>
            </div>
            <div class="shop-filters-inline d-lg-none">
              @include('shop.partials.filters-inline', ['categories' => $categories])
            </div>
            <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active" id="nav-grid" role="tabpanel" aria-labelledby="nav-grid-tab">
                <div class="row row-gutter-60 product-items-style4">
                  @forelse ($products as $product)
                    @php
                      $image = $product->images->first();
                      $price = $product->discount_price ?? $product->price;
                    @endphp
                    <div class="col-sm-6 col-md-4">
                      <div class="product-item">
                        <div class="product-thumb">
                          <a href="{{ route('shop.product', $product->id) }}">
                            <img src="{{ $image ? \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_path) : asset('assets/img/shop/13.jpg') }}" alt="{{ $product->name }}">
                            <span class="thumb-overlay"></span>
                          </a>
                          <div class="product-action action-style3">
                            <form method="POST" action="{{ route('shop.cart.add') }}">
                              @csrf
                              <input type="hidden" name="product_id" value="{{ $product->id }}">
                              <input type="hidden" name="quantity" value="1">
                              <button class="action-cart ht-tooltip" data-tippy-content="Add to cart" title="Add to cart" type="submit">
                                <i class="lastudioicon-shopping-cart-3"></i>
                              </button>
                            </form>
                            {{-- <a class="action-quick-view ht-tooltip" data-tippy-content="Quick View" href="javascript:void(0);" title="Quick View">
                              <i class="lastudioicon-search-zoom-in"></i>
                            </a>
                            <a class="action-wishlist ht-tooltip" data-tippy-content="Add to wishlist" href="{{ route('shop.wishlist') }}" title="Add to wishlist">
                              <i class="lastudioicon-heart-2"></i>
                            </a>
                            <a class="action-compare ht-tooltip" data-tippy-content="Add to compare" href="{{ route('shop.compare') }}" title="Add to compare">
                              <i class="lastudioicon-compare"></i>
                            </a> --}}
                          </div>
                        </div>
                        <div class="product-info info-style2">
                          <div class="content-inner">
                            <h4 class="title"><a href="{{ route('shop.product', $product->id) }}">{{ $product->name }}</a></h4>
                            <div class="prices">
                              <span class="price {{ !$product->instock ? 'text-decoration-line-through text-danger' : '' }}">MMK {{ number_format($price, 2) }}</span>
                              @if (!$product->instock)
                                <span class="d-block small text-danger fw-semibold mt-1">Out of stock</span>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @empty
                    <div class="col-12">
                      <p class="text-center">No products available yet.</p>
                    </div>
                  @endforelse
                </div>
              </div>
              <div class="tab-pane fade" id="nav-list" role="tabpanel" aria-labelledby="nav-list-tab">
                <div class="row">
                  <div class="col-12 product-items-list">
                    @foreach ($products as $product)
                      @php
                        $image = $product->images->first();
                        $price = $product->discount_price ?? $product->price;
                      @endphp
                      <div class="product-item">
                        <div class="product-thumb">
                          <a href="{{ route('shop.product', $product->id) }}">
                            <img src="{{ $image ? \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_path) : asset('assets/img/shop/list1.jpg') }}" alt="{{ $product->name }}">
                            <span class="thumb-overlay"></span>
                          </a>
                          <div class="product-action">
                            <a class="action-quick-view ht-tooltip" data-tippy-content="Quick View" href="{{ route('shop.product', $product->id) }}" title="Quick View">
                              <i class="lastudioicon-search-zoom-in"></i>
                            </a>
                          </div>
                        </div>
                        <div class="product-info">
                          <div class="content-inner">
                            <h4 class="title"><a href="{{ route('shop.product', $product->id) }}">{{ $product->name }}</a></h4>
                            <div class="prices">
                              <span class="price {{ !$product->instock ? 'text-decoration-line-through text-danger' : '' }}">MMK {{ number_format($price, 2) }}</span>
                              @if (!$product->instock)
                                <span class="d-block small text-danger fw-semibold mt-1">Out of stock</span>
                              @endif
                            </div>
                            <p>{{ $product->description ?: 'Explore this item and add it to your cart for a fresh new look.' }}</p>
                            <div class="product-action-btn">
                              <form method="POST" action="{{ route('shop.cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button class="btn-add-cart btn-theme" type="submit">Add to cart</button>
                              </form>
                              <a class="btn-wishlist" href="{{ route('shop.wishlist') }}">
                                <i class="lastudioicon-heart-2"></i>
                              </a>
                              <a class="btn-compare" href="{{ route('shop.compare') }}">
                                <i class="lastudioicon-compare"></i>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            @include('partials.pagination', ['paginator' => $products])
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Product Area Wrapper ==-->
@endsection

@push('styles')
  <link href="{{ asset('assets/css/rangeslider.css') }}" rel="stylesheet"/>
  <style>
    /* Mobile-friendly shop toolbar: compact single row, fewer controls */
    @media (max-width: 991.98px) {
      .shop-toolbar-mobile-wrap {
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px 16px;
        margin-bottom: 24px;
      }
      .shop-toolbar-mobile-wrap .shop-toolbar-left {
        flex: 1 1 auto;
        margin-bottom: 0;
      }
      .shop-toolbar-mobile-wrap .shop-toolbar-left .count-result {
        font-size: 14px;
        line-height: 1.4;
      }
      .shop-toolbar-mobile-wrap .shop-toolbar-right {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        flex: 1 1 auto;
        justify-content: flex-end;
      }
      .shop-toolbar-mobile-wrap .product-sorting-menu .current {
        padding: 8px 12px;
        font-size: 14px;
      }
      .shop-toolbar-mobile-wrap .product-view-mode-mobile {
        margin-top: 0;
      }
      .shop-toolbar-mobile-wrap .product-view-mode-mobile .nav-link {
        padding: 8px 10px;
      }
    }
    @media (max-width: 575.98px) {
      .shop-toolbar-mobile-wrap {
        margin-bottom: 16px;
        gap: 10px 12px;
      }
    }
    /* Mobile: filters between toolbar and products – full width, no card, clean */
    @media (max-width: 991.98px) {
      .shop-filters-inline {
        width: 100%;
        margin: 0 0 20px 0;
        padding: 0;
        background: transparent;
        border: none;
        border-radius: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 16px;
      }
      .shop-filters-inline .shop-filters-inline-content {
        width: 100%;
      }
      .shop-filters-inline .widget-search-inline { margin-bottom: 10px; }
      .shop-filters-inline .widget-search-inline .widget-search-box .form-input-item {
        position: relative;
      }
      .shop-filters-inline .widget-search-inline .widget-search-box input {
        width: 100%;
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 6px;
        padding: 10px 40px 10px 14px;
        font-size: 14px;
        height: 44px;
      }
      .shop-filters-inline .widget-search-inline .widget-search-box input:focus {
        border-color: #333;
        outline: none;
      }
      .shop-filters-inline .widget-search-inline .widget-search-box .btn-src {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        padding: 0;
        color: #666;
        font-size: 16px;
      }
      .shop-filters-inline .categories-details-inline { margin: 0; }
      .shop-filters-inline .categories-summary-inline {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        padding: 8px 0;
        cursor: pointer;
        list-style: none;
      }
      .shop-filters-inline .categories-summary-inline::-webkit-details-marker,
      .shop-filters-inline .categories-summary-inline::marker { display: none; }
      .shop-filters-inline .categories-summary-inline::after {
        content: "";
        float: right;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #666;
        margin-top: 4px;
      }
      .shop-filters-inline .categories-details-inline[open] .categories-summary-inline::after {
        transform: rotate(180deg);
      }
      .shop-filters-inline .widget-custom-menu {
        margin-top: 0;
        padding-top: 6px;
        padding-bottom: 4px;
      }
      .shop-filters-inline .widget-custom-menu ul { margin: 0; padding: 0; list-style: none; }
      .shop-filters-inline .widget-custom-menu li { margin: 0; border: none; }
      .shop-filters-inline .widget-custom-menu li a {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        color: #444;
        font-size: 13px;
        text-decoration: none;
      }
      .shop-filters-inline .widget-custom-menu li a:hover { color: #ff1a1a; }
      .shop-filters-inline .cat-count { font-size: 12px; color: #999; margin-left: 6px; }
      .shop-filters-inline li a:hover .cat-count { color: #ff1a1a; }
    }
    @media (max-width: 575.98px) {
      .shop-filters-inline { margin-bottom: 16px; padding-bottom: 14px; }
      .shop-filters-inline .widget-search-inline .widget-search-box input {
        height: 42px;
        padding: 8px 36px 8px 12px;
        font-size: 13px;
      }
      .shop-filters-inline .categories-summary-inline { padding: 6px 0; font-size: 13px; }
      .shop-filters-inline .widget-custom-menu li a { padding: 4px 0; }
    }
    @media (min-width: 992px) {
      .shop-sidebar-area .categories-details-mobile { display: block; }
      .shop-sidebar-area .categories-details-mobile .widget-custom-menu { display: block !important; }
      .shop-sidebar-area .categories-summary {
        font-size: 20px !important;
        font-weight: 600 !important;
        cursor: default !important;
        list-style: none !important;
        padding: 0 0 20px 0 !important;
        margin: 0 !important;
      }
      .shop-sidebar-area .categories-summary::-webkit-details-marker,
      .shop-sidebar-area .categories-summary::marker { display: none !important; }
      .shop-sidebar-area .categories-summary::after { display: none !important; }
    }
    /* Match .product-action a styles for add-to-cart button (no wrapper so class works) */
    .product-item .product-thumb .product-action button.action-cart {
      background-color: #fff;
      border: none;
      color: #333;
      cursor: pointer;
      display: inline-block;
      font-size: 16px;
      height: 44px;
      line-height: 44px;
      margin: 0 2.5px;
      opacity: 0;
      padding: 0;
      position: relative;
      text-align: center;
      visibility: hidden;
      width: 44px;
      transform: translate(0, 40px);
      transition: all 0.3s ease;
    }
    .product-item .product-thumb .product-action button.action-cart i {
      margin: 0;
    }
    .product-item .product-thumb .product-action.action-style3 button.action-cart {
      background-color: #333;
      color: #fff;
    }
    .product-item .product-thumb .product-action.action-style3 button.action-cart:hover {
      background-color: #A64637;
      color: #fff;
    }
    .product-item:hover .product-thumb .product-action button.action-cart {
      opacity: 1;
      visibility: visible;
      transform: translate(0, 0);
    }
  </style>
@endpush

@push('scripts')
  <script src="{{ asset('assets/js/rangeSlider.js') }}"></script>
  <script>
    (function () {
      var details = document.querySelector('.categories-details-mobile');
      if (details && window.innerWidth >= 992) {
        details.setAttribute('open', '');
      }
    })();
  </script>
@endpush

