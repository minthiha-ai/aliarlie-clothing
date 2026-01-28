<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AliarLIE Clothing')</title>

    <!--== Favicon ==-->
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    <!--== Google Fonts ==-->
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface:400" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,400i,500,600,700,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,700,900,900i" rel="stylesheet">

    <!--== Bootstrap CSS ==-->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <!--== Font-awesome Icons CSS ==-->
    <link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet"/>
    <!--== Icofont Min Icons CSS ==-->
    <link href="{{ asset('assets/css/icofont.min.css') }}" rel="stylesheet"/>
    <!--== lastudioIcons CSS ==-->
    <link href="{{ asset('assets/css/lastudioIcons.css') }}" rel="stylesheet"/>
    <!--== Animate CSS ==-->
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet"/>
    <!--== Aos CSS ==-->
    <link href="{{ asset('assets/css/aos.css') }}" rel="stylesheet"/>
    <!--== FancyBox CSS ==-->
    <link href="{{ asset('assets/css/jquery.fancybox.min.css') }}" rel="stylesheet"/>
    <!--== Slicknav CSS ==-->
    <link href="{{ asset('assets/css/slicknav.css') }}" rel="stylesheet"/>
    <!--== Swiper CSS ==-->
    <link href="{{ asset('assets/css/swiper.min.css') }}" rel="stylesheet"/>
    <!--== Slick CSS ==-->
    <link href="{{ asset('assets/css/slick.css') }}" rel="stylesheet"/>
    <!--== Main Style CSS ==-->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />

    <style>html, body { margin: 0; padding: 0; }
    /* Solid header overrides – only when NOT transparent, and :not(.sticky) so sticky keeps position:fixed */
    .header-area.header-default.header-style2:not(.header-transparent):not(.sticky) { background-color: #fff; box-shadow: 0 0 30px rgba(0,0,0,0.1); }
    .header-area.header-default.header-style2:not(.header-transparent) .header-navigation-area .main-menu > li > a { color: #333; }
    .header-area.header-default.header-style2:not(.header-transparent) .header-navigation-area .main-menu > li > a:after { color: #333; }
    .header-area.header-default.header-style2:not(.header-transparent) .header-action-area .btn-search,
    .header-area.header-default.header-style2:not(.header-transparent) .header-action-area .btn-login,
    .header-area.header-default.header-style2:not(.header-transparent) .header-action-area .btn-cart,
    .header-area.header-default.header-style2:not(.header-transparent) .header-action-area .btn-menu { color: #333; }
    .header-area.header-default.header-style2:not(.header-transparent) .header-action-area .btn-cart .cart-count { color: #fff; }
    .header-area.header-default.header-style2:not(.header-transparent) .header-action-area .search-form .form-input-item input { color: #757575; border-color: #ddd; }
    .header-area.header-default.header-style2:not(.header-transparent) .header-action-area .search-form .form-input-item input::placeholder { color: #757575; }</style>
    @stack('styles')
</head>

<body>

<!--wrapper start-->
<div class="wrapper @yield('wrapper-class', 'home-default-wrapper')">

  <!--== Start Preloader Content ==-->
  @hasSection('preloader')
    @yield('preloader')
  @else
    @include('partials.preloader', [
      'preloaderVariant' => 'logo',
      'preloaderLogo' => asset('assets/img/logo-main.png'),
    ])
  @endif
  <!--== End Preloader Content ==-->

  <!--== Start Header Wrapper ==-->
  @php
    $isHome = request()->routeIs('home');
  @endphp
  <header class="header-area header-default header-style2 {{ $isHome ? 'header-transparent' : '' }} sticky-header">
    <div class="container-fluid">
      <div class="row row-gutter-0 align-items-center">
        <div class="col-12">
          <div class="header-align">
            <div class="header-align-left">
              <div class="header-logo-area">
                <a href="{{ route('home') }}">
                  @if($isHome)
                    <img class="logo-main" src="{{ asset('assets/img/logo-light.png') }}" alt="ALIARLIE Logo" />
                    <img class="logo-light" src="{{ asset('assets/img/logo-main.png') }}" alt="ALIARLIE Logo" />
                  @else
                    <img class="logo-main" src="{{ asset('assets/img/logo-main.png') }}" alt="ALIARLIE Logo" />
                    <img class="logo-light" src="{{ asset('assets/img/logo-main.png') }}" alt="ALIARLIE Logo" />
                  @endif
                </a>
              </div>
              <div class="header-navigation-area d-none d-xl-block">
                <ul class="main-menu nav justify-content-center position-relative">
                  <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}">Home</a>
                  </li>
                  <li class="{{ request()->routeIs('shop.*') ? 'active' : '' }}">
                    <a href="{{ route('shop.index') }}">Shop</a>
                  </li>
                  <li class="{{ request()->routeIs('shop.collections') ? 'active' : '' }}">
                    <a href="{{ route('shop.collections') }}">Collection</a>
                  </li>
                  <li class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                    <a href="{{ route('contact') }}">Contact</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="header-align-right">
              <div class="header-action-area">
                <div class="header-action-search-form">
                  <div class="search-form">
                    <form action="{{ route('shop.index') }}" method="get">
                      <div class="form-input-item">
                        <label for="action-search" class="sr-only">Search</label>
                        <input type="text" id="action-search" name="search" placeholder="Search" value="{{ request('search') }}">
                      </div>
                    </form>
                  </div>
                </div>
                <div class="header-action-search">
                  <button class="btn-search btn-search-menu">
                    <i class="lastudioicon-zoom-1"></i>
                  </button>
                </div>
                @auth('customer')
                  <div class="header-action-login">
                    <a href="{{ route('shop.account') }}" class="btn-login" title="My Account">
                      <i class="lastudioicon-single-01-2"></i>
                    </a>
                  </div>
                @else
                  <div class="header-action-login">
                    <a href="{{ route('shop.account') }}" class="btn-login" title="Login">
                      <i class="lastudioicon-single-01-2"></i>
                    </a>
                  </div>
                @endauth
                <div class="header-action-cart">
                  <a href="javascript:void(0);" class="btn-cart cart-icon" title="Shopping Cart">
                    <span class="cart-count">{{ session('cart_count', 0) }}</span>
                    <i class="lastudioicon-shopping-cart-2"></i>
                  </a>
                </div>
                @guest('customer')
                  <div class="header-action-account d-none d-xxl-block">
                    <a href="{{ route('shop.account') }}" class="btn-sign-up">
                      Sign Up
                    </a>
                  </div>
                @endguest
                <button class="btn-menu d-xl-none">
                  <i class="lastudioicon-menu-3-1"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!--== End Header Wrapper ==-->

  @include('partials.toasts')

  <main class="main-content">

    @yield('content')
  </main>

  <!--== Start Footer Area Wrapper ==-->
  <footer class="footer-area footer-style1">
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-lg-6">
            <div class="footer-logo-area">
              <a href="{{ route('home') }}">
                <img class="logo-main" src="{{ asset('assets/img/logo-main.png') }}" alt="Logo" />
              </a>
            </div>
          </div>
          <div class="col-md-8 col-lg-6">
            <div class="newsletter-content-wrap">
              <form class="newsletter-form" action="#" method="post">
                @csrf
                <input class="form-control" type="text" id="email" name="email" placeholder="Enter your email address...">
                <button class="btn btn-theme" type="submit">Subscribe</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-main">
      <div class="container">
        <div class="row row-gutter-0">
          <div class="col-sm-6 col-md-4">
            <div class="widget-item">
              <nav class="widget-menu-wrap">
                <ul class="nav-menu nav">
                  <li><a href="#">PRIVACY POLICY</a></li>
                  <li><a href="#">TERMS & CONDITIONS</a></li>
                  <li><a href="{{ route('pages.about') }}">ABOUT</a></li>
                </ul>
              </nav>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="widget-item mt-xs-20">
              <nav class="widget-menu-wrap">
                <ul class="nav-menu nav">
                  <li><a href="#">SHIPPING INFO</a></li>
                  <li><a href="#">RETURNS / EXCHANGES</a></li>
                  <li><a href="{{ route('contact') }}">CONTACT</a></li>
                </ul>
              </nav>
            </div>
          </div>
          <div class="col-md-5">
            <div class="widget-item widget-social-item mt-sm-30 mt-xs-20">
              <span>ON SOCIAL NETWORKS</span>
              <div class="widget-social-icons">
                <a href="#"><i class="lastudioicon lastudioicon-b-facebook"></i></a>
                <a href="#"><i class="lastudioicon lastudioicon-b-pinterest"></i></a>
                <a href="#"><i class="lastudioicon lastudioicon-b-twitter"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!--== End Footer Area Wrapper ==-->

  <!--== Scroll Top Button ==-->
  <div class="scroll-to-top"><span class="icofont-arrow-up"></span></div>

  <!--== Start Product Quick View ==-->
  <aside class="product-quick-view-modal">
    <div class="product-quick-view-inner">
      <div class="product-quick-view-content">
        <button type="button" class="btn-close">
          <span class="close-icon"><i class="lastudioicon-e-remove"></i></span>
        </button>
        <div class="row row-gutter-0">
          <div class="col-lg-6 col-md-6 col-12">
            <div class="thumb">
              <img src="{{ asset('assets/img/shop/quick-view1.jpg') }}" alt="AliarLIE Clothing">
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-12">
            <div class="single-product-info">
              <h4 class="title">Product Simple</h4>
              <div class="prices">
                <span class="price">£49.90</span>
              </div>
              <p class="product-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
              <div class="quick-product-action">
                <div class="action-top">
                  <a class="btn-theme btn-black" href="{{ route('shop.cart') }}">Add to cart</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="canvas-overlay"></div>
  </aside>
  <!--== End Product Quick View ==-->

  <!--== Start Aside Search Menu ==-->
  <div class="search-box-wrapper">
    <div class="search-box-content-inner">
      <div class="search-box-form-wrap">
        <div class="search-note">
          <p>Start typing and press Enter to search</p>
        </div>
        <form action="{{ route('shop.index') }}" method="get">
          <div class="search-form position-relative">
            <label for="search-input" class="sr-only">Search</label>
            <input type="search" class="form-control" name="search" placeholder="Search" id="search-input" value="{{ request('search') }}">
            <button type="submit" class="search-button"><i class="lastudioicon-zoom-1"></i></button>
          </div>
        </form>
      </div>
    </div>
    <a href="javascript:;" class="search-close"><i class="lastudioicon-e-remove"></i></a>
  </div>
  <!--== End Aside Search Menu ==-->

  <!--== Start Sidebar Cart Menu ==-->
  <aside class="sidebar-cart-modal">
    <div class="sidebar-cart-inner">
      <div class="sidebar-cart-content js-sidebar-cart">
        @include('partials.sidebar-cart')
      </div>
    </div>
  </aside>
  <div class="sidebar-cart-overlay"></div>
  <!--== End Sidebar Cart Menu ==-->

  <!--== Start Side Menu ==-->
  <aside class="off-canvas-wrapper">
    <div class="off-canvas-inner">
      <div class="off-canvas-overlay d-none"></div>
      <div class="off-canvas-content">
        <div class="off-canvas-header">
          <div class="close-action">
            <button class="btn-close"><i class="icofont-close-line"></i></button>
          </div>
        </div>
        <div class="off-canvas-item">
          <div class="res-mobile-menu">
            <!-- Note Content Auto Generate By Jquery From Main Menu -->
          </div>
        </div>
        <div class="off-canvas-footer"></div>
      </div>
    </div>
  </aside>
  <!--== End Side Menu ==-->
</div>

<!--=======================Javascript============================-->

<!--=== Modernizr Min Js ===-->
<script src="{{ asset('assets/js/modernizr.js') }}"></script>
<!--=== jQuery Min Js ===-->
<script src="{{ asset('assets/js/jquery-main.js') }}"></script>
<!--=== jQuery Migration Min Js ===-->
<script src="{{ asset('assets/js/jquery-migrate.js') }}"></script>
<!--=== Popper Min Js ===-->
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<!--=== Bootstrap Min Js ===-->
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<!--=== jquery Appear Js ===-->
<script src="{{ asset('assets/js/jquery.appear.js') }}"></script>
<!--=== jquery Swiper Min Js ===-->
<script src="{{ asset('assets/js/swiper.min.js') }}"></script>
<!--=== jquery Fancybox Min Js ===-->
<script src="{{ asset('assets/js/fancybox.min.js') }}"></script>
<!--=== jquery Aos Min Js ===-->
<script src="{{ asset('assets/js/aos.min.js') }}"></script>
<!--=== jquery Slicknav Js ===-->
<script src="{{ asset('assets/js/jquery.slicknav.js') }}"></script>
<!--=== jquery Countdown Js ===-->
<script src="{{ asset('assets/js/jquery.countdown.min.js') }}"></script>
<!--=== jquery Tippy Js ===-->
<script src="{{ asset('assets/js/tippy.all.min.js') }}"></script>
<!--=== Isotope Min Js ===-->
<script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
<!--=== jquery Vivus Js ===-->
<script src="{{ asset('assets/js/vivus.js') }}"></script>
<!--=== Parallax Min Js ===-->
<script src="{{ asset('assets/js/parallax.min.js') }}"></script>
<!--=== Slick  Min Js ===-->
<script src="{{ asset('assets/js/slick.min.js') }}"></script>
<!--=== jquery Wow Min Js ===-->
<script src="{{ asset('assets/js/wow.min.js') }}"></script>
<!--=== jquery Zoom Min Js ===-->
<script src="{{ asset('assets/js/jquery-zoom.min.js') }}"></script>

<!--=== Custom Js ===-->
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
  (function () {
    function getCsrfToken() {
      var tokenElement = document.querySelector('meta[name="csrf-token"]');
      return tokenElement ? tokenElement.getAttribute('content') : '';
    }

    function updateCartUi(data) {
      var cartCount = document.querySelector('.cart-count');
      if (cartCount && typeof data.cart_count !== 'undefined') {
        cartCount.textContent = data.cart_count;
      }

      var sidebarCart = document.querySelector('.js-sidebar-cart');
      if (sidebarCart && data.sidebar_cart) {
        sidebarCart.innerHTML = data.sidebar_cart;
      }

      var cartContent = document.querySelector('.js-cart-content');
      if (cartContent && data.cart_content) {
        cartContent.innerHTML = data.cart_content;
      }
    }

    function sendCartRequest(url, payload) {
      return fetch(url, {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': getCsrfToken(),
        },
        credentials: 'same-origin',
        body: JSON.stringify(payload),
      })
        .then(function (response) {
          if (!response.ok) {
            throw response;
          }
          return response.json();
        })
        .then(function (data) {
          updateCartUi(data);
          return data;
        });
    }

    document.addEventListener('click', function (event) {
      var removeButton = event.target.closest('.js-cart-remove');
      if (!removeButton) {
        return;
      }

      event.preventDefault();

      var cartKey = removeButton.getAttribute('data-cart-key');
      if (!cartKey) {
        return;
      }

      sendCartRequest("{{ route('shop.cart.remove') }}", { cart_key: cartKey })
        .catch(function () {
          window.alert('Unable to remove this item right now.');
        });
    });

    document.addEventListener('change', function (event) {
      var qtyInput = event.target.closest('.js-cart-qty');
      if (!qtyInput) {
        return;
      }

      var cartKey = qtyInput.getAttribute('data-cart-key');
      var quantity = parseInt(qtyInput.value, 10);

      if (!cartKey || Number.isNaN(quantity) || quantity < 1) {
        return;
      }

      sendCartRequest("{{ route('shop.cart.update') }}", {
        cart_key: cartKey,
        quantity: quantity,
      }).catch(function () {
        window.alert('Unable to update cart right now.');
      });
    });
  })();
</script>

<script>
  (function () {
    function dismissToast(element) {
      if (!element) {
        return;
      }
      element.classList.remove('show');
      setTimeout(function () {
        element.remove();
      }, 200);
    }

    document.addEventListener('click', function (event) {
      var closeButton = event.target.closest('.js-toast-close');
      if (!closeButton) {
        return;
      }

      event.preventDefault();
      dismissToast(closeButton.closest('.js-toast'));
    });

    document.querySelectorAll('.js-toast[data-auto-dismiss]').forEach(function (toast) {
      var timeout = parseInt(toast.getAttribute('data-auto-dismiss'), 10);
      if (!Number.isNaN(timeout) && timeout > 0) {
        setTimeout(function () {
          dismissToast(toast);
        }, timeout);
      }
    });
  })();
</script>

@stack('scripts')

@guest('customer')
  @if (config('services.google.client_id'))
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
      window.handleGoogleOneTap = function (response) {
        if (!response || !response.credential) {
          return;
        }

        fetch("{{ route('shop.auth.google.one-tap') }}", {
          method: 'POST',
          headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          credentials: 'same-origin',
          body: JSON.stringify({ credential: response.credential }),
        })
          .then(function (res) {
            return res.json();
          })
          .then(function (data) {
            if (data.redirect) {
              window.location.href = data.redirect;
            }
          })
          .catch(function () {
            // Silent fail to avoid blocking the UI
          });
      };
    </script>
    <div id="g_id_onload"
         data-client_id="{{ config('services.google.client_id') }}"
         data-auto_select="true"
         data-callback="handleGoogleOneTap"
         data-cancel_on_tap_outside="false"></div>
  @endif
@endguest
</body>

</html>

