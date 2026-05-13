@extends('layouts.app')

@section('title', 'Profile | The DS')
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
                <p>{{ '@' . strtolower(preg_replace('/[^a-z0-9]/', '', $user->full_name)) }}</p>

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

                <a href="{{ route('profile.edit') }}" class="profile-edit-link" data-edit-open>Edit Profile</a>
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

    <div class="edit-profile-overlay" id="edit-profile-modal" aria-hidden="true">
        <div class="edit-profile-modal" role="dialog" aria-modal="true" aria-labelledby="edit-profile-title">
            <button type="button" class="edit-profile-close" data-edit-close aria-label="Close edit profile">
                <i data-lucide="x"></i>
            </button>
            <h2 id="edit-profile-title">Edit Personal Detail</h2>
            <hr class="edit-form-divider">
            <form class="edit-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                @if ($errors->any())
                    <div class="auth-errors" style="color: #e63946; margin-bottom: 1rem; font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <p style="margin: 0.25rem 0;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-full-name">Full name</label>
                    <input class="edit-form-input" id="edit-full-name" name="full_name" type="text" value="{{ old('full_name', $user->full_name) }}" placeholder="e.g. John Smith">
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-username">Username</label>
                    <input class="edit-form-input" id="edit-username" name="username" type="text" value="{{ '@' . strtolower(preg_replace('/[^a-z0-9]/', '', $user->full_name)) }}" placeholder="e.g. johnsmith123">
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-gender">Gender</label>
                    <select class="edit-form-input edit-form-select" id="edit-gender" name="gender">
                        <option value="Hidden" {{ old('gender', $user->gender) === 'Hidden' ? 'selected' : '' }}>Hidden</option>
                        <option value="Male" {{ old('gender', $user->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $user->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $user->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-profile-pic">Profile Picture</label>
                    <div class="edit-form-file-wrap">
                        <span class="edit-form-file-text" id="edit-file-text">Browser File</span>
                        <input id="edit-profile-pic" name="avatar" type="file" accept="image/*" aria-label="Profile picture">
                    </div>
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-address">Address</label>
                    <input class="edit-form-input" id="edit-address" name="address" type="text" value="{{ old('address', $user->address) }}" placeholder="e.g. Toul Kork, Cambodia">
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="edit-phone">Phone</label>
                    <input class="edit-form-input" id="edit-phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 85511 223 344">
                </div>
                <div class="edit-form-actions">
                    <button type="button" class="edit-form-button edit-form-button--cancel" data-edit-close>Cancel</button>
                    <button type="submit" class="edit-form-button edit-form-button--submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

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
