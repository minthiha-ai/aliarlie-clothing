@extends('layouts.app')

@section('title', 'Home - AliarLIE Clothing')

@section('wrapper-class', 'home-default-wrapper')

@section('content')
<!--== Start Hero Area Wrapper ==-->
<section class="home-slider-area slider-default">
  <div class="home-slider-content">
    <div class="swiper-container home-slider-container">
      <div class="swiper-wrapper">
        @forelse ($banners as $banner)
          <div class="swiper-slide">
            <!-- Start Slide Item -->
            @php
              $bannerLink = $banner->product_id && $banner->relationLoaded('product') && $banner->product
                  ? route('shop.product', $banner->product->id)
                  : null;
            @endphp
            <div class="home-slider-item {{ $bannerLink ? 'home-slider-item--linked' : '' }}">
              @if ($bannerLink)
                <a href="{{ $bannerLink }}" class="home-slider-item__link stretched-link" aria-label="{{ $banner->product->name ?? 'View product' }}"></a>
              @endif
              <div class="bg-thumb bg-overlay bg-img" data-bg-img="{{ $banner->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image) : asset('assets/img/slider/h1-s1.jpg') }}"></div>
              <div class="slider-content-area">
                <div class="container">
                  <div class="row align-items-center">
                    <div class="col-md-8 col-lg-5 m-auto">
                      <div class="content">
                        <div class="inner-content">
                          <h3 class="slider-title">{{ $banner->title ?? 'NEW IN' }}</h3>
                          @if ($banner->description)
                            <p class="slider-desc">{{ $banner->description }}</p>
                          @endif
                          @if ($bannerLink)
                            <a href="{{ $bannerLink }}" class="hero-cta-btn">View product</a>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Slide Item -->
          </div>
        @empty
          <div class="swiper-slide">
            <div class="home-slider-item">
              <div class="bg-thumb bg-overlay bg-img" data-bg-img="{{ asset('assets/img/slider/h1-s1.jpg') }}"></div>
              <div class="slider-content-area">
                <div class="container">
                  <div class="row align-items-center">
                    <div class="col-md-8 col-lg-5 m-auto">
                      <div class="content">
                        <div class="inner-content">
                          <h3 class="slider-title">NEW IN</h3>
                          <p class="slider-desc">Discover the latest styles from Aliarlie.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforelse
      </div>
      <!-- Add Arrows -->
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
      <!-- Add Pagination -->
      <div class="swiper-pagination"></div>
    </div>
  </div>
</section>
<!--== End Hero Area Wrapper ==-->

<!--== Start Infos Area (story-style, no slider) ==-->
<section class="infos-area">
  <div class="infos-content">
    @forelse ($infos as $info)
      <div class="infos-item">
        <div class="thumb infos-thumb">
          <div class="bg-thumb bg-overlay bg-img" data-bg-img="{{ $info->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($info->image) : asset('assets/img/slider/cs1-s1.jpg') }}"></div>
        </div>
        <div class="infos-content-area">
          <div class="content">
            <div class="inner-content">
              <h2>{{ $info->title }}</h2>
              @if ($info->description)
                <p>{{ \Illuminate\Support\Str::limit($info->description, 120) }}</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="infos-item">
        <div class="thumb infos-thumb">
          <div class="bg-thumb bg-overlay bg-img" data-bg-img="{{ asset('assets/img/slider/cs1-s1.jpg') }}"></div>
        </div>
        <div class="infos-content-area">
          <div class="content">
            <div class="inner-content">
              <h2>Welcome</h2>
              <p>Add content from Admin → Infos.</p>
            </div>
          </div>
        </div>
      </div>
    @endforelse
  </div>
</section>
<!--== End Infos Area ==-->

