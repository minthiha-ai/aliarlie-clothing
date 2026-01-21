@if ($cartItems->isEmpty())
  <div class="row">
    <div class="col-12 text-center">
      <p>Your cart is empty.</p>
      <a class="btn btn-theme mt-3" href="{{ route('shop.index') }}">Continue shopping</a>
    </div>
  </div>
@else
  <div class="row">
    <div class="col-lg-8">
      <div class="cart-table-wrap">
        <div class="cart-table table-responsive">
          <table>
            <thead>
              <tr>
                <th class="pro-remove"></th>
                <th class="pro-thumbnail"></th>
                <th class="pro-name">Product</th>
                <th class="pro-price">Price</th>
                <th class="pro-quantity">Quantity</th>
                <th class="pro-subtotal">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($cartItems as $cartKey => $item)
                @php
                  $price = (float) ($item['price'] ?? 0);
                  $quantity = (int) ($item['quantity'] ?? 0);
                  $lineTotal = $price * $quantity;
                  $image = $item['image'] ?? null;
                  $productId = $item['product_id'] ?? null;
                @endphp
                <tr>
                  <td class="pro-remove">
                    <button class="js-cart-remove" data-cart-key="{{ $cartKey }}" type="button">
                      <i class="lastudioicon-e-remove"></i>
                    </button>
                  </td>
                  <td class="pro-thumbnail">
                    <div class="pro-info">
                      <div class="pro-img">
                        <a href="{{ $productId ? route('shop.product', $productId) : 'javascript:void(0);' }}">
                          <img src="{{ $image ? \Illuminate\Support\Facades\Storage::disk('public')->url($image) : asset('assets/img/shop/15.jpg') }}" alt="{{ $item['name'] ?? 'Product' }}">
                        </a>
                      </div>
                    </div>
                  </td>
                  <td class="pro-name">
                    <span>{{ $item['name'] ?? 'Product' }}</span>
                    @if (!empty($item['variant']))
                      <small class="d-block text-muted">{{ $item['variant'] }}</small>
                    @endif
                  </td>
                  <td class="pro-price"><span>${{ number_format($price, 2) }}</span></td>
                  <td class="pro-quantity">
                    <div class="action-top">
                      <div class="pro-qty-area">
                        <div class="pro-qty">
                          <input
                            class="js-cart-qty"
                            data-cart-key="{{ $cartKey }}"
                            min="1"
                            type="number"
                            value="{{ $quantity }}"
                          />
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="pro-subtotal"><span>${{ number_format($lineTotal, 2) }}</span></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="coupon-action">
        <div class="coupon">
          <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="Coupon code">
          <button type="submit" class="button" name="apply_coupon" value="Apply coupon">Apply coupon</button>
        </div>
        <button type="submit" class="button" name="apply_coupon" value="Apply coupon">Update cart</button>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="cart-payment">
        <div class="cart-subtotal">
          <h2 class="title">Cart totals</h2>
          <table>
            <tbody>
              <tr>
                <th>Subtotal</th>
                <td><span class="amount">${{ number_format($subtotal, 2) }}</span></td>
              </tr>
              <tr class="shipping-totals">
                <th>Shipping</th>
                <td>
                  <ul>
                    <li>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipping_method" id="shipping_flat_rate">
                        <label class="form-check-label" for="shipping_flat_rate">Flat rate: <span class="amount">$50.00</span></label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipping_method" id="shipping_local_pickup" checked>
                        <label class="form-check-label" for="shipping_local_pickup">Local pickup</label>
                      </div>
                    </li>
                  </ul>
                  <p>Shipping options will be updated during checkout.</p>
                  <p>Calculate shipping</p>
                </td>
              </tr>
              <tr class="amount-total">
                <th>Total</th>
                <td><span class="amount">${{ number_format($subtotal, 2) }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
        <a class="btn-theme" href="{{ route('shop.checkout') }}">Proceed to Checkout</a>
      </div>
    </div>
  </div>
@endif
