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
  <section class="product-area shop-single-product mt-5">
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

@push('styles')
  <style>
    /* Product page: quantity + Add to cart – compact and clean */
    .single-product-info .quick-product-action .action-top {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }
    .single-product-info .quick-product-action .action-top .pro-qty-area {
      margin-right: 0;
      border: 1px solid #e0e0e0;
      border-radius: 6px;
      overflow: hidden;
      background: #fff;
    }
    .single-product-info .quick-product-action .action-top .pro-qty-area .pro-qty {
      min-height: 38px;
    }
    .single-product-info .quick-product-action .action-top .pro-qty-area .pro-qty input {
      width: 64px;
      height: 38px;
      font-size: 14px;
      font-weight: 500;
      padding: 0 26px;
      border: none;
      background: transparent;
      color: #333;
      text-align: center;
      -moz-appearance: textfield;
    }
    .single-product-info .quick-product-action .action-top .pro-qty-area .pro-qty input::-webkit-outer-spin-button,
    .single-product-info .quick-product-action .action-top .pro-qty-area .pro-qty input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    .single-product-info .quick-product-action .action-top .pro-qty-area .pro-qty .qty-btn {
      font-size: 15px;
      line-height: 38px;
      width: 26px;
      height: 38px;
      color: #666;
      text-decoration: none;
      transition: color 0.2s, background 0.2s;
    }
    .single-product-info .quick-product-action .action-top .pro-qty-area .pro-qty .qty-btn:hover {
      color: #333;
      background: #f5f5f5;
    }
    .single-product-info .quick-product-action .action-top .btn-theme.btn-black {
      font-size: 14px;
      font-weight: 500;
      line-height: 1.35;
      padding: 10px 28px;
      min-width: 160px;
      height: 38px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      border: none;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      transition: background-color 0.2s, box-shadow 0.2s;
    }
    .single-product-info .quick-product-action .action-top .btn-theme.btn-black:hover {
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }
    @media (max-width: 575.98px) {
      .single-product-info .quick-product-action .action-top {
        gap: 10px;
        margin-bottom: 20px;
      }
      .single-product-info .quick-product-action .action-top .btn-theme.btn-black {
        min-width: 100%;
        flex: 1 1 100%;
      }
    }
  </style>
@endpush