<!--== Start Products Area Wrapper ==-->
<section class="product-area best-sellers-product-area">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-lg-6 m-auto">
        <div class="section-title text-center" data-aos="fade-up" data-aos-duration="1000">
          <h2 class="title">Best Sellers</h2>
          <h5 class="subtitle">COLLECTION 2021</h5>
        </div>
      </div>
    </div>
    <div class="row row-gutter-60" data-aos="fade-up" data-aos-duration="1000">
      @forelse ($bestSellers as $product)
        @php
          $image = $product->images->first();
          $price = $product->discount_price ?? $product->price;
        @endphp
        <div class="col-sm-6 col-lg-4">
          <!-- Start Product Item -->
          <div class="product-item">
            <div class="product-thumb">
              <a href="{{ route('shop.product', $product->id) }}">
                <img src="{{ $image ? \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_path) : asset('assets/img/shop/d1-1.jpg') }}" alt="{{ $product->name }}">
                <span class="thumb-overlay"></span>
              </a>
              {{-- <div class="product-action">
                <a class="action-quick-view ht-tooltip" data-tippy-content="Quick View" href="javascript:void(0);" title="Wishlist">
                  <i class="lastudioicon-search-zoom-in"></i>
                </a>
                <a class="action-compare ht-tooltip" data-tippy-content="Add to compare" href="{{ route('shop.compare') }}" title="Add to compare">
                  <i class="lastudioicon-compare"></i>
                </a>
              </div> --}}
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
              </div>
              <div class="product-info-action">
                {{-- <a class="action-wishlist ht-tooltip" data-tippy-content="Add to wishlist" href="{{ route('shop.wishlist') }}" title="Add to wishlist">
                  <i class="lastudioicon-heart-2"></i>
                </a> --}}
                <button
                  class="action-cart ht-tooltip js-add-to-cart"
                  data-tippy-content="Add to cart"
                  data-product-id="{{ $product->id }}"
                  title="Add to cart"
                  type="button"
                >
                  <i class="lastudioicon-bag-3"></i>
                </button>
              </div>
            </div>
          </div>
          <!-- End Product Item -->
        </div>
      @empty
        <div class="col-12">
          <p class="text-center">No products available yet.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>
<!--== End Products Area Wrapper ==-->

<!--== Start Featured Area Wrapper ==-->
<section class="featured-area">
  <div class="container" data-aos="fade-up" data-aos-duration="1000">
    <div class="row">
      <div class="col-sm-6 col-md-4">
        <div class="featured-item">
          <div class="content">
            <span class="icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="76" height="46" fill="none" viewBox="0 0 76 46"><path fill="currentColor" d="M74.757 45.702H1.243a1.08 1.08 0 0 1-1.08-1.08V33.593a1.081 1.081 0 0 1 .864-1.06 9.73 9.73 0 0 0 0-19.07 1.081 1.081 0 0 1-.865-1.059V1.378A1.081 1.081 0 0 1 1.243.297h73.514a1.08 1.08 0 0 1 1.08 1.081v11.027a1.082 1.082 0 0 1-.864 1.06 9.73 9.73 0 0 0 0 19.07 1.081 1.081 0 0 1 .865 1.06V44.62a1.08 1.08 0 0 1-1.081 1.081zM2.324 43.54h71.352v-9.097a11.892 11.892 0 0 1 0-22.887V2.46H2.324v9.097a11.892 11.892 0 0 1 0 22.887v9.097z"></path></svg>
            </span>
            <div class="inner-content">
              <h4 class="title">NEW DISCOUNT</h4>
              <p>Lorem ipsum dolor sit amet, id pericula appe llantur eam, mea.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="featured-item mt-xs-30">
          <div class="content">
            <span class="icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="76" height="72" fill="none" viewBox="0 0 76 72"><path fill="currentColor" d="M64.733 71.123H11.267a4.432 4.432 0 0 1-4.422-4.42V31.614a1.081 1.081 0 0 1 1.081-1.08h60.147a1.081 1.081 0 0 1 1.082 1.08v35.089a4.432 4.432 0 0 1-4.422 4.42zM9.008 32.695v34.008a2.263 2.263 0 0 0 2.26 2.26h53.465a2.262 2.262 0 0 0 2.26-2.26V32.695H9.007z"></path></svg>
            </span>
            <div class="inner-content">
              <h4 class="title">GIFT VOUCHERS</h4>
              <p>Lorem ipsum dolor sit amet, id pericula appe llantur eam, mea.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="featured-item mt-sm-30">
          <div class="content">
            <span class="icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="76" height="74" fill="none" viewBox="0 0 76 74"><path fill="currentColor" d="M74.757 73.649H1.243a1.08 1.08 0 0 1-1.08-1.081V20.4a1.081 1.081 0 0 1 1.08-1.081h73.514a1.08 1.08 0 0 1 1.08 1.081v52.168a1.08 1.08 0 0 1-1.08 1.08zM2.324 71.487h71.352V21.48H2.324v50.006z"></path></svg>
            </span>
            <div class="inner-content">
              <h4 class="title">FREE DELIVERY</h4>
              <p>Lorem ipsum dolor sit amet, id pericula appe llantur eam, mea.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--== End Featured Area Wrapper ==-->
