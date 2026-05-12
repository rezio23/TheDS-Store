@extends('layouts.app')

@section('title', 'Profile')
@section('body_class', 'profile-page')

@section('content')
    <main class="profile-main">
        @if (request('ordered'))
            <div class="form-success" style="grid-column: 1 / -1; max-width: 800px; margin: 0 auto 1rem; color: #070; background: #eaffea; padding: 1rem; border-radius: 8px; text-align: center;">
                <p>Your order has been placed successfully!</p>
            </div>
        @endif

        <aside class="profile-sidebar" aria-label="Profile summary">
            <div class="profile-card">
                <img class="profile-avatar" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://i1.sndcdn.com/avatars-tDQKBExQks6cE0zh-HO3N7Q-t240x240.jpg' }}" alt="{{ $user->full_name }}">
                <h1>{{ $user->full_name }}</h1>
                <p>@ {{ strtolower(preg_replace('/[^a-z0-9]/', '', $user->full_name)) }}</p>

                <dl class="profile-detail-list">
                    <div>
                        <dt>Email</dt>
                        <dd>{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt>Gender</dt>
                        <dd>{{ $user->gender ?: 'Hidden' }}</dd>
                    </div>
                    <div>
                        <dt>Phone</dt>
                        <dd>{{ $user->phone ?: 'Unknown' }}</dd>
                    </div>
                    <div>
                        <dt>Location</dt>
                        <dd>{{ $user->address ?: 'Unknown' }}</dd>
                    </div>
                </dl>

                <a href="{{ route('profile.edit') }}" class="profile-edit-link">Edit Profile</a>
            </div>

            <nav class="profile-footer-links" aria-label="Profile actions">
                <a href="{{ route('terms') }}">Terms &amp; Conditions</a>
                <span aria-hidden="true"></span>
                <a href="{{ route('logout') }}" data-logout>Logout</a>
            </nav>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </aside>

        <div class="profile-content">
            <section class="profile-products-section" data-profile-section aria-labelledby="profile-orders-title">
                <div class="profile-section-heading">
                    <button
                        class="profile-panel-toggle"
                        id="profile-orders-title"
                        type="button"
                        data-product-toggle
                        aria-expanded="true"
                        aria-controls="profile-orders-panel"
                    >
                        <span>Your Order</span>
                        <i data-lucide="chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="profile-section-controls" aria-label="Your Order controls">
                        <button type="button" data-profile-carousel-prev aria-label="Previous Your Order">
                            <i data-lucide="chevron-left"></i>
                        </button>
                        <button type="button" data-profile-carousel-next aria-label="Next Your Order">
                            <i data-lucide="chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="profile-product-viewport product-panel-content profile-panel-content" id="profile-orders-panel" data-profile-carousel-panel>
                    <div class="profile-product-grid" data-profile-carousel>
                        @forelse ($orders as $order)
                            @php
                                $firstItem = $order->items->first();
                                $cardImage = $firstItem ? asset('storage/' . $firstItem->product_image) : '';
                                $cardName = $firstItem ? $firstItem->product_name : 'Order #' . $order->id;
                                $cardBrand = $firstItem ? $firstItem->product_brand : 'The DS';
                                $cardDesc = 'Order total: $' . number_format($order->total, 2) . ' | Status: ' . ucfirst($order->status);
                                $cardPrice = $firstItem ? (float) $firstItem->product_price : 0;
                            @endphp
                            <article class="profile-product-card">
                                <a class="profile-product-image" href="{{ route('shop') }}" aria-label="View {{ $cardName }}">
                                    @if ($cardImage)
                                        <img src="{{ $cardImage }}" alt="{{ $cardName }}">
                                    @else
                                        <div style="display:flex;align-items:center;justify-content:center;background:#f8f8f8;height:100%;">
                                            <i data-lucide="shopping-bag" style="width:48px;height:48px;color:#aaa;"></i>
                                        </div>
                                    @endif
                                </a>
                                <div class="profile-product-meta">
                                    <p>{{ $cardBrand }}</p>
                                    <strong>$ {{ number_format($cardPrice, 2) }}</strong>
                                </div>
                                <h3>
                                    <a href="{{ route('shop') }}">{{ $cardName }}</a>
                                </h3>
                                <p class="profile-product-copy">{{ $cardDesc }}</p>
                            </article>
                        @empty
                            <article class="profile-product-card profile-product-card--empty">
                                <a class="profile-product-image" href="{{ route('shop') }}" style="display:flex;align-items:center;justify-content:center;background:#f8f8f8;">
                                    <i data-lucide="shopping-bag" style="width:48px;height:48px;color:#aaa;"></i>
                                </a>
                                <div class="profile-product-meta">
                                    <p></p>
                                    <strong></strong>
                                </div>
                                <h3>
                                    <a href="{{ route('shop') }}">No orders yet</a>
                                </h3>
                                <p class="profile-product-copy">Browse the shop and add items to your cart to place your first order.</p>
                            </article>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="profile-products-section" data-profile-section aria-labelledby="profile-favorites-title">
                <div class="profile-section-heading">
                    <button
                        class="profile-panel-toggle"
                        id="profile-favorites-title"
                        type="button"
                        data-product-toggle
                        aria-expanded="true"
                        aria-controls="profile-favorites-panel"
                    >
                        <span>Your Favorite</span>
                        <i data-lucide="chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="profile-section-controls" aria-label="Your Favorite controls">
                        <button type="button" data-profile-carousel-prev aria-label="Previous Your Favorite">
                            <i data-lucide="chevron-left"></i>
                        </button>
                        <button type="button" data-profile-carousel-next aria-label="Next Your Favorite">
                            <i data-lucide="chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="profile-product-viewport product-panel-content profile-panel-content" id="profile-favorites-panel" data-profile-carousel-panel>
                    <div class="profile-product-grid" data-profile-carousel>
                        @forelse ($favorites as $product)
                            <article class="profile-product-card">
                                <a class="profile-product-image" href="{{ route('product.show', ['slug' => $product->slug]) }}" aria-label="View {{ $product->name }}">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                    @else
                                        <div style="display:flex;align-items:center;justify-content:center;background:#f8f8f8;height:100%;">
                                            <i data-lucide="heart" style="width:48px;height:48px;color:#aaa;"></i>
                                        </div>
                                    @endif
                                </a>
                                <div class="profile-product-meta">
                                    <p>{{ $product->brand }}</p>
                                    <strong>$ {{ number_format($product->price, 2) }}</strong>
                                </div>
                                <h3>
                                    <a href="{{ route('product.show', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
                                </h3>
                                <p class="profile-product-copy">{{ $product->description }}</p>
                            </article>
                        @empty
                            <article class="profile-product-card profile-product-card--empty">
                                <a class="profile-product-image" href="{{ route('shop') }}" style="display:flex;align-items:center;justify-content:center;background:#f8f8f8;">
                                    <i data-lucide="heart" style="width:48px;height:48px;color:#aaa;"></i>
                                </a>
                                <div class="profile-product-meta">
                                    <p></p>
                                    <strong></strong>
                                </div>
                                <h3>
                                    <a href="{{ route('shop') }}">No favorites yet</a>
                                </h3>
                                <p class="profile-product-copy">Save your favorite items here for quick access.</p>
                            </article>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </main>

    <div class="confirm-overlay" id="logout-confirm" aria-hidden="true">
        <div class="confirm-modal" role="alertdialog" aria-modal="true" aria-labelledby="logout-confirm-title" aria-describedby="logout-confirm-desc">
            <h2 id="logout-confirm-title">Log Out</h2>
            <p id="logout-confirm-desc">Are you sure you want to log out?</p>
            <div class="confirm-actions">
                <button type="button" class="confirm-btn confirm-btn--cancel" data-logout-cancel>Cancel</button>
                <a href="{{ route('logout') }}" class="confirm-btn confirm-btn--confirm" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const logoutLink = document.querySelector('[data-logout]');
            const overlay = document.getElementById('logout-confirm');
            const cancelBtn = document.querySelector('[data-logout-cancel]');

            if (logoutLink && overlay) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    overlay.classList.add('is-open');
                    overlay.setAttribute('aria-hidden', 'false');
                    cancelBtn.focus();
                });
            }

            if (cancelBtn && overlay) {
                cancelBtn.addEventListener('click', function() {
                    overlay.classList.remove('is-open');
                    overlay.setAttribute('aria-hidden', 'true');
                });
            }

            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.classList.remove('is-open');
                    overlay.setAttribute('aria-hidden', 'true');
                }
            });
        })();
    </script>
@endsection
