@extends('layouts.app')

@section('title', 'Shipping | The DS')
@section('body_class', 'shipping-page')

@section('content')
    <main class="shipping-main">
        <div class="shipping-header-row">
            <nav class="shipping-breadcrumb" aria-label="Checkout steps">
                <a href="{{ route('cart') }}">Cart</a>
                <span>/</span>
                <span class="is-active" aria-current="step">Shipping</span>
                <span>/</span>
                <span>Payment</span>
            </nav>
            <a href="{{ route('help-center') }}" class="shipping-help-link">Help Center</a>
        </div>

        @if ($errors->any())
            <div class="form-errors" style="max-width: 800px; margin: 1rem auto; color: #c00; background: #ffeaea; padding: 1rem; border-radius: 8px;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="shipping-layout">
            <section class="shipping-form-card" aria-label="Shipping details">
                <h2 class="shipping-card-title">Shipping Detail</h2>
                <form action="{{ route('shipping') }}" method="post" class="shipping-form" id="shipping-form">
                    @csrf
                    <div class="shipping-form-row">
                        <div class="shipping-form-group">
                            <label for="ship-full-name">Full name</label>
                            <input type="text" id="ship-full-name" name="full_name" value="{{ old('full_name', $user->full_name ?? '') }}" required>
                        </div>
                        <div class="shipping-form-group">
                            <label for="ship-phone">Phone</label>
                            <input type="tel" id="ship-phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" required>
                        </div>
                    </div>

                    <div class="shipping-form-row">
                        <div class="shipping-form-group">
                            <label for="ship-address-1">Address Line 1</label>
                            <input type="text" id="ship-address-1" name="address_1" value="{{ old('address_1', $user->address ?? '') }}" required>
                        </div>
                        <div class="shipping-form-group">
                            <label for="ship-address-2">Address Line 2</label>
                            <input type="text" id="ship-address-2" name="address_2" value="{{ old('address_2') }}">
                        </div>
                    </div>

                    <div class="shipping-form-row">
                        <div class="shipping-form-group shipping-form-group--wide">
                            <label for="ship-description">Description</label>
                            <input type="text" id="ship-description" name="description" placeholder="Enter a description...">
                        </div>
                    </div>

                    <div class="shipping-form-row">
                        <div class="shipping-form-group">
                            <label for="ship-postal">Postal Code</label>
                            <input type="text" id="ship-postal" name="postal_code" value="{{ old('postal_code') }}" required>
                        </div>
                        <div class="shipping-form-group">
                            <label for="ship-email">Email</label>
                            <input type="email" id="ship-email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                        </div>
                    </div>

                    <fieldset class="shipping-mode">
                        <legend>Shipping Mode</legend>
                        <div class="shipping-mode-options">
                            <label class="shipping-mode-option">
                                <input type="radio" name="shipping_mode" value="standard" {{ old('shipping_mode', 'standard') === 'standard' ? 'checked' : '' }}>
                                <span class="shipping-mode-check"></span>
                                <span class="shipping-mode-info">
                                    <strong>Standard Delivery</strong>
                                    <span>9 - 14 Days</span>
                                </span>
                                <span class="shipping-mode-price">2.99$</span>
                            </label>
                            <label class="shipping-mode-option">
                                <input type="radio" name="shipping_mode" value="fast" {{ old('shipping_mode') === 'fast' ? 'checked' : '' }}>
                                <span class="shipping-mode-check"></span>
                                <span class="shipping-mode-info">
                                    <strong>Fast Delivery</strong>
                                    <span>5 - 7 Days</span>
                                </span>
                                <span class="shipping-mode-price">5.99$</span>
                            </label>
                        </div>
                    </fieldset>
                </form>
            </section>

            <aside class="shipping-summary-card" aria-label="Order summary">
                <h2 class="shipping-card-title">Cart</h2>
                <div class="shipping-cart-items">
                    @foreach (array_values($cart) as $index => $item)
                        <div class="shipping-cart-item">
                            <div class="shipping-cart-thumb">
                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                <span class="shipping-cart-badge">{{ $index + 1 }}</span>
                            </div>
                            <div class="shipping-cart-info">
                                <strong>{{ $item['name'] }}</strong>
                                <span>Quantity: {{ $item['quantity'] }}</span>
                                <span class="shipping-cart-size">Size: {{ $item['size'] ?? 'One Size' }}</span>
                            </div>
                            <span class="shipping-cart-price">$ {{ number_format($item['price'], 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="shipping-promo">
                    <input type="text" placeholder="Apply Promo Code" aria-label="Promo code">
                    <button type="button">Apply</button>
                </div>

                <hr class="shipping-divider">

                <dl class="shipping-costs">
                    <div>
                        <dt>Subtotal</dt>
                        <dd>$ {{ number_format($subtotal, 2) }}</dd>
                    </div>
                    <div>
                        <dt>Shipping</dt>
                        <dd>$ {{ number_format($shippingPrice, 2) }}</dd>
                    </div>
                    <div>
                        <dt>Taxes (1.8%)</dt>
                        <dd>$ {{ number_format($taxes, 2) }}</dd>
                    </div>
                </dl>

                <hr class="shipping-divider">

                <div class="shipping-total">
                    <span>Total</span>
                    <strong>$ {{ number_format($total, 2) }}</strong>
                </div>

                <button type="submit" form="shipping-form" class="shipping-checkout-btn">Continue to Payment</button>
            </aside>
        </div>
    </main>
@endsection
