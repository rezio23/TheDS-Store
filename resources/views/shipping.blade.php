@extends('layouts.app')

@section('title', 'Shipping')
@section('body_class', 'shipping-page')

@section('content')
    <main class="shipping-main">
        <section class="shipping-section" aria-labelledby="shipping-heading">
            <h1 id="shipping-heading">Shipping Information</h1>

            <form class="shipping-form" method="POST" action="{{ route('shipping') }}">
                @csrf

                @if ($errors->any())
                    <div class="auth-errors" style="color: #e63946; margin-bottom: 1rem; font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <p style="margin: 0.25rem 0;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="edit-form-group">
                    <label class="edit-form-label" for="shipping_name">Full Name</label>
                    <input class="edit-form-input" id="shipping_name" name="shipping_name" type="text" value="{{ old('shipping_name', $user->full_name ?? '') }}" required>
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="shipping_email">Email</label>
                    <input class="edit-form-input" id="shipping_email" name="shipping_email" type="email" value="{{ old('shipping_email', $user->email ?? '') }}" required>
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="shipping_phone">Phone</label>
                    <input class="edit-form-input" id="shipping_phone" name="shipping_phone" type="tel" value="{{ old('shipping_phone', $user->phone ?? '') }}" required>
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="shipping_address">Address</label>
                    <textarea class="edit-form-input" id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address', $user->address ?? '') }}</textarea>
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="shipping_postal">Postal Code</label>
                    <input class="edit-form-input" id="shipping_postal" name="shipping_postal" type="text" value="{{ old('shipping_postal') }}" required>
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label">Shipping Method</label>
                    <div class="shipping-options">
                        @foreach ($shippingOptions as $option)
                            <label class="shipping-option">
                                <input type="radio" name="shipping_mode" value="{{ $option['value'] }}" {{ $loop->first ? 'checked' : '' }}>
                                <span class="shipping-option-label">{{ $option['label'] }}</span>
                                <span class="shipping-option-price">${{ number_format($option['price'], 2) }}</span>
                                <span class="shipping-option-time">{{ $option['time'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="edit-form-actions">
                    <button type="submit" class="edit-form-button edit-form-button--submit edit-form-button--full">Continue to Payment</button>
                </div>
            </form>
        </section>
    </main>
@endsection
