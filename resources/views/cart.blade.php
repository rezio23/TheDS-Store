@extends('layouts.app')

@section('title', 'Cart')
@section('body_class', 'cart-page')

@section('content')
    <main class="cart-main">
        <section class="cart-section" aria-labelledby="cart-heading">
            <div class="cart-heading">
                <h1 id="cart-heading">Your Bag</h1>
                <p>{{ count($cart) }} item(s)</p>
            </div>

            @if (empty($cart))
                <div class="cart-empty">
                    <p>Your bag is empty.</p>
                    <a href="{{ route('shop') }}" class="edit-form-button edit-form-button--submit">Continue Shopping</a>
                </div>
            @else
                <div class="cart-items">
                    @foreach ($cart as $key => $item)
                        <article class="cart-item">
                            <div class="cart-item-media">
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                            </div>
                            <div class="cart-item-info">
                                <p class="cart-item-brand">{{ $item['brand'] }}</p>
                                <h3>{{ $item['name'] }}</h3>
                                <p class="cart-item-size">Size: {{ $item['size'] }}</p>
                                <p class="cart-item-price">${{ number_format($item['price'], 2) }}</p>
                            </div>
                            <div class="cart-item-actions">
                                <form action="{{ route('cart.update') }}" method="post" class="cart-qty-form">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $key }}">
                                    <button type="submit" name="quantity" value="{{ max(0, $item['quantity'] - 1) }}" class="cart-qty-btn" aria-label="Decrease quantity">-</button>
                                    <span class="cart-qty-value">{{ $item['quantity'] }}</span>
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="cart-qty-btn" aria-label="Increase quantity">+</button>
                                </form>
                                <form action="{{ route('cart.remove') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $key }}">
                                    <button type="submit" class="cart-remove-btn" aria-label="Remove item">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="cart-summary">
                    <div class="cart-summary-row">
                        <span>Subtotal</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                    <div class="cart-summary-row">
                        <span>Shipping</span>
                        <strong>Calculated at checkout</strong>
                    </div>
                    <hr class="cart-summary-divider">
                    <div class="cart-summary-row cart-summary-total">
                        <span>Total</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                    <a href="{{ route('shipping') }}" class="edit-form-button edit-form-button--submit edit-form-button--full">Proceed to Checkout</a>
                    <a href="{{ route('shop') }}" class="cart-continue-link">Continue Shopping</a>
                </div>
            @endif
        </section>
    </main>
@endsection