@endsection

@push('styles')
  <style>
    /* Hero slider: transparent content area (no white bg) */
    .home-slider-area.slider-default .slider-content-area,
    .home-slider-area.slider-default .slider-content-area .container,
    .home-slider-area.slider-default .slider-content-area .content,
    .home-slider-area.slider-default .slider-content-area .content .inner-content {
      background: transparent !important;
    }
    /* Hero slider: h3-size title, hide decorative lines */
    .home-slider-area.slider-default .slider-content-area .content .inner-content .slider-title {
      color: #fff;
      font-family: "Abril Fatface", cursive;
      font-size: 1.75rem;
      font-weight: 400;
      line-height: 1.3;
      margin-bottom: 0.5rem;
      text-transform: uppercase;
    }
    .home-slider-area.slider-default .slider-content-area .content .inner-content .slider-title::after {
      display: none !important;
    }
    .home-slider-area.slider-default .slider-content-area .content .inner-content .slider-desc {
      color: #fff;
      font-size: 1.1rem;
      margin: 0 auto;
    }
    .home-slider-area.slider-default .slider-content-area .content .inner-content .slider-desc::after {
      display: none !important;
    }
    /* Hero "View product" CTA button - group hover: hover anywhere on slide to animate button */
    .home-slider-area.slider-default .hero-cta-btn {
      position: relative;
      z-index: 2;
      display: inline-block;
      margin-top: 1rem;
      padding: 0.5rem 1.75rem;
      font-size: 0.875rem;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #fff;
      background: transparent;
      border: 2px solid #e53935;
      border-radius: 4px;
      text-decoration: none;
      transition: color 0.25s ease, background 0.25s ease, border-color 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
    }
    .home-slider-area.slider-default .home-slider-item--linked:hover .hero-cta-btn,
    .home-slider-area.slider-default .hero-cta-btn:hover,
    .home-slider-area.slider-default .hero-cta-btn:focus {
      color: #fff;
      background: #e53935;
      border-color: #e53935;
      transform: translateY(-3px) scale(1.03);
      box-shadow: 0 8px 24px rgba(229, 57, 53, 0.4);
    }
    /* Linked banner slide: whole slide clickable to product */
    .home-slider-item--linked {
      position: relative;
    }
    .home-slider-item__link.stretched-link {
      position: absolute;
      inset: 0;
      z-index: 1;
    }
    @media (min-width: 576px) {
      .home-slider-area.slider-default .slider-content-area .content .inner-content .slider-title {
        font-size: 2.25rem;
      }
      .home-slider-area.slider-default .slider-content-area .content .inner-content .slider-desc {
        font-size: 1.15rem;
      }
    }
    @media (min-width: 992px) {
      .home-slider-area.slider-default .slider-content-area .content .inner-content .slider-title {
        font-size: 2.75rem;
      }
    }
    /* Match .product-info-action a styles for add-to-cart button */
    .product-item .product-info-action button.action-cart {
      background: none;
      border: none;
      color: inherit;
      cursor: pointer;
      font-size: 18px;
      line-height: 20px;
      margin: 2.5px;
      padding: 0;
      vertical-align: middle;
      transition: color 0.2s ease, transform 0.2s ease;
    }
    .product-item .product-info-action button.action-cart:last-child {
      margin-right: 0;
    }
    .product-item .product-info-action button.action-cart:hover {
      color: #ff1a1a;
      transform: scale(1.15);
    }
    /* Product item: image zoom on hover */
    .best-sellers-product-area .product-item .product-thumb {
      overflow: hidden;
    }
    .best-sellers-product-area .product-item .product-thumb img {
      transition: transform 0.4s ease;
    }
    .best-sellers-product-area .product-item:hover .product-thumb img {
      transform: scale(1.08);
    }
    .best-sellers-product-area .product-item .product-info .title a {
      transition: color 0.2s ease;
    }
    .best-sellers-product-area .product-item .product-info .title a:hover {
      color: #e53935;
    }
    /* Infos area: story-style stacked blocks, content on right, transparent bg */
    .infos-area {
      width: 100vw;
      max-width: 100vw;
      margin-left: calc(50% - 50vw);
      margin-right: calc(50% - 50vw);
      margin-top: 2rem;
      padding: 0 0 4rem;
    }
    .infos-area .infos-content {
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }
    .infos-area .infos-item {
      display: flex;
      align-items: center;
      height: 800px;
      position: relative;
    }
    @media (max-width: 1199px) {
      .infos-area .infos-item {
        height: 570px;
      }
    }
    @media (max-width: 767px) {
      .infos-area {
        padding: 0 0 3rem;
      }
      .infos-area .infos-content {
        gap: 3rem;
      }
      .infos-area .infos-item {
        height: 470px;
        flex-direction: column;
      }
    }
    .infos-area .infos-item .infos-thumb {
      height: 100%;
      overflow: hidden;
      position: absolute;
      width: 100%;
      left: 0;
      right: 0;
      top: 0;
      z-index: 0;
    }
    .infos-area .infos-item .infos-thumb .bg-thumb,
    .infos-area .infos-item .infos-thumb .bg-img {
      background-position: center;
      background-size: cover;
      height: 100%;
      width: 100%;
    }
    @media (max-width: 767px) {
      .infos-area .infos-item .infos-thumb {
        position: relative;
        width: 100%;
        height: 50%;
      }
    }
    .infos-area .infos-content-area {
      background: transparent !important;
      box-shadow: none !important;
      position: absolute;
      left: 0;
      right: auto;
      top: 0;
      bottom: 0;
      margin: 0;
      max-width: 520px;
      width: 100%;
      padding: 3rem 2.5rem 3rem 3rem;
      z-index: 1;
      display: flex;
      align-items: center;
    }
    .infos-area .infos-content-area .content,
    .infos-area .infos-content-area .content .inner-content {
      background: transparent !important;
    }
    .infos-area .infos-content-area .content {
      width: 100%;
    }
    .infos-area .infos-content-area .content .inner-content h2 {
      color: #fff;
      font-size: 1.75rem;
      font-weight: 600;
      letter-spacing: -0.02em;
      line-height: 1.25;
      margin-bottom: 0;
      padding-bottom: 0.75rem;
      border-bottom: 3px solid #e53935;
      display: inline-block;
    }
    .infos-area .infos-content-area .content .inner-content p {
      color: rgba(255, 255, 255, 0.92);
      font-size: 0.95rem;
      line-height: 1.65;
      margin-top: 1rem;
      margin-bottom: 0;
    }
    @media (max-width: 1199px) {
      .infos-area .infos-content-area {
        max-width: 48%;
        padding: 2rem 1.75rem;
      }
    }
    @media (max-width: 575px) {
      .infos-area .infos-content-area .content .inner-content h2 {
        font-size: 1.4rem;
      }
      .infos-area .infos-content-area .content .inner-content p {
        font-size: 0.9rem;
      }
    }
    @media (max-width: 767px) {
      .infos-area .infos-content-area {
        position: relative;
        max-width: 100%;
        padding: 2rem 1.25rem;
      }
    }
    /* Featured items: hover lift and icon animation */
    .featured-area .featured-item {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .featured-area .featured-item:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
    }
    .featured-area .featured-item .icon {
      display: inline-block;
      transition: transform 0.3s ease, color 0.3s ease;
    }
    .featured-area .featured-item:hover .icon {
      transform: scale(1.08);
      color: #e53935;
    }
  </style>
