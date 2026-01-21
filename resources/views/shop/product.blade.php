@extends('layouts.app')

@section('title', $product->name.' - AliarLIE Clothing')

@section('wrapper-class', 'home-default-wrapper')

@section('content')
  @php
    $mainImage = $product->images->first();
    $bannerImage = $banner?->image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image)
        : null;
    $pageTitleClasses = $bannerImage ? 'page-title-area page-title-area2 bg-img' : 'page-title-area page-title-area2';
  @endphp

  <!--== Start Page Title Area ==-->
  <div class="{{ $pageTitleClasses }}" @if ($bannerImage) data-bg-img="{{ $bannerImage }}" @endif>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="page-title-content content-style-2">
            <div class="bread-crumbs">
              <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
              @if ($product->category)
                <a href="{{ route('shop.index') }}">{{ $product->category->name }}<span class="breadcrumb-sep">></span></a>
              @endif
              <span class="active">{{ $product->name }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--== End Page Title Area ==-->

  <!--== Start Shop Area ==-->
  <section class="product-area shop-single-product">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="single-product-slider">
            <div class="product-dec-slider-right">
              <div class="single-product-thumb">
                <div class="single-product-thumb-slider">
                  <div class="zoom zoom-hover">
                    <div class="thumb-item">
                      <a class="lightbox-image" data-fancybox="gallery" href="{{ $mainImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($mainImage->image_path) : asset('assets/img/shop/details/6.jpg') }}">
                        <img src="{{ $mainImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($mainImage->image_path) : asset('assets/img/shop/details/6.jpg') }}" alt="{{ $product->name }}">
                      </a>
                    </div>
                  </div>
                  @foreach ($product->images->skip(1) as $image)
                    <div class="zoom zoom-hover">
                      <div class="thumb-item">
                        <a class="lightbox-image" data-fancybox="gallery" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_path) }}">
                          <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_path) }}" alt="{{ $product->name }}">
                        </a>
                      </div>
                    </div>
                  @endforeach
                </div>
                <div class="product-gallery-actions">
                  <a class="lightbox-image" data-fancybox="gallery" href="{{ $mainImage ? \Illuminate\Support\Facades\Storage::disk('public')->url($mainImage->image_path) : asset('assets/img/shop/details/6.jpg') }}">
                    <i class="lastudioicon-full-screen"></i>
                  </a>
                </div>
              </div>
            </div>
            <div class="product-dec-slider-left">
              <div class="single-product-nav">
                <div class="single-product-nav-slider">
                  @foreach ($product->images as $image)
                    <div class="nav-item">
                      <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_path) }}" alt="{{ $product->name }}">
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="single-product-info">
            <h4 class="title">{{ $product->name }}</h4>
            <div class="product-rating">
              <div class="review">
                @php
                  $stockCount = $product->variants->sum(fn ($variant) => (int) optional($variant->stock)->quantity);
                @endphp
                <p><span></span>{{ $stockCount }} in stock</p>
              </div>
            </div>
            <div class="prices">
              @if ($product->discount_price)
                <span class="price">${{ number_format($product->discount_price, 2) }}</span>
                <span class="price-old">${{ number_format($product->price, 2) }}</span>
              @else
                <span class="price">${{ number_format($product->price, 2) }}</span>
              @endif
            </div>
            @if ($product->description)
              <p class="product-desc mb-30">{{ $product->description }}</p>
            @endif

            <form class="quick-product-action" method="POST" action="{{ route('shop.cart.add') }}">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <div class="action-top">
                <div class="pro-qty-area">
                  <div class="pro-qty">
                    <input type="number" name="quantity" title="Quantity" value="1" min="1">
                  </div>
                </div>
                <button class="btn-theme btn-black" type="submit">Add to cart</button>
              </div>

              @if ($product->variants->isNotEmpty())
                <div class="action-bottom mt-3">
                  <label class="d-block mb-2">Choose Variant</label>
                  <select name="variant_id" class="form-select" required>
                    <option value="">Select size / color</option>
                    @foreach ($product->variants as $variant)
                      @php
                        $inStock = optional($variant->stock)->quantity > 0;
                      @endphp
                      <option value="{{ $variant->id }}" {{ $inStock ? '' : 'disabled' }}>
                        {{ $variant->size }} / {{ $variant->color }} {{ $inStock ? '' : '(Out of stock)' }}
                      </option>
                    @endforeach
                  </select>
                </div>
              @endif
            </form>

            <div class="action-bottom mt-3">
              <a class="btn-wishlist" href="{{ route('shop.wishlist') }}"><i class="labtn-icon labtn-icon-wishlist"></i>Add to wishlist</a>
              <a class="btn-compare" href="{{ route('shop.compare') }}"><i class="labtn-icon labtn-icon-compare"></i>Add to compare</a>
            </div>

            @if ($product->category)
              <div class="product-categorys mt-4">
                <div class="product-category">
                  Category: <span>{{ $product->category->name }}</span>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Shop Area ==-->

  @if ($relatedProducts->isNotEmpty())
    <section class="product-area related-products-area">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 m-auto">
            <div class="section-title text-center">
              <h2 class="title">Related Products</h2>
            </div>
          </div>
        </div>
        <div class="row row-gutter-60">
          @foreach ($relatedProducts as $related)
            @php
              $image = $related->images->first();
              $price = $related->discount_price ?? $related->price;
            @endphp
            <div class="col-sm-6 col-lg-3">
              <div class="product-item">
                <div class="product-thumb">
                  <a href="{{ route('shop.product', $related->id) }}">
                    <img src="{{ $image ? \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_path) : asset('assets/img/shop/d1-1.jpg') }}" alt="{{ $related->name }}">
                    <span class="thumb-overlay"></span>
                  </a>
                </div>
                <div class="product-info info-style3">
                  <div class="content-inner">
                    <h4 class="title"><a href="{{ route('shop.product', $related->id) }}">{{ $related->name }}</a></h4>
                    <div class="prices">
                      <span class="price">${{ number_format($price, 2) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif
@endsection
