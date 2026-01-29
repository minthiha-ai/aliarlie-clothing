@extends('layouts.app')

@section('title', 'Manage My Account - AliarLIE Clothing')

@section('wrapper-class', '')

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
            <h2 class="title">My Account</h2>
            <div class="bread-crumbs">
              <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
              <span class="active">My Account</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Page Title Area ==-->

  @php
    $showRegister = old('register_email') || old('register_phone') || old('register_name');
  @endphp

  @auth('customer')
    @php
      $customerName = $customer?->name ?? 'Customer';
      $avatarLetter = strtoupper(substr($customerName, 0, 1));
      $recentOrders = $orders->take(5);
      $defaultShippingAddress = $addresses->first();
      $defaultBillingAddress = $addresses->first(); // For now, same as shipping
      $maskedEmail = $customer?->email ? substr($customer->email, 0, 2) . str_repeat('*', max(0, strlen($customer->email) - 12)) . substr($customer->email, -10) : 'N/A';
    @endphp

    <section class="account-dashboard-area mb-5 mt-5">
      <div class="container">
        @if ($customer && ! $customer->hasVerifiedEmail())
          <div class="alert alert-warning d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
            <div>
              <strong>Verify your email address</strong>
              <div class="small text-muted">Please verify your email to secure your account and receive order updates.</div>
            </div>
            <form method="POST" action="{{ route('shop.verification.send') }}">
              @csrf
              <button class="btn btn-dark btn-sm" type="submit">Resend verification email</button>
            </form>
          </div>
        @endif

        <div class="row">
          <!-- Sidebar Navigation -->
          <div class="col-lg-3 mb-4 mb-lg-0">
            @include('shop.partials.account-sidebar')
          </div>

          <!-- Main Content Area -->
          <div class="col-lg-9">
            <h1 class="account-page-title mb-4">Manage My Account</h1>

            <!-- Personal Profile & Address Book Row -->
            <div class="row g-4 mb-4">
              <!-- Personal Profile Card -->
              <div class="col-lg-5">
                <div class="account-card">
                  <div class="account-card-header">
                    <h5 class="account-card-title">Personal Profile</h5>
                    <button type="button" class="account-edit-link" data-bs-toggle="modal" data-bs-target="#editProfileModal">EDIT</button>
                  </div>
                  <div class="account-card-body">
                    <div class="account-profile-info">
                      <p class="account-profile-name mb-2">{{ $customerName }}</p>
                      <p class="account-profile-email mb-3">{{ $maskedEmail }}</p>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="marketingEmails" {{ old('marketing_emails', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="marketingEmails">
                          Receive marketing emails
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Address Book Card -->
              <div class="col-lg-7">
                <div class="account-card">
                  <div class="account-card-header">
                    <h5 class="account-card-title">Address Book</h5>
                    <a href="{{ route('shop.account.addresses') }}" class="account-edit-link">EDIT</a>
                  </div>
                  <div class="account-card-body">
                    <div class="row g-4">
                      <!-- Default Shipping Address -->
                      <div class="col-md-6">
                        <h6 class="account-address-label">DEFAULT SHIPPING ADDRESS:</h6>
                        @if ($defaultShippingAddress)
                          <div class="account-address-details">
                            <p class="mb-1"><strong>{{ $defaultShippingAddress->receiver_name }}</strong></p>
                            <p class="mb-1">{{ $defaultShippingAddress->address }}</p>
                            <p class="mb-1">{{ $defaultShippingAddress->township }}, {{ $defaultShippingAddress->city }}</p>
                            <p class="mb-0">{{ $defaultShippingAddress->phone }}</p>
                          </div>
                        @else
                          <p class="text-muted small mb-0">No shipping address set</p>
                        @endif
                      </div>

                      <!-- Default Billing Address -->
                      <div class="col-md-6">
                        <h6 class="account-address-label">DEFAULT BILLING ADDRESS:</h6>
                        @if ($defaultBillingAddress)
                          <div class="account-address-details">
                            <p class="mb-1"><strong>{{ $defaultBillingAddress->receiver_name }}</strong></p>
                            <p class="mb-1">{{ $defaultBillingAddress->address }}</p>
                            <p class="mb-1">{{ $defaultBillingAddress->township }}, {{ $defaultBillingAddress->city }}</p>
                            <p class="mb-0">{{ $defaultBillingAddress->phone }}</p>
                          </div>
                        @else
                          <p class="text-muted small mb-0">No billing address set</p>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Recent Orders Card -->
            <div class="account-card">
              <div class="account-card-header">
                <h5 class="account-card-title">Recent Orders</h5>
              </div>
              <div class="account-card-body">
                @if ($recentOrders->isEmpty())
                  <div class="text-center py-5">
                    <p class="text-muted mb-3">You don't have any orders yet.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-theme btn-black">Start shopping</a>
                  </div>
                @else
                  <div class="table-responsive">
                    <table class="table account-orders-table">
                      <thead>
                        <tr>
                          <th>Order #</th>
                          <th>Placed On</th>
                          <th>Items</th>
                          <th>Total</th>
                          <th class="text-end">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($recentOrders as $order)
                          @php
                            $firstItem = $order->items->first();
                            $itemImage = $firstItem?->productVariant?->product?->images?->first();
                            $imageUrl = $itemImage
                              ? \Illuminate\Support\Facades\Storage::disk('public')->url($itemImage->image_path)
                              : asset('assets/img/shop/13.jpg');
                          @endphp
                          <tr>
                            <td class="account-order-code">{{ $order->order_code }}</td>
                            <td class="account-order-date">{{ $order->created_at?->format('m/d/Y') }}</td>
                            <td>
                              <div class="account-order-item">
                                <img src="{{ $imageUrl }}" alt="Order item" class="account-order-item-image">
                              </div>
                            </td>
                            <td class="account-order-total">${{ number_format($order->total_amount, 2) }}</td>
                            <td class="text-end">
                              <a href="{{ route('shop.orders.show', $order->id) }}" class="account-manage-link">MANAGE</a>
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
        </div>
      </div>
    </section>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editProfileModalLabel">Edit Personal Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="{{ route('shop.account.profile.update') }}">
            @csrf
            <div class="modal-body">
              <div class="row g-3">
                <div class="col-12">
                  <div class="form-group">
                    <label for="profile_name" class="form-label">Full name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="profile_name" name="name" value="{{ old('name', $customer?->name) }}" required>
                    @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="profile_email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="profile_email" name="email" value="{{ old('email', $customer?->email) }}">
                    @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="profile_phone" class="form-label">Phone *</label>
                    @php
                      $currentPhone = old('phone', $customer?->phone);
                      $isGooglePlaceholder = $currentPhone && str_starts_with($currentPhone, 'google-');
                    @endphp
                    @if ($isGooglePlaceholder)
                      <div class="alert alert-info alert-sm mb-2 py-2">
                        <small><i class="icofont-info-circle me-1"></i> You signed in with Google. Please add your real phone number below.</small>
                      </div>
                    @endif
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="profile_phone" name="phone" value="{{ $isGooglePlaceholder ? '' : $currentPhone }}" placeholder="Enter your phone number" required>
                    @error('phone')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Enter your phone number (e.g., +959123456789)</small>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="profile_password" class="form-label">New password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="profile_password" name="password" placeholder="Leave blank to keep current password">
                    @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="profile_password_confirmation" class="form-label">Confirm new password</label>
                    <input type="password" class="form-control" id="profile_password_confirmation" name="password_confirmation">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-theme btn-black">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endauth

  @guest('customer')
    <div class="account-login-area">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 m-auto">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4 p-md-5">
                <div class="login-top">
                  <nav class="login-form-nav">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      <button class="nav-link {{ $showRegister ? '' : 'active' }}" id="nav-login-tab" data-bs-toggle="tab" data-bs-target="#nav-login" type="button" role="tab" aria-controls="nav-login" aria-selected="{{ $showRegister ? 'false' : 'true' }}">
                        <i class="lastudioicon-user-1"></i>Login
                      </button>
                      <button class="nav-link nav-register {{ $showRegister ? 'active' : '' }}" id="nav-register-tab" data-bs-toggle="tab" data-bs-target="#nav-register" type="button" role="tab" aria-controls="nav-register" aria-selected="{{ $showRegister ? 'true' : 'false' }}">
                        <i class="lastudioicon-user-2"></i>Register
                      </button>
                    </div>
                  </nav>
                </div>

                <div class="login-bottom">
                  <div class="login-form-content tab-content" id="nav-tabContent">
                    <div class="tab-pane fade {{ $showRegister ? '' : 'show active' }}" id="nav-login" role="tabpanel" aria-labelledby="nav-login-tab">
                      <div class="login-form">
                        <form class="login-form-wrapper" method="POST" action="{{ route('shop.account.login') }}">
                          @csrf
                          <div class="row g-3">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="login" class="form-label">Email or phone *</label>
                                <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}" required>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember"> Remember me</label>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group mb-0 form-group-info">
                                <button class="btn btn-theme btn-black w-100" type="submit">Log in</button>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="text-center text-muted small text-uppercase">or</div>
                            </div>
                            <div class="col-md-12">
                              <a href="{{ route('shop.auth.google.redirect') }}" class="btn btn-outline-dark w-100">
                                <i class="fa fa-google me-2"></i>
                                Sign in with Google
                              </a>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>

                    <div class="tab-pane fade {{ $showRegister ? 'show active' : '' }}" id="nav-register" role="tabpanel" aria-labelledby="nav-register-tab">
                      <div class="login-form">
                        <form class="login-form-wrapper" method="POST" action="{{ route('shop.account.register') }}">
                          @csrf
                          <div class="row g-3">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="register_name" class="form-label">Full name *</label>
                                <input type="text" class="form-control" id="register_name" name="register_name" value="{{ old('register_name') }}" required>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="register_phone" class="form-label">Phone *</label>
                                <input type="text" class="form-control" id="register_phone" name="register_phone" value="{{ old('register_phone') }}" required>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="register_email" class="form-label">Email address *</label>
                                <input type="email" class="form-control" id="register_email" name="register_email" value="{{ old('register_email') }}" required>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="register_password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="register_password" name="register_password" required>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="register_password_confirmation" class="form-label">Confirm password *</label>
                                <input type="password" class="form-control" id="register_password_confirmation" name="register_password_confirmation" required>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group mb-0 form-group-info">
                                <button class="btn btn-theme btn-black w-100" type="submit">Register</button>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="text-center text-muted small text-uppercase">or</div>
                            </div>
                            <div class="col-md-12">
                              <a href="{{ route('shop.auth.google.redirect') }}" class="btn btn-outline-dark w-100">
                                <i class="fa fa-google me-2"></i>
                                Sign up with Google
                              </a>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endguest

@push('styles')
  <style>
    /* Account Dashboard Styles */
    .account-dashboard-area {
      background-color: #f8f9fa;
      padding: 40px 0;
    }

    /* Sidebar Styles */
    .account-sidebar {
      background: #fff;
      border-radius: 8px;
      padding: 24px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .account-greeting h5 {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin-bottom: 0;
    }

    .account-nav {
      margin-top: 24px;
    }

    .nav-section {
      margin-bottom: 32px;
    }

    .nav-section:last-child {
      margin-bottom: 0;
    }

    .nav-section-title {
      font-size: 14px;
      font-weight: 700;
      color: #333;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 12px;
      padding-bottom: 8px;
      border-bottom: 1px solid #e8e8e8;
    }

    .nav-section-title.active {
      color: #007bff;
    }

    .nav-section-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .nav-section-list li {
      margin-bottom: 8px;
    }

    .nav-section-list li:last-child {
      margin-bottom: 0;
    }

    .nav-link {
      display: block;
      padding: 8px 0;
      color: #666;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.2s ease;
    }

    .nav-link:hover {
      color: #007bff;
    }

    .nav-link.active {
      color: #007bff;
      font-weight: 600;
    }

    /* Main Content Styles */
    .account-page-title {
      font-size: 28px;
      font-weight: 600;
      color: #333;
      margin-bottom: 24px;
    }

    .account-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      margin-bottom: 24px;
    }

    .account-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 24px;
      border-bottom: 1px solid #e8e8e8;
    }

    .account-card-title {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin: 0;
    }

    .account-edit-link {
      font-size: 12px;
      font-weight: 600;
      color: #007bff;
      text-decoration: none;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: color 0.2s ease;
    }

    .account-edit-link:hover {
      color: #0056b3;
    }

    .account-card-body {
      padding: 24px;
    }

    /* Personal Profile Styles */
    .account-profile-info {
      color: #333;
    }

    .account-profile-name {
      font-size: 16px;
      font-weight: 600;
      color: #333;
      margin: 0;
    }

    .account-profile-email {
      font-size: 14px;
      color: #666;
      margin: 0;
    }

    /* Address Book Styles */
    .account-address-label {
      font-size: 11px;
      font-weight: 700;
      color: #333;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 12px;
    }

    .account-address-details {
      font-size: 14px;
      color: #666;
      line-height: 1.6;
    }

    .account-address-details p {
      margin: 0;
    }

    /* Orders Table Styles */
    .account-orders-table {
      margin: 0;
    }

    .account-orders-table thead th {
      font-size: 12px;
      font-weight: 700;
      color: #333;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 16px 12px;
      border-bottom: 2px solid #e8e8e8;
      background: #f8f9fa;
    }

    .account-orders-table tbody td {
      padding: 16px 12px;
      vertical-align: middle;
      border-bottom: 1px solid #e8e8e8;
    }

    .account-orders-table tbody tr:last-child td {
      border-bottom: none;
    }

    .account-order-code {
      font-weight: 600;
      color: #333;
    }

    .account-order-date {
      color: #666;
      font-size: 14px;
    }

    .account-order-item {
      display: inline-block;
    }

    .account-order-item-image {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 4px;
    }

    .account-order-total {
      font-weight: 600;
      color: #333;
    }

    .account-manage-link {
      font-size: 12px;
      font-weight: 600;
      color: #007bff;
      text-decoration: none;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: color 0.2s ease;
    }

    .account-manage-link:hover {
      color: #0056b3;
    }

  /* Alert Styles */
  .alert-sm {
    padding: 8px 12px;
    font-size: 13px;
    margin-bottom: 12px;
  }

  .alert-sm small {
    display: flex;
    align-items: center;
  }

  /* Responsive */
  @media (max-width: 991.98px) {
    .account-sidebar {
      margin-bottom: 24px;
    }

    .account-page-title {
      font-size: 24px;
    }
  }

  @media (max-width: 767.98px) {
    .account-card-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }

    .account-address-label {
      margin-top: 16px;
    }

    .account-address-label:first-child {
      margin-top: 0;
    }
  }
  </style>
@endpush
@endsection
