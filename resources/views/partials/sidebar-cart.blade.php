<a class="cart-close" href="{{ url('/') }}"><i class="lastudioicon-e-remove"></i></a>
<div class="sidebar-cart">
  <h4 class="sidebar-cart-title">Shopping Cart</h4>
  <div class="product-cart">
    @php
      $sidebarCartItems = collect(session('cart', []));
      $sidebarSubtotal = collect(session('cart', []))->sum(function (array $item): float {
          return ((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0));
      });
    @endphp
    @forelse ($sidebarCartItems as $cartKey => $item)
      @php
        $price = (float) ($item['price'] ?? 0);
        $quantity = (int) ($item['quantity'] ?? 0);
        $image = $item['image'] ?? null;
      @endphp
      <div class="product-cart-item">
        <div class="product-img">
          <a href="javascript:void(0);">
            <img src="{{ $image ? \Illuminate\Support\Facades\Storage::disk('public')->url($image) : asset('assets/img/shop/cart/1.jpg') }}" alt="{{ $item['name'] ?? 'Product' }}">
          </a>
        </div>
        <div class="product-info">
          <h4 class="title"><a href="javascript:void(0);">{{ $item['name'] ?? 'Product' }}</a></h4>
          <span class="info">{{ $quantity }} × ${{ number_format($price, 2) }}</span>
        </div>
      <div class="product-delete">
        <button
          class="js-cart-remove"
          data-cart-key="{{ $cartKey }}"
          type="button"
          style="background: none; border: 0; padding: 0;"
        >
          <i class="lastudioicon-e-remove"></i>
        </button>
      </div>
      </div>
    @empty
      <p class="text-center mb-0">Your cart is empty.</p>
    @endforelse
  </div>
  <div class="cart-total">
    <h4>Subtotal: <span class="money">${{ number_format($sidebarSubtotal, 2) }}</span></h4>
  </div>
  <div class="cart-checkout-btn">
    <a class="btn-theme" href="{{ route('shop.cart') }}">View cart</a>
    <a class="btn-theme" href="{{ route('shop.checkout') }}">Checkout</a>
  </div>
</div>
