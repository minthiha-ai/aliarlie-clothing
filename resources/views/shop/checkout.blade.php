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

    $defaultFirstName = old('billing_first_name')
        ?? (isset($defaultAddress) && $defaultAddress
            ? explode(' ', $defaultAddress->receiver_name, 2)[0] ?? ''
            : (isset($customer) ? explode(' ', $customer->name ?? '', 2)[0] ?? '' : ''));
    $defaultLastName = old('billing_last_name')
        ?? (isset($defaultAddress) && $defaultAddress
            ? (explode(' ', $defaultAddress->receiver_name, 2)[1] ?? '')
            : (isset($customer) ? (explode(' ', $customer->name ?? '', 2)[1] ?? '') : ''));
    $defaultAddressLine1 = old('billing_address_line1')
        ?? (isset($defaultAddress) && $defaultAddress ? $defaultAddress->address : '');
    $defaultPhone = old('billing_phone')
        ?? (isset($defaultAddress) && $defaultAddress ? $defaultAddress->phone : (isset($customer) ? $customer->phone : ''));
    $defaultEmail = old('billing_email')
        ?? (isset($customer) ? $customer->email : '');
    $defaultStateRegionId = old('billing_state_region_id')
        ?? (isset($defaultAddress) && $defaultAddress ? (string) $defaultAddress->state_region_id : '');
    $defaultTownshipId = old('billing_township_id')
        ?? (isset($defaultAddress) && $defaultAddress ? (string) $defaultAddress->township_id : '');
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
                      <input class="form-control @error('billing_first_name') is-invalid @enderror" id="billing_first_name" name="billing_first_name" type="text" value="{{ $defaultFirstName }}" required>
                      @error('billing_first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="billing_last_name">Last name <abbr class="required" title="required">*</abbr></label>
                      <input class="form-control @error('billing_last_name') is-invalid @enderror" id="billing_last_name" name="billing_last_name" type="text" value="{{ $defaultLastName }}" required>
                      @error('billing_last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="form-group mt-3">
                  <label for="billing_state_region_id">State / Region <abbr class="required" title="required">*</abbr></label>
                  <select class="form-select js-select2 @error('billing_state_region_id') is-invalid @enderror" id="billing_state_region_id" name="billing_state_region_id" required>
                    <option value="">Select State / Region</option>
                    @foreach ($stateRegions as $region)
                      <option value="{{ $region->id }}" @selected($defaultStateRegionId == $region->id)>{{ $region->name }}</option>
                    @endforeach
                  </select>
                  @error('billing_state_region_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group mt-3">
                  <label for="billing_address_line1">Street address <abbr class="required" title="required">*</abbr></label>
                  <input class="form-control @error('billing_address_line1') is-invalid @enderror" id="billing_address_line1" name="billing_address_line1" type="text" value="{{ $defaultAddressLine1 }}" required>
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

                <div class="form-group mt-3">
                  <label for="billing_township_id">Delivery <abbr class="required" title="required">*</abbr></label>
                  <select class="form-select js-select2 @error('billing_township_id') is-invalid @enderror" id="billing_township_id" name="billing_township_id" required disabled>
                    <option value="">Select delivery option</option>
                  </select>
                  <small class="text-muted d-block mt-1">Choose your township to see delivery fee</small>
                  @error('billing_township_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <input type="hidden" name="billing_country" value="MM">

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
                      <input class="form-control @error('billing_phone') is-invalid @enderror" id="billing_phone" name="billing_phone" type="text" value="{{ $defaultPhone }}" required>
                      @error('billing_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="form-group mt-3">
                  <label for="billing_email">Email address <abbr class="required" title="required">*</abbr></label>
                  <input class="form-control @error('billing_email') is-invalid @enderror" id="billing_email" name="billing_email" type="email" value="{{ $defaultEmail }}" required>
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
              <div class="card border-0 shadow-sm mb-4" id="order-summary-card" data-subtotal="{{ $subtotal }}">
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
                            <td class="text-end">MMK {{ number_format(($cartItem['price'] ?? 0) * ($cartItem['quantity'] ?? 1), 2) }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr class="text-muted">
                          <th>Subtotal</th>
                          <td class="text-end" id="checkout-subtotal">MMK {{ number_format($subtotal, 2) }}</td>
                        </tr>
                        <tr class="text-muted js-delivery-row">
                          <th>Delivery</th>
                          <td class="text-end" id="checkout-delivery">MMK 0.00</td>
                        </tr>
                        <tr>
                          <th class="fw-bold">Total</th>
                          <td class="text-end">
                            <div class="bg-light px-3 py-2 rounded fw-bold fs-6" id="checkout-total">MMK {{ number_format($subtotal, 2) }}</div>
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

                  <div class="card mt-4 border-0 shadow-sm js-online-payment {{ $selectedPaymentMethod === 'online_payment' ? '' : 'd-none' }}">
                    <div class="card-body p-4">
                      <label class="fw-semibold d-block mb-2">Select payment account</label>
                      @if ($payments->isEmpty())
                        <p class="text-muted small mb-0">No payment accounts available.</p>
                      @else
                        <div class="row g-2 mb-0">
                          @foreach ($payments as $payment)
                            @php
                              $isSelected = (string) old('payment_id') === (string) $payment->id;
                            @endphp
                            <div class="col-6">
                              <label class="payment-account-card d-flex flex-column rounded-3 border p-2 text-center text-decoration-none text-dark cursor-pointer m-0 h-100 {{ $isSelected ? 'border-dark border-2 bg-light' : '' }}" for="payment_account_{{ $payment->id }}">
                                <input type="radio" name="payment_id" value="{{ $payment->id }}" id="payment_account_{{ $payment->id }}" class="visually-hidden @error('payment_id') is-invalid @enderror" {{ $isSelected ? 'checked' : '' }}>
                                <div class="payment-account-card-logo rounded-2 bg-light d-flex align-items-center justify-content-center mx-auto flex-shrink-0" style="height: 72px; width: 80px;">
                                  @if ($payment->payment_logo)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($payment->payment_logo) }}" alt="" class="img-fluid" style="max-height: 56px; max-width: 72px; object-fit: contain;">
                                  @else
                                    <span class="text-muted small">Logo</span>
                                  @endif
                                </div>
                                <div class="payment-account-card-body mt-2 flex-grow-1 d-flex flex-column justify-content-center min-h-payment-card-text">
                                  <div class="fw-semibold small text-break">{{ $payment->name ?? '—' }}</div>
                                  @if ($payment->number)
                                    <div class="text-muted small text-break mt-1">{{ $payment->number }}</div>
                                  @else
                                    <div class="text-muted small mt-1">&nbsp;</div>
                                  @endif
                                </div>
                              </label>
                            </div>
                          @endforeach
                        </div>
                        @error('payment_id')
                          <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                      @endif

                      <div class="form-group mt-3 pt-3 border-top">
                        <label for="payment_proof_photo" class="fw-semibold d-block mb-2">Upload payment proof</label>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                          <input class="form-control form-control-sm @error('payment_proof_photo') is-invalid @enderror" type="file" id="payment_proof_photo" name="payment_proof_photo" accept="image/*" style="max-width: 220px;">
                        </div>
                        @error('payment_proof_photo')
                          <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1">Upload a screenshot or receipt (max 2MB).</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <button class="btn btn-dark w-100" type="submit" id="place-order-btn">
                Place order • MMK <span id="place-order-amount">{{ number_format($subtotal, 2) }}</span>
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

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
  <style>
    .payment-account-card { transition: border-color .15s ease, background-color .15s ease; }
    .payment-account-card:hover { border-color: var(--bs-secondary) !important; background-color: var(--bs-light) !important; }
    .payment-account-card:has(input:checked) { border-color: var(--bs-dark) !important; }
    .min-h-payment-card-text { min-height: 3.5rem; }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
  <script>
    (function () {
      var subtotal = parseFloat(document.getElementById('order-summary-card').getAttribute('data-subtotal') || 0);
      var townshipsUrl = '{{ route("shop.account.townships") }}';
      var townshipsCache = {};

      function formatMoney(n) {
        return 'MMK ' + parseFloat(n).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
      }

      function updateOrderSummary(deliveryFees) {
        deliveryFees = deliveryFees || 0;
        var total = subtotal + deliveryFees;
        var deliveryRow = document.querySelector('.js-delivery-row');
        var deliveryCell = document.getElementById('checkout-delivery');
        var totalEl = document.getElementById('checkout-total');
        var placeOrderAmount = document.getElementById('place-order-amount');
        if (deliveryRow && deliveryCell) {
          deliveryCell.textContent = formatMoney(deliveryFees);
        }
        if (totalEl) totalEl.textContent = formatMoney(total);
        if (placeOrderAmount) placeOrderAmount.textContent = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
      }

      function loadTownships(stateRegionId, selectedTownshipId) {
        var townshipSelect = document.getElementById('billing_township_id');
        townshipSelect.innerHTML = '<option value="">Select Township</option>';
        townshipSelect.disabled = true;
        if (window.jQuery && townshipSelect._select2) {
          try { window.jQuery(townshipSelect).select2('destroy'); } catch (e) {}
        }
        townshipSelect._select2 = false;
        if (!stateRegionId) {
          updateOrderSummary(0);
          return;
        }
        if (townshipsCache[stateRegionId]) {
          var list = townshipsCache[stateRegionId];
          list.forEach(function (t) {
            var opt = document.createElement('option');
            opt.value = t.id;
            opt.textContent = t.name + ' — MMK ' + (parseFloat(t.delivery_fees) || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            opt.dataset.deliveryFees = t.delivery_fees;
            if (selectedTownshipId && String(t.id) === String(selectedTownshipId)) opt.selected = true;
            townshipSelect.appendChild(opt);
          });
          townshipSelect.disabled = false;
          if (window.jQuery) {
            var $ts = window.jQuery(townshipSelect);
            $ts.off('change.select2summary select2:select.select2summary').on('change.select2summary select2:select.select2summary', function () {
              var opt = townshipSelect.options[townshipSelect.selectedIndex];
              updateOrderSummary(opt && opt.dataset.deliveryFees ? parseFloat(opt.dataset.deliveryFees) : 0);
            });
            $ts.select2({ width: '100%', placeholder: 'Select delivery option', allowClear: true, theme: 'bootstrap-5' });
            townshipSelect._select2 = true;
          }
          var sel = townshipSelect.options[townshipSelect.selectedIndex];
          updateOrderSummary(sel && sel.dataset.deliveryFees ? parseFloat(sel.dataset.deliveryFees) : 0);
          return;
        }
        fetch(townshipsUrl + '?state_region_id=' + encodeURIComponent(stateRegionId), {
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
          .then(function (res) { return res.json(); })
          .then(function (list) {
            townshipsCache[stateRegionId] = list;
            list.forEach(function (t) {
              var opt = document.createElement('option');
              opt.value = t.id;
              opt.textContent = t.name + ' — MMK ' + (parseFloat(t.delivery_fees) || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
              opt.dataset.deliveryFees = t.delivery_fees;
              if (selectedTownshipId && String(t.id) === String(selectedTownshipId)) opt.selected = true;
              townshipSelect.appendChild(opt);
            });
            townshipSelect.disabled = false;
            if (window.jQuery) {
              var $ts = window.jQuery(townshipSelect);
              $ts.off('change.select2summary select2:select.select2summary').on('change.select2summary select2:select.select2summary', function () {
                var opt = townshipSelect.options[townshipSelect.selectedIndex];
                updateOrderSummary(opt && opt.dataset.deliveryFees ? parseFloat(opt.dataset.deliveryFees) : 0);
              });
              $ts.select2({ width: '100%', placeholder: 'Select delivery option', allowClear: true, theme: 'bootstrap-5' });
              townshipSelect._select2 = true;
            }
            var sel = townshipSelect.options[townshipSelect.selectedIndex];
            updateOrderSummary(sel && sel.dataset.deliveryFees ? parseFloat(sel.dataset.deliveryFees) : 0);
          })
          .catch(function () {
            townshipSelect.disabled = false;
            updateOrderSummary(0);
          });
      }

      document.addEventListener('DOMContentLoaded', function () {
        var $ = window.jQuery;
        var stateSelect = document.getElementById('billing_state_region_id');
        var townshipSelect = document.getElementById('billing_township_id');
        var oldState = '{{ old("billing_state_region_id") }}';
        var oldTownship = '{{ old("billing_township_id") }}';
        var defaultState = '{{ $defaultStateRegionId }}';
        var defaultTownship = '{{ $defaultTownshipId }}';
        var stateToLoad = oldState || defaultState;
        var townshipToSelect = oldTownship || defaultTownship;

        if ($ && stateSelect) {
          $(stateSelect).select2({ width: '100%', placeholder: 'Select State / Region', allowClear: true, theme: 'bootstrap-5' });
        }
        stateSelect.addEventListener('change', function () {
          loadTownships(this.value, null);
        });

        if (stateToLoad) loadTownships(stateToLoad, townshipToSelect);
      });

      document.addEventListener('change', function (event) {
        if (event.target && event.target.id === 'ship_to_different') {
          var shippingBlock = document.querySelector('.ship-to-different');
          if (shippingBlock) shippingBlock.classList.toggle('d-none', !event.target.checked);
        }
        if (event.target && event.target.name === 'payment_method') {
          var onlineBlock = document.querySelector('.js-online-payment');
          if (onlineBlock) onlineBlock.classList.toggle('d-none', event.target.value !== 'online_payment');
          document.querySelectorAll('.payment-option').forEach(function (option) {
            option.classList.remove('border-dark');
          });
          var selectedOption = event.target.closest('.payment-option');
          if (selectedOption) selectedOption.classList.add('border-dark');
        }
        if (event.target && event.target.name === 'payment_id') {
          document.querySelectorAll('.payment-account-card').forEach(function (card) {
            var radio = card.querySelector('input[name="payment_id"]');
            if (radio && radio.checked) {
              card.classList.add('border-dark', 'border-2', 'bg-light');
            } else {
              card.classList.remove('border-dark', 'border-2', 'bg-light');
            }
          });
        }
      });
    })();
  </script>
@endpush
