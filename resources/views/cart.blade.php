@extends('layouts.app')

@section('title', 'Cart | The DS')
@section('body_class', 'cart-page')

@section('content')
    <main class="cart-main">
        <div class="cart-header-row">
            <nav class="cart-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}#home">Home</a>
                <span>/</span>
                <a href="{{ route('shop') }}">Shop</a>
                <span>/</span>
                <span aria-current="page">Cart</span>
            </nav>
            <a href="{{ route('help-center') }}" class="cart-help-link">Help Center</a>
        </div>

        @if (empty($cart))
            <div class="cart-empty">
                <i data-lucide="shopping-bag" aria-hidden="true"></i>
                <h2>Your cart is empty</h2>
                <p>Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('shop') }}" class="cart-checkout-btn">Continue Shopping</a>
            </div>
        @else
            <div class="cart-table-wrap">
                <div class="cart-table-header">
                    <span>Product</span>
                    <span>Unit Price</span>
                    <span>Quantity</span>
                    <span>Total</span>
                </div>

                @foreach ($cart as $key => $item)
                    <div class="cart-table-row">
                        <div class="cart-product-cell">
                            <img src="{{ storage_url($item['image']) }}" alt="{{ $item['name'] }}">
                            <div class="cart-product-meta">
                                <strong>{{ $item['name'] }}</strong>
                                <span>Size: {{ $item['size'] }}</span>
                            </div>
                        </div>
                        <div class="cart-price-cell">$ {{ number_format($item['price'], 2) }}</div>
                        <div class="cart-quantity-cell">
                            <form action="{{ route('cart.update') }}" method="post" class="cart-qty-control">
                                @csrf
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="key" value="{{ $key }}">
                                <button type="submit" class="cart-qty-btn" name="quantity" value="{{ max(0, $item['quantity'] - 1) }}" aria-label="Decrease quantity">
                                    <i data-lucide="minus" aria-hidden="true"></i>
                                </button>
                                <span class="cart-qty-value">{{ $item['quantity'] }}</span>
                                <button type="submit" class="cart-qty-btn" name="quantity" value="{{ $item['quantity'] + 1 }}" aria-label="Increase quantity">
                                    <i data-lucide="plus" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                        <div class="cart-total-cell">$ {{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <hr class="cart-summary-line">
                <div class="cart-subtotal">
                    <span>Sub Total:</span>
                    <strong>$ {{ number_format($total, 2) }}</strong>
                </div>
                <a href="{{ route('shipping') }}" class="cart-checkout-btn">Go to Checkout</a>
            </div>
        @endif
    </main>
@endsection
