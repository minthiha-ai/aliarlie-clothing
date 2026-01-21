@extends('layouts.app')

@section('title', 'Checkout - AliarLIE Clothing')

@section('wrapper-class', '')

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
            <h2 class="title">Checkout</h2>
            <div class="bread-crumbs">
              <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
              <span class="active">Checkout</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Page Title Area ==-->

  @php
    $shipDifferent = old('ship_to_different') ? true : false;
    $selectedPaymentMethod = $payments->isEmpty()
        ? 'cod'
        : old('payment_method', 'cod');
  @endphp

  <!--== Start Shop Checkout Area ==-->
  <section class="shop-checkout-area">
    <div class="container">
      <form action="{{ route('shop.checkout.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
          <div class="col-lg-7">
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                  <h4 class="mb-0">Billing details</h4>
                  <span class="text-muted small">Fields marked with * are required</span>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="billing_first_name">First name <abbr class="required" title="required">*</abbr></label>
                      <input class="form-control @error('billing_first_name') is-invalid @enderror" id="billing_first_name" name="billing_first_name" type="text" value="{{ old('billing_first_name') }}" required>
                      @error('billing_first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="billing_last_name">Last name <abbr class="required" title="required">*</abbr></label>
                      <input class="form-control @error('billing_last_name') is-invalid @enderror" id="billing_last_name" name="billing_last_name" type="text" value="{{ old('billing_last_name') }}" required>
                      @error('billing_last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="form-group mt-3">
                  <label for="billing_country">Country / Region <abbr class="required" title="required">*</abbr></label>
                  <select class="form-control @error('billing_country') is-invalid @enderror" id="billing_country" name="billing_country" required>
                    <option value="MM" {{ old('billing_country', 'MM') === 'MM' ? 'selected' : '' }}>Myanmar</option>
                    <option value="TH" {{ old('billing_country') === 'TH' ? 'selected' : '' }}>Thailand</option>
                    <option value="SG" {{ old('billing_country') === 'SG' ? 'selected' : '' }}>Singapore</option>
                    <option value="MY" {{ old('billing_country') === 'MY' ? 'selected' : '' }}>Malaysia</option>
                  </select>
                  @error('billing_country')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group mt-3">
                  <label for="billing_address_line1">Street address <abbr class="required" title="required">*</abbr></label>
                  <input class="form-control @error('billing_address_line1') is-invalid @enderror" id="billing_address_line1" name="billing_address_line1" type="text" value="{{ old('billing_address_line1') }}" required>
                  @error('billing_address_line1')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group mt-3">
                  <input class="form-control @error('billing_address_line2') is-invalid @enderror" id="billing_address_line2" name="billing_address_line2" type="text" placeholder="Apartment, suite, unit, etc. (optional)" value="{{ old('billing_address_line2') }}">
                  @error('billing_address_line2')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="row g-3 mt-1">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="billing_township">Township <abbr class="required" title="required">*</abbr></label>
                      <input class="form-control @error('billing_township') is-invalid @enderror" id="billing_township" name="billing_township" type="text" value="{{ old('billing_township') }}" required>
                      @error('billing_township')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="billing_city">Town / City <abbr class="required" title="required">*</abbr></label>
                      <input class="form-control @error('billing_city') is-invalid @enderror" id="billing_city" name="billing_city" type="text" value="{{ old('billing_city') }}" required>
                      @error('billing_city')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="row g-3 mt-1">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="billing_postal_code">ZIP</label>
                      <input class="form-control @error('billing_postal_code') is-invalid @enderror" id="billing_postal_code" name="billing_postal_code" type="text" value="{{ old('billing_postal_code') }}">
                      @error('billing_postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="billing_phone">Phone <abbr class="required" title="required">*</abbr></label>
                      <input class="form-control @error('billing_phone') is-invalid @enderror" id="billing_phone" name="billing_phone" type="text" value="{{ old('billing_phone') }}" required>
                      @error('billing_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="form-group mt-3">
                  <label for="billing_email">Email address <abbr class="required" title="required">*</abbr></label>
                  <input class="form-control @error('billing_email') is-invalid @enderror" id="billing_email" name="billing_email" type="email" value="{{ old('billing_email') }}" required>
                  @error('billing_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body p-4">
                <div class="form-check form-switch">
                  <input type="checkbox" class="form-check-input" id="ship_to_different" name="ship_to_different" value="1" {{ $shipDifferent ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold" for="ship_to_different">Ship to a different address</label>
                </div>

                <div class="ship-to-different single-form-row mt-4 {{ $shipDifferent ? '' : 'd-none' }}">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="shipping_first_name">First name <abbr class="required" title="required">*</abbr></label>
                        <input class="form-control @error('shipping_first_name') is-invalid @enderror" id="shipping_first_name" name="shipping_first_name" type="text" value="{{ old('shipping_first_name') }}">
                        @error('shipping_first_name')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="shipping_last_name">Last name <abbr class="required" title="required">*</abbr></label>
                        <input class="form-control @error('shipping_last_name') is-invalid @enderror" id="shipping_last_name" name="shipping_last_name" type="text" value="{{ old('shipping_last_name') }}">
                        @error('shipping_last_name')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>

                  <div class="form-group mt-3">
                    <label for="shipping_country">Country / Region <abbr class="required" title="required">*</abbr></label>
                    <select class="form-control @error('shipping_country') is-invalid @enderror" id="shipping_country" name="shipping_country">
                      <option value="MM" {{ old('shipping_country', 'MM') === 'MM' ? 'selected' : '' }}>Myanmar</option>
                      <option value="TH" {{ old('shipping_country') === 'TH' ? 'selected' : '' }}>Thailand</option>
                      <option value="SG" {{ old('shipping_country') === 'SG' ? 'selected' : '' }}>Singapore</option>
                      <option value="MY" {{ old('shipping_country') === 'MY' ? 'selected' : '' }}>Malaysia</option>
                    </select>
                    @error('shipping_country')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group mt-3">
                    <label for="shipping_address_line1">Street address <abbr class="required" title="required">*</abbr></label>
                    <input class="form-control @error('shipping_address_line1') is-invalid @enderror" id="shipping_address_line1" name="shipping_address_line1" type="text" value="{{ old('shipping_address_line1') }}">
                    @error('shipping_address_line1')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="form-group mt-3">
                    <input class="form-control @error('shipping_address_line2') is-invalid @enderror" id="shipping_address_line2" name="shipping_address_line2" type="text" placeholder="Apartment, suite, unit, etc. (optional)" value="{{ old('shipping_address_line2') }}">
                    @error('shipping_address_line2')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="row g-3 mt-1">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="shipping_township">Township <abbr class="required" title="required">*</abbr></label>
                        <input class="form-control @error('shipping_township') is-invalid @enderror" id="shipping_township" name="shipping_township" type="text" value="{{ old('shipping_township') }}">
                        @error('shipping_township')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="shipping_city">Town / City <abbr class="required" title="required">*</abbr></label>
                        <input class="form-control @error('shipping_city') is-invalid @enderror" id="shipping_city" name="shipping_city" type="text" value="{{ old('shipping_city') }}">
                        @error('shipping_city')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>

                  <div class="row g-3 mt-1">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="shipping_postal_code">ZIP</label>
                        <input class="form-control @error('shipping_postal_code') is-invalid @enderror" id="shipping_postal_code" name="shipping_postal_code" type="text" value="{{ old('shipping_postal_code') }}">
                        @error('shipping_postal_code')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="shipping_phone">Phone <abbr class="required" title="required">*</abbr></label>
                        <input class="form-control @error('shipping_phone') is-invalid @enderror" id="shipping_phone" name="shipping_phone" type="text" value="{{ old('shipping_phone') }}">
                        @error('shipping_phone')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body p-4">
                <label for="order_notes" class="fw-semibold">Order notes (optional)</label>
                <textarea class="form-control @error('order_notes') is-invalid @enderror" name="order_notes" id="order_notes" placeholder="Notes about your order, e.g. special notes for delivery.">{{ old('order_notes') }}</textarea>
                @error('order_notes')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="col-lg-5">
            <div class="position-sticky" style="top: 24px;">
              <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                  <h4 class="mb-3">Order Summary</h4>
                  <div class="table-responsive">
                    <table class="table align-middle mb-0">
                      <thead>
                        <tr class="text-muted small">
                          <th>Product</th>
                          <th class="text-end">Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($cartItems as $cartItem)
                          <tr>
                            <td>
                              <div class="fw-semibold">{{ $cartItem['name'] ?? 'Item' }}</div>
                              <div class="text-muted small">
                                @if (!empty($cartItem['variant']))
                                  {{ $cartItem['variant'] }}
                                @endif
                                <span class="ms-1">× {{ $cartItem['quantity'] ?? 1 }}</span>
                              </div>
                            </td>
                            <td class="text-end">${{ number_format(($cartItem['price'] ?? 0) * ($cartItem['quantity'] ?? 1), 2) }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr class="text-muted">
                          <th>Subtotal</th>
                          <td class="text-end">${{ number_format($subtotal, 2) }}</td>
                        </tr>
                        <tr>
                          <th class="fw-bold">Total</th>
                          <td class="text-end">
                            <div class="bg-light px-3 py-2 rounded fw-bold fs-6">${{ number_format($subtotal, 2) }}</div>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>

              <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                  <h4 class="mb-3">Payment Method</h4>
                  <div class="d-flex flex-column gap-3">
                    <label class="payment-option border rounded-3 p-3 d-flex align-items-start gap-3 {{ $selectedPaymentMethod === 'cod' ? 'border-dark' : '' }}">
                      <input class="form-check-input mt-1 @error('payment_method') is-invalid @enderror" type="radio" name="payment_method" value="cod" {{ $selectedPaymentMethod === 'cod' ? 'checked' : '' }}>
                      <span>
                        <span class="fw-semibold d-block">Cash on delivery</span>
                        <span class="text-muted small">Pay with cash when your order arrives.</span>
                      </span>
                    </label>

                    <label class="payment-option border rounded-3 p-3 d-flex align-items-start gap-3 {{ $selectedPaymentMethod === 'online_payment' ? 'border-dark' : '' }} {{ $payments->isEmpty() ? 'opacity-50' : '' }}">
                      <input class="form-check-input mt-1 @error('payment_method') is-invalid @enderror" type="radio" name="payment_method" value="online_payment" {{ $selectedPaymentMethod === 'online_payment' ? 'checked' : '' }} {{ $payments->isEmpty() ? 'disabled' : '' }}>
                      <span>
                        <span class="fw-semibold d-block">Online payment</span>
                        <span class="text-muted small">Pay securely via supported wallets or bank transfer.</span>
                        @if ($payments->isEmpty())
                          <span class="text-muted small d-block mt-1">No online payment options available.</span>
                        @endif
                      </span>
                    </label>
                    @error('payment_method')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="card mt-4 border js-online-payment {{ $selectedPaymentMethod === 'online_payment' ? '' : 'd-none' }}">
                    <div class="card-body">
                      <div class="form-group">
                        <label for="payment_id" class="fw-semibold">Select payment account</label>
                        <select class="form-control @error('payment_id') is-invalid @enderror" id="payment_id" name="payment_id">
                          <option value="">Choose a payment option</option>
                          @foreach ($payments as $payment)
                            <option value="{{ $payment->id }}" {{ (string) old('payment_id') === (string) $payment->id ? 'selected' : '' }}>
                              {{ $payment->name }} @if($payment->number) - {{ $payment->number }} @endif
                            </option>
                          @endforeach
                        </select>
                        @error('payment_id')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="form-group mt-3">
                        <label for="payment_proof_photo" class="fw-semibold">Upload payment proof</label>
                        <input class="form-control @error('payment_proof_photo') is-invalid @enderror" type="file" id="payment_proof_photo" name="payment_proof_photo" accept="image/*">
                        @error('payment_proof_photo')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1">Upload a screenshot or receipt (max 2MB).</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <button class="btn btn-dark w-100" type="submit">
                Place order • ${{ number_format($subtotal, 2) }}
              </button>
              <p class="text-muted small mt-3">Your personal data will be used to process your order and support your experience throughout this website.</p>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
  <!--== End Shop Checkout Area ==-->
@endsection

@push('scripts')
  <script>
    document.addEventListener('change', function (event) {
      if (event.target && event.target.id === 'ship_to_different') {
        var shippingBlock = document.querySelector('.ship-to-different');
        if (shippingBlock) {
          shippingBlock.classList.toggle('d-none', !event.target.checked);
        }
      }

      if (event.target && event.target.name === 'payment_method') {
        var onlineBlock = document.querySelector('.js-online-payment');
        if (onlineBlock) {
          onlineBlock.classList.toggle('d-none', event.target.value !== 'online_payment');
        }

        document.querySelectorAll('.payment-option').forEach(function (option) {
          option.classList.remove('border-dark');
        });
        var selectedOption = event.target.closest('.payment-option');
        if (selectedOption) {
          selectedOption.classList.add('border-dark');
        }
      }
    });
  </script>
@endpush
