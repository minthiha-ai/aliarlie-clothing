@php
    $currentRoute = request()->route()->getName();
    $customer = Auth::guard('customer')->user();
    $customerName = $customer?->name ?? 'Customer';
@endphp

<div class="account-sidebar">
    <div class="account-greeting mb-4">
        <h5 class="mb-0">Hello, {{ $customerName }}</h5>
    </div>
    
    <nav class="account-nav">
        <div class="nav-section">
            <h6 class="nav-section-title {{ str_starts_with($currentRoute, 'shop.account') ? 'active' : '' }}">Manage My Account</h6>
            <ul class="nav-section-list">
                <li>
                    <a href="{{ route('shop.account') }}" class="nav-link {{ $currentRoute === 'shop.account' ? 'active' : '' }}">
                        My Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('shop.account.addresses') }}" class="nav-link {{ $currentRoute === 'shop.account.addresses' ? 'active' : '' }}">
                        Address Book
                    </a>
                </li>
                <li>
                    <a href="{{ route('shop.account.payments') }}" class="nav-link {{ $currentRoute === 'shop.account.payments' ? 'active' : '' }}">
                        My Payment Options
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="nav-section">
            <h6 class="nav-section-title {{ str_starts_with($currentRoute, 'shop.orders') ? 'active' : '' }}">My Orders</h6>
            <ul class="nav-section-list">
                <li>
                    <a href="{{ route('shop.orders.index') }}" class="nav-link {{ $currentRoute === 'shop.orders.index' ? 'active' : '' }}">
                        All Orders
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