@endpush

@push('scripts')
  <script>
    document.addEventListener('click', function (event) {
      var button = event.target.closest('.js-add-to-cart');
      if (!button) {
        return;
      }

      event.preventDefault();

      if (button.dataset.loading === 'true') {
        return;
      }

      button.dataset.loading = 'true';

      var productId = button.getAttribute('data-product-id');
      var tokenElement = document.querySelector('meta[name="csrf-token"]');
      var token = tokenElement ? tokenElement.getAttribute('content') : '';

      fetch("{{ route('shop.cart.add') }}", {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
        },
        credentials: 'same-origin',
        body: JSON.stringify({ product_id: productId, quantity: 1 }),
      })
        .then(function (response) {
          if (!response.ok) {
            throw response;
          }
          return response.json();
        })
        .then(function (data) {
          var cartCount = document.querySelector('.cart-count');
          if (cartCount && typeof data.cart_count !== 'undefined') {
            cartCount.textContent = data.cart_count;
          }

          var sidebarCart = document.querySelector('.js-sidebar-cart');
          if (sidebarCart && data.sidebar_cart) {
            sidebarCart.innerHTML = data.sidebar_cart;
          }
        })
        .catch(function () {
          window.alert('Unable to add to cart right now.');
        })
        .finally(function () {
          button.dataset.loading = 'false';
        });
    });
  </script>
@endpush
