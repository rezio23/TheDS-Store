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
            <div class="form-errors" style="max-width: 800px; margin: 1rem auto; background: #fff; border: 1px solid #e0e0e0; border-left: 4px solid #e63946; border-radius: 10px; padding: 1rem 1.25rem; display: flex; align-items: flex-start; gap: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                <span style="display:flex;align-items:center;justify-content:center;width:28px;height:28px;background:#fdeaea;border-radius:50%;flex-shrink:0;margin-top:2px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </span>
                <div style="flex:1;">
                    @foreach ($errors->all() as $error)
                        <p style="margin:0.15rem 0;color:#1a1a1a;font-size:0.9rem;">{{ $error }}</p>
                    @endforeach
                </div>
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
                            <input type="text" id="ship-full-name" name="full_name" value="{{ old('full_name', $user->full_name ?? '') }}" minlength="2" maxlength="255" autocomplete="name" required>
                        </div>
                        <div class="shipping-form-group">
                            <label for="ship-phone">Phone</label>
                            <input type="tel" id="ship-phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" minlength="7" maxlength="20" pattern="[\d\s\-\+\(\)]+" title="Enter a valid phone number" autocomplete="tel" required>
                        </div>
                    </div>

                    <div class="shipping-form-row">
                        <div class="shipping-form-group">
                            <label for="ship-address-1">Address Line 1</label>
                            <input type="text" id="ship-address-1" name="address_1" value="{{ old('address_1', $user->address ?? '') }}" minlength="5" maxlength="255" autocomplete="address-line1" required>
                        </div>
                        <div class="shipping-form-group">
                            <label for="ship-address-2">Address Line 2</label>
                            <input type="text" id="ship-address-2" name="address_2" value="{{ old('address_2') }}" maxlength="255" autocomplete="address-line2">
                        </div>
                    </div>

                    <div class="shipping-form-row">
                        <div class="shipping-form-group shipping-form-group--wide">
                            <label for="ship-description">Description</label>
                            <input type="text" id="ship-description" name="description" placeholder="Enter a description..." maxlength="500">
                        </div>
                    </div>

                    <div class="shipping-form-row">
                        <div class="shipping-form-group">
                            <label for="ship-postal">Postal Code</label>
                            <input type="text" id="ship-postal" name="postal_code" value="{{ old('postal_code') }}" maxlength="20" pattern="[A-Za-z0-9\s\-]+" title="Enter a valid postal code" autocomplete="postal-code" required>
                        </div>
                        <div class="shipping-form-group">
                            <label for="ship-email">Email</label>
                            <input type="email" id="ship-email" name="email" value="{{ old('email', $user->email ?? '') }}" maxlength="255" autocomplete="email" required>
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

                @if (session('promo_error'))
                    <div style="background:#fff;border:1px solid #e0e0e0;border-left:4px solid #e63946;border-radius:10px;padding:10px 14px;display:flex;align-items:center;gap:0.6rem;box-shadow:0 4px 12px rgba(0,0,0,0.06);margin-bottom:12px;">
                        <span style="display:flex;align-items:center;justify-content:center;width:24px;height:24px;background:#fdeaea;border-radius:50%;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </span>
                        <span style="color:#1a1a1a;font-size:0.8rem;">{{ session('promo_error') }}</span>
                    </div>
                @endif
                @if (session('promo_success'))
                    <div style="background:#fff;border:1px solid #e0e0e0;border-left:4px solid #2a9d8f;border-radius:10px;padding:10px 14px;display:flex;align-items:center;gap:0.6rem;box-shadow:0 4px 12px rgba(0,0,0,0.06);margin-bottom:12px;">
                        <span style="display:flex;align-items:center;justify-content:center;width:24px;height:24px;background:#e6f4f1;border-radius:50%;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2a9d8f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </span>
                        <span style="color:#1a1a1a;font-size:0.8rem;">{{ session('promo_success') }}</span>
                    </div>
                @endif

                @if ($promoCode && $discount > 0)
                    <div class="shipping-promo">
                        <span class="shipping-promo-code"><i data-lucide="tag"></i> {{ strtoupper($promoCode) }}</span>
                        <form method="post" action="{{ route('remove-promo') }}">
                            @csrf
                            <button type="submit" class="shipping-promo-remove">Remove</button>
                        </form>
                    </div>
                @else
                    <form method="post" action="{{ route('apply-promo') }}" class="shipping-promo">
                        @csrf
                        <input type="text" name="promo_code" placeholder="Apply Promo Code" aria-label="Promo code" value="{{ old('promo_code') }}" maxlength="255">
                        <button type="submit">Apply</button>
                    </form>
                @endif

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
                    @if ($discount > 0)
                        <div style="color:#228b22;">
                            <dt>Discount</dt>
                            <dd>-$ {{ number_format($discount, 2) }}</dd>
                        </div>
                    @endif
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
