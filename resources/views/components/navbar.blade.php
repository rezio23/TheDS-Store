@php
$cart = session('cart', []);
$cartCount = array_sum(array_column($cart, 'quantity'));
@endphp

<header class="site-header" id="header-top">
    <a class="brand-mark" href="{{ url('/') }}#home" aria-label="The DS home">the DS</a>

    <nav class="site-nav" aria-label="Primary navigation">
        <a href="{{ url('/') }}#home_text" class="{{ request()->is('/') ? 'is-active' : '' }}">Home</a>
        <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'is-active' : '' }}">Shop</a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'is-active' : '' }}">About</a>
        <a href="{{ route('help-center') }}" class="{{ request()->routeIs('help-center') ? 'is-active' : '' }}">Help</a>
        <a href="{{ url('/') }}#new">New</a>
    </nav>

    <div class="header-actions" aria-label="Store actions">
        <div class="header-search" data-header-search>
            <div class="header-search-field">
                <input class="header-search-input" id="header-product-search" data-product-search type="search" placeholder="Search products..." aria-label="Search products" autocomplete="off" tabindex="-1" aria-hidden="true">
                <div class="header-search-results" data-header-search-results hidden></div>
            </div>
            <button class="icon-button search-trigger" type="button" aria-label="Open search" aria-controls="header-product-search" aria-expanded="false" title="Search">
                <i data-lucide="search"></i>
            </button>
        </div>
        <a class="icon-button bag-button {{ request()->routeIs('cart') ? 'is-active' : '' }}" href="{{ route('cart') }}" aria-label="Shopping bag" title="Bag">
            <i data-lucide="shopping-bag"></i>
            <span class="bag-count" aria-live="polite">{{ $cartCount }}</span>
        </a>
        @auth
            <a class="icon-button {{ request()->routeIs('profile') || request()->routeIs('profile.edit') ? 'is-active' : '' }}" href="{{ route('profile') }}" aria-label="Account profile" title="{{ Auth::user()->full_name }}">
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
