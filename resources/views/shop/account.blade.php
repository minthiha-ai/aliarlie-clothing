@extends('layouts.app')

@section('title', 'My Account - AliarLIE Clothing')

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
      $status = strtolower($customer?->status ?? 'active');
      $statusClasses = [
          'active' => 'success',
          'inactive' => 'secondary',
          'pending' => 'warning',
          'banned' => 'danger',
      ];
      $statusClass = $statusClasses[$status] ?? 'secondary';
      $recentOrders = $orders->take(5);
    @endphp

    <section class="account-dashboard-area mb-5 mt-5">
      <div class="container">
        @if ($customer && ! $customer->hasVerifiedEmail())
          <div class="alert alert-warning d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
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
        <div class="row g-4" id="orders">
          <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4 text-center">
                <div class="account-avatar rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center mb-3 overflow-hidden">
                  @if ($customer?->google_avatar)
                    <img src="{{ $customer->google_avatar }}" alt="{{ $customerName }}">
                  @else
                    {{ $avatarLetter }}
                  @endif
                </div>
                <h4 class="mb-1">{{ $customerName }}</h4>
                <p class="text-muted mb-3">Welcome back to AliarLIE Clothing.</p>
                <ul class="list-unstyled mb-4">
                  <li class="mb-2"><strong>Email:</strong> {{ $customer?->email ?? 'N/A' }}</li>
                  <li class="mb-2"><strong>Phone:</strong> {{ $customer?->phone ?? 'N/A' }}</li>
                  <li>
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $statusClass }} text-uppercase ms-2">{{ $status }}</span>
                  </li>
                </ul>
                <form method="POST" action="{{ route('shop.account.logout') }}">
                  @csrf
                  <button class="btn btn-dark w-100" type="submit">Log out</button>
                </form>
              </div>
            </div>
            <div class="card border-0 shadow-sm mt-4">
              <div class="card-body p-4">
                <h5 class="mb-3">Edit Profile</h5>
                <form method="POST" action="{{ route('shop.account.profile.update') }}">
                  @csrf
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
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="profile_phone" name="phone" value="{{ old('phone', $customer?->phone) }}" required>
                        @error('phone')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="profile_password" class="form-label">New password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="profile_password" name="password">
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
                    <div class="col-12">
                      <button class="btn btn-theme btn-black w-100" type="submit">Save changes</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                  <h4 class="mb-0">Recent Orders</h4>
                  <span class="text-muted small">Latest activity</span>
                </div>
                @if ($recentOrders->isEmpty())
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
                        @foreach ($recentOrders as $order)
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
                <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <a href="{{ route('shop.orders.index') }}" class="btn btn-link">View all orders</a>
                  <span class="text-muted small">Showing latest 5 orders</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-5">
          <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <h4 class="mb-3">Saved Addresses</h4>
                @if ($addresses->isEmpty())
                  <div class="text-center py-3">
                    <p class="mb-3 text-muted">No saved addresses yet.</p>
                    <a href="#address-form" class="btn btn-outline-dark btn-sm">Create your first address</a>
                  </div>
                @else
                  <div class="row g-4">
                    @foreach ($addresses as $address)
                      <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 p-3 h-100 address-card">
                          <h6 class="mb-2">{{ $address->receiver_name }}</h6>
                          <p class="mb-1 text-muted">{{ $address->phone }}</p>
                          <p class="mb-1">{{ $address->address }}</p>
                          <p class="mb-0">{{ $address->township }}, {{ $address->city }}</p>
                          <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-outline-secondary btn-sm" type="button" disabled>Edit</button>
                            <button class="btn btn-outline-secondary btn-sm" type="button" disabled>Delete</button>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @endif

                <div class="mt-4" id="address-form">
                  <div class="border rounded-3 p-4">
                    <h5 class="mb-3">Add new address</h5>
                    <form method="POST" action="{{ route('shop.account.addresses.store') }}">
                      @csrf
                      <div class="row g-3">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="receiver_name" class="form-label">Full name *</label>
                            <input type="text" class="form-control @error('receiver_name') is-invalid @enderror" id="receiver_name" name="receiver_name" value="{{ old('receiver_name') }}" required>
                            @error('receiver_name')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="phone" class="form-label">Phone *</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="township" class="form-label">Township *</label>
                            <input type="text" class="form-control @error('township') is-invalid @enderror" id="township" name="township" value="{{ old('township') }}" required>
                            @error('township')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                        <div class="col-12">
                          <button class="btn btn-theme btn-black w-100" type="submit">Save address</button>
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
    </section>
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
    .account-avatar {
      width: 72px;
      height: 72px;
      font-size: 1.75rem;
    }
    .account-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .address-card {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .address-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }
  </style>
@endpush
@endsection
