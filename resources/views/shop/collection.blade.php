@extends('layouts.app')

@section('title', $collection->name . ' - AliarLIE Clothing')

@section('wrapper-class', 'page-Shop-wrapper')

@section('content')
  @php
      $bannerImage = $banner?->image
          ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image)
          : ($collection->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($collection->image) : asset('assets/img/photos/bg-page1.jpg'));
  @endphp

  <!--== Start Page Title Area ==-->
  <section class="page-title-area bg-img" data-bg-img="{{ $bannerImage }}">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="page-title-content">
            <h2 class="title">{{ $collection->name }}</h2>
            <div class="bread-crumbs">
              <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
              <a href="{{ route('shop.stories') }}">Stories<span class="breadcrumb-sep">></span></a>
              <span class="active">{{ $collection->name }}</span>
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
      @if($collection->description)
        <div class="row mb-5">
          <div class="col-12">
            <div class="collection-description text-center">
              <p>{{ $collection->description }}</p>
            </div>
          </div>
        </div>
      @endif
      <div class="row">
        <div class="col-lg-12">
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
                      <p class="text-center">No products available in this collection yet.</p>
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
  <style>
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
