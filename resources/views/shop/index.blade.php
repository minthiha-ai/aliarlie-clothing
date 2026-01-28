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
        <div class="col-lg-3 order-1 order-lg-0">
          <div class="sidebar-area inner-right-padding shop-sidebar-area">
            <div class="widget">
              <div class="widget-search-box">
                <form action="{{ route('shop.index') }}" method="get">
                  <div class="form-input-item">
                    <label for="search2" class="sr-only">Search Here</label>
                    <input type="text" id="search2" name="search" placeholder="Search entire store…" value="{{ request('search') }}">
                    <button type="submit" class="btn-src">
                      <i class="icofont-search-1"></i>
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="widget">
              <h3 class="widget-title">Categories</h3>
              <div class="widget-custom-menu">
                <ul>
                  @forelse ($categories as $category)
                    <li>
                      <a href="{{ route('shop.index', ['category' => $category->id]) }}">
                        {{ $category->name }}
                      </a>
                      <span>({{ $category->products_count }})</span>
                    </li>
                  @empty
                    <li>No categories yet.</li>
                  @endforelse
                </ul>
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
        <div class="col-lg-9 order-0 order-lg-1">
          <div class="inner-left-padding">
            <div class="shop-toolbar-wrap">
              <div class="shop-toolbar-left">
                <div class="product-showing-status">
                  <p class="count-result">
                    Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results
                  </p>
                </div>
              </div>
              <div class="shop-toolbar-right">
                <div class="product-sorting-menu product-view-count">
                  <span class="current">Show 12 <i class="lastudioicon-down-arrow"></i></span>
                  <ul>
                    <li class="active"><a href="{{ route('shop.index') }}" class="active">Show 12</a></li>
                    <li><a href="{{ route('shop.index') }}">Show 15</a></li>
                    <li><a href="{{ route('shop.index') }}">Show 30</a></li>
                  </ul>
                </div>
                <div class="product-sorting-menu product-view-count">
                  <span class="current">Filters <i class="lastudioicon-down-arrow"></i></span>
                  <ul>
                    <li class="active"><a href="{{ route('shop.index') }}" class="active">Filters</a></li>
                    <li><a href="{{ route('shop.index') }}">Categories</a></li>
                    <li><a href="{{ route('shop.index') }}">Tags</a></li>
                  </ul>
                </div>
                <div class="product-sorting-menu product-sorting">
                  <span class="current">Sort by Default <i class="lastudioicon-down-arrow"></i></span>
                  <ul>
                    <li class="active"><a href="{{ route('shop.index') }}" class="active">Sort by Default</a></li>
                    <li><a href="{{ route('shop.index') }}">Sort by Popularity</a></li>
                    <li><a href="{{ route('shop.index') }}">Sort by Rated</a></li>
                    <li><a href="{{ route('shop.index') }}">Sort by Latest</a></li>
                  </ul>
                </div>
                <div class="product-view-mode">
                  <nav>
                    <div class="nav nav-tabs active" id="nav-tab" role="tablist">
                      <button class="nav-link" id="nav-list-tab" data-bs-toggle="tab" data-bs-target="#nav-list" type="button" role="tab" aria-controls="nav-list" aria-selected="false"><i class="lastudioicon-list-bullet-2"></i></button>
                      <button class="nav-link active" id="nav-grid-tab" data-bs-toggle="tab" data-bs-target="#nav-grid" type="button" role="tab" aria-controls="nav-grid" aria-selected="true"><i class="lastudioicon-microsoft"></i></button>
                    </div>
                  </nav>
                </div>
              </div>
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
                            <a class="action-quick-view ht-tooltip" data-tippy-content="Quick View" href="javascript:void(0);" title="Quick View">
                              <i class="lastudioicon-search-zoom-in"></i>
                            </a>
                            <a class="action-wishlist ht-tooltip" data-tippy-content="Add to wishlist" href="{{ route('shop.wishlist') }}" title="Add to wishlist">
                              <i class="lastudioicon-heart-2"></i>
                            </a>
                            <a class="action-compare ht-tooltip" data-tippy-content="Add to compare" href="{{ route('shop.compare') }}" title="Add to compare">
                              <i class="lastudioicon-compare"></i>
                            </a>
                          </div>
                        </div>
                        <div class="product-info info-style2">
                          <div class="content-inner">
                            <h4 class="title"><a href="{{ route('shop.product', $product->id) }}">{{ $product->name }}</a></h4>
                            <div class="prices">
                              <span class="price">${{ number_format($price, 2) }}</span>
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
                              <span class="price">${{ number_format($price, 2) }}</span>
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
@endpush

@push('scripts')
  <script src="{{ asset('assets/js/rangeSlider.js') }}"></script>
@endpush

