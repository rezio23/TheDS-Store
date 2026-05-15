@php
$cart = session('cart', []);
$cartCount = array_sum(array_column($cart, 'quantity'));

$headerId = match (true) {
    request()->is('/') => 'home',
    request()->routeIs('shop') => 'shop-top',
    request()->routeIs('about') => 'about-top',
    request()->routeIs('cart') => 'cart-top',
    request()->routeIs('payment') => 'payment-top',
    request()->routeIs('shipping') => 'shipping-top',
    request()->routeIs('profile') => 'profile-top',
    request()->routeIs('help-center') => 'help-top',
    request()->routeIs('terms') => 'terms-top',
    request()->routeIs('product.show') => 'product-top',
    default => 'header-top',
};

$searchId = match ($headerId) {
    'home' => 'header-product-search',
    'shop-top' => 'header-product-search',
    'about-top' => 'header-about-search',
    'cart-top' => 'header-cart-search',
    'payment-top' => 'header-payment-search',
    'shipping-top' => 'header-shipping-search',
    'profile-top' => 'header-profile-search',
    'help-top' => 'header-help-search',
    'terms-top' => 'header-terms-search',
    'product-top' => 'header-product-search',
    default => 'header-product-search',
};
@endphp

<header class="site-header" id="{{ $headerId }}">
    <a class="brand-mark" href="{{ url('/') }}#home" aria-label="The DS home">the DS</a>

    <nav class="site-nav" aria-label="Primary navigation">
        <a href="{{ url('/') }}#home_text" class="{{ request()->is('/') ? 'is-active' : '' }}">Home</a>
        <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'is-active' : '' }}">Shop</a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'is-active' : '' }}">About</a>
        <a href="{{ route('help-center') }}" class="{{ request()->routeIs('help-center') ? 'is-active' : '' }}">Help</a>
        <a href="{{ url('/') }}#new">New</a>
    </nav>

    <div class="header-actions" aria-label="Store actions">
        @if(request()->is('/') || request()->routeIs('shop'))
        <div class="header-search" data-header-search>
            <div class="header-search-field">
                <input class="header-search-input" id="{{ $searchId }}" data-product-search type="search" placeholder="Search products..." aria-label="Search products" autocomplete="off" tabindex="-1" aria-hidden="true">
                <div class="header-search-results" data-header-search-results hidden></div>
            </div>
            <button class="icon-button search-trigger" type="button" aria-label="Open search" aria-controls="{{ $searchId }}" aria-expanded="false" title="Search">
                <i data-lucide="search"></i>
            </button>
        </div>
        @endif
        @auth
            @if(!request()->is('/') && !request()->routeIs('shop'))
            <div class="header-notifications" data-header-notifications>
                <button class="icon-button notification-trigger {{ request()->routeIs('profile') ? 'is-active' : '' }}" type="button" aria-label="Notifications" aria-controls="header-notification-panel" aria-expanded="false" title="Notifications" data-notification-trigger>
                    <i data-lucide="bell"></i>
                    <span class="notification-count" aria-live="polite" data-notification-count hidden>0</span>
                </button>
                <div class="header-notification-panel" id="header-notification-panel">
                    <div class="header-notification-header">
                        <strong>Notifications</strong>
                        <button type="button" class="header-notification-mark-all" data-mark-all-read>Mark all as read</button>
                    </div>
                    <div class="header-notification-list" data-notification-list></div>
                    <a href="{{ route('profile') }}#notifications" class="header-notification-footer">View all</a>
                </div>
            </div>
            @endif
        @endauth
        <a class="icon-button bag-button {{ request()->routeIs('cart') ? 'is-active' : '' }}" href="{{ route('cart') }}" aria-label="Shopping bag" title="Bag">
            <i data-lucide="shopping-bag"></i>
            <span class="bag-count" aria-live="polite">{{ $cartCount }}</span>
        </a>
        @auth
            <a class="icon-button {{ request()->routeIs('profile') || request()->routeIs('profile.edit') ? 'is-active' : '' }}" href="{{ route('profile') }}" aria-label="Account profile" title="{{ Auth::user()->full_name ?: 'Account' }}">
                <i data-lucide="user-round"></i>
            </a>
        @else
            <a class="icon-button {{ request()->routeIs('login') ? 'is-active' : '' }}" href="{{ route('login') }}" aria-label="Account login" title="Account">
                <i data-lucide="user-round"></i>
            </a>
        @endauth
        <button class="icon-button nav-toggle" type="button" aria-label="Open menu" aria-expanded="false" title="Menu">
            <i data-lucide="menu" class="nav-toggle-open"></i>
            <i data-lucide="x" class="nav-toggle-close"></i>
        </button>
    </div>
</header>
