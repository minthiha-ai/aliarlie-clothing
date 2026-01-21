@extends('layouts.app')

@section('title', 'Order Details - AliarLIE Clothing')

@section('content')
  @php
    $bannerImage = $banner?->image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image)
        : asset('assets/img/photos/bg-page2.jpg');
    $orderStatus = strtolower($order->status);
    $statusMap = [
        'pending' => 'warning',
        'paid' => 'success',
        'shipped' => 'info',
        'cancelled' => 'danger',
    ];
    $orderStatusClass = $statusMap[$orderStatus] ?? 'secondary';
    $billingAddress = $order->addresses->firstWhere('type', 'billing');
    $shippingAddress = $order->addresses->firstWhere('type', 'shipping');
  @endphp

  <!--== Start Page Title Area ==-->
  <section class="page-title-area bg-overlay-black2-6 bg-img" data-bg-img="{{ $bannerImage }}">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="page-title-content">
            <h2 class="title">Order {{ $order->order_code }}</h2>
            <div class="bread-crumbs">
              <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
              <a href="{{ route('shop.account') }}">My Account<span class="breadcrumb-sep">></span></a>
              <a href="{{ route('shop.orders.index') }}">Orders<span class="breadcrumb-sep">></span></a>
              <span class="active">Details</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Page Title Area ==-->

  <section class="account-dashboard-area mb-5">
    <div class="container">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
              <h4 class="mb-1">Order {{ $order->order_code }}</h4>
              <p class="text-muted mb-0">Placed on {{ $order->created_at?->format('M d, Y') }}</p>
            </div>
            <div class="text-end">
              <span class="badge bg-{{ $orderStatusClass }} text-uppercase">{{ $order->status }}</span>
              <div class="fw-semibold text-dark mt-2">${{ number_format($order->total_amount, 2) }}</div>
              <a href="{{ route('shop.orders.index') }}" class="btn btn-outline-dark btn-sm mt-2">Back to orders</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
              <h4 class="mb-3">Items</h4>
              <div class="table-responsive">
                <table class="table align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Product</th>
                      <th>Variant</th>
                      <th>Qty</th>
                      <th class="text-end">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($order->items as $item)
                      @php
                        $variant = $item->productVariant;
                        $product = $variant?->product;
                        $variantLabel = collect([$variant?->size, $variant?->color])
                            ->filter()
                            ->implode(' / ');
                      @endphp
                      <tr>
                        <td>
                          <div class="fw-semibold">{{ $product?->name ?? 'Product' }}</div>
                        </td>
                        <td class="text-muted">{{ $variantLabel ?: '—' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-end fw-semibold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
              <h4 class="mb-3">Order Summary</h4>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Total</span>
                <span class="fw-semibold text-dark">${{ number_format($order->total_amount, 2) }}</span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Payment</span>
                <span class="text-uppercase">{{ $order->payment_method ?? 'N/A' }}</span>
              </div>
              @if ($order->payment_proof_photo)
                <div class="mt-3">
                  <span class="text-muted d-block mb-2">Payment proof</span>
                  <img class="img-fluid rounded" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($order->payment_proof_photo) }}" alt="Payment proof">
                </div>
              @endif
            </div>
          </div>
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <h4 class="mb-3">Addresses</h4>
              <div class="mb-3">
                <h6 class="mb-1">Billing</h6>
                @if ($billingAddress)
                  <p class="mb-0">{{ $billingAddress->receiver_name }}</p>
                  <p class="mb-0">{{ $billingAddress->phone }}</p>
                  <p class="mb-0">{{ $billingAddress->address_line1 }}</p>
                  @if ($billingAddress->address_line2)
                    <p class="mb-0">{{ $billingAddress->address_line2 }}</p>
                  @endif
                  <p class="mb-0">{{ $billingAddress->township }}, {{ $billingAddress->city }}</p>
                @else
                  <p class="text-muted mb-0">Not available.</p>
                @endif
              </div>
              <div>
                <h6 class="mb-1">Shipping</h6>
                @if ($shippingAddress)
                  <p class="mb-0">{{ $shippingAddress->receiver_name }}</p>
                  <p class="mb-0">{{ $shippingAddress->phone }}</p>
                  <p class="mb-0">{{ $shippingAddress->address_line1 }}</p>
                  @if ($shippingAddress->address_line2)
                    <p class="mb-0">{{ $shippingAddress->address_line2 }}</p>
                  @endif
                  <p class="mb-0">{{ $shippingAddress->township }}, {{ $shippingAddress->city }}</p>
                @else
                  <p class="text-muted mb-0">Not available.</p>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
