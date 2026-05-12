@extends('layouts.app')

@section('title', 'Profile')
@section('body_class', 'profile-page')

@section('content')
    <main class="profile-main">
        <section class="profile-section" aria-labelledby="profile-heading">
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="{{ $user->avatar ?? 'https://i1.sndcdn.com/avatars-tDQKBExQks6cE0zh-HO3N7Q-t240x240.jpg' }}" alt="{{ $user->full_name }}">
                </div>
                <div class="profile-info">
                    <h1 id="profile-heading">{{ $user->full_name }}</h1>
                    <p class="profile-handle">@ {{ strtolower(preg_replace('/[^a-z0-9]/', '', $user->full_name)) }}</p>
                    <div class="profile-meta">
                        <span><i data-lucide="mail"></i> {{ $user->email }}</span>
                        <span><i data-lucide="phone"></i> {{ $user->phone ?: 'Unknown' }}</span>
                        <span><i data-lucide="user"></i> {{ $user->gender ?: 'Hidden' }}</span>
                        <span><i data-lucide="map-pin"></i> {{ $user->address ?: 'Unknown' }}</span>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="edit-form-button edit-form-button--submit">Edit Profile</a>
                </div>
            </div>

            @if (session('success'))
                <div class="auth-success" style="color: #2a9d8f; margin-bottom: 1rem; font-size: 0.9rem;">{{ session('success') }}</div>
            @endif

            <div class="profile-tabs">
                <section class="profile-orders">
                    <h2>My Orders</h2>
                    @if ($orders->isEmpty())
                        <p class="profile-empty">No orders yet.</p>
                    @else
                        <div class="orders-list">
                            @foreach ($orders as $order)
                                <article class="order-card">
                                    <div class="order-header">
                                        <span>Order #{{ $order->id }}</span>
                                        <span class="order-status order-status--{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                    </div>
                                    <div class="order-items">
                                        @foreach ($order->items as $item)
                                            <div class="order-item">
                                                <span>{{ $item->product_name }} (x{{ $item->quantity }})</span>
                                                <strong>${{ number_format($item->product_price * $item->quantity, 2) }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="order-footer">
                                        <span>Total: ${{ number_format($order->total, 2) }}</span>
                                        <span>{{ $order->created_at->format('M d, Y') }}</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>

                <section class="profile-favorites">
                    <h2>My Favorites</h2>
                    @if ($favorites->isEmpty())
                        <p class="profile-empty">No favorites yet.</p>
                    @else
                        <div class="product-grid">
                            @foreach ($favorites as $product)
                                <article class="product-card">
                                    <a class="product-image" href="{{ route('product.show', ['slug' => $product->slug]) }}" aria-label="View {{ $product->name }}">
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                                    </a>
                                    <div class="product-info">
                                        <p>{{ $product->brand }}</p>
                                        <h3>{{ $product->name }}</h3>
                                    </div>
                                    <div class="product-actions">
                                        <strong>${{ number_format($product->price, 2) }}</strong>
                                        <form action="{{ route('favorites.toggle') }}" method="post" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="cart-button">Remove</button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>
        </section>
    </main>
@endsection
