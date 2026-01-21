@extends('layouts.app')

@section('title', 'My Orders - AliarLIE Clothing')

@section('content')
  @php
    $bannerImage = $banner?->image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image)
        : asset('assets/img/photos/bg-page2.jpg');
  @endphp

  <!--== Start Page Title Area ==-->
  <section class="page-title-area bg-overlay-black2-6 bg-img" data-bg-img="{{ $bannerImage }}">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="page-title-content">
            <h2 class="title">My Orders</h2>
            <div class="bread-crumbs">
              <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
              <a href="{{ route('shop.account') }}">My Account<span class="breadcrumb-sep">></span></a>
              <span class="active">Orders</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Page Title Area ==-->

  <section class="account-dashboard-area mb-5">
    <div class="container">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h4 class="mb-0">All Orders</h4>
            <a href="{{ route('shop.account') }}" class="btn btn-outline-dark btn-sm">Back to account</a>
          </div>

          @if ($orders->isEmpty())
            <div class="text-center py-4">
              <p class="text-muted mb-3">You don’t have any orders yet.</p>
              <a href="{{ route('shop.index') }}" class="btn btn-theme btn-black">Start shopping</a>
            </div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($orders as $order)
                    @php
                      $orderStatus = strtolower($order->status);
                      $statusMap = [
                          'pending' => 'warning',
                          'paid' => 'success',
                          'shipped' => 'info',
                          'cancelled' => 'danger',
                      ];
                      $orderStatusClass = $statusMap[$orderStatus] ?? 'secondary';
                    @endphp
                    <tr>
                      <td class="fw-semibold">{{ $order->order_code }}</td>
                      <td>
                        <span class="badge bg-{{ $orderStatusClass }} text-uppercase">{{ $order->status }}</span>
                      </td>
                      <td class="fw-semibold text-dark">${{ number_format($order->total_amount, 2) }}</td>
                      <td class="text-muted">{{ $order->created_at?->format('M d, Y') }}</td>
                      <td class="text-end">
                        <a href="{{ route('shop.orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm">View Order</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
