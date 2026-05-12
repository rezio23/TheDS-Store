@extends('layouts.app')

@section('title', 'Payment')
@section('body_class', 'payment-page')

@section('content')
    <main class="payment-main">
        <section class="payment-section" aria-labelledby="payment-heading">
            <h1 id="payment-heading">Payment</h1>

            <div class="payment-summary">
                <h2>Order Summary</h2>
                @foreach ($cart as $item)
                    <div class="payment-item">
                        <span>{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                        <strong>${{ number_format($item['price'] * $item['quantity'], 2) }}</strong>
                    </div>
                @endforeach
                <hr>
                <div class="payment-item">
                    <span>Subtotal</span>
                    <strong>${{ number_format($subtotal, 2) }}</strong>
                </div>
                <div class="payment-item">
                    <span>Shipping</span>
                    <strong>${{ number_format($shippingPrice, 2) }}</strong>
                </div>
                <div class="payment-item payment-total">
                    <span>Total</span>
                    <strong>${{ number_format($total, 2) }}</strong>
                </div>
            </div>

            <form class="payment-form" method="POST" action="{{ route('payment') }}">
                @csrf

                <div class="edit-form-group">
                    <label class="edit-form-label" for="card_name">Name on Card</label>
                    <input class="edit-form-input" id="card_name" name="card_name" type="text" placeholder="John Smith" required>
                </div>

                <div class="edit-form-group">
                    <label class="edit-form-label" for="card_number">Card Number</label>
                    <input class="edit-form-input" id="card_number" name="card_number" type="text" placeholder="0000 0000 0000 0000" required>
                </div>

                <div class="edit-form-row">
                    <div class="edit-form-group">
                        <label class="edit-form-label" for="card_expiry">Expiry Date</label>
                        <input class="edit-form-input" id="card_expiry" name="card_expiry" type="text" placeholder="MM/YY" required>
                    </div>
                    <div class="edit-form-group">
                        <label class="edit-form-label" for="card_cvc">CVC</label>
                        <input class="edit-form-input" id="card_cvc" name="card_cvc" type="text" placeholder="123" required>
                    </div>
                </div>

                <div class="edit-form-actions">
                    <button type="submit" class="edit-form-button edit-form-button--submit edit-form-button--full">Place Order</button>
                </div>
            </form>
        </section>
    </main>
@endsection
