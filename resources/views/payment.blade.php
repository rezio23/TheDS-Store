@extends('layouts.app')

@section('title', 'Payment | The DS')
@section('body_class', 'payment-page')

@section('content')
    <main class="payment-main">
        <div class="payment-header-row">
            <nav class="payment-breadcrumb" aria-label="Checkout steps">
                <a href="{{ route('cart') }}">Cart</a>
                <span>/</span>
                <a href="{{ route('shipping') }}">Shipping</a>
                <span>/</span>
                <span class="is-active" aria-current="step">Payment</span>
            </nav>
            <a href="{{ route('help-center') }}" class="payment-help-link">Help Center</a>
        </div>

        @if ($errors->any())
            <div class="form-errors" style="max-width: 800px; margin: 1rem auto; color: #c00; background: #ffeaea; padding: 1rem; border-radius: 8px;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('payment') }}" method="post" class="payment-layout-form" id="payment-form" autocomplete="off">
            @csrf
            <div class="payment-layout">
                <section class="payment-form-card" aria-label="Payment details">
                    <h2 class="payment-card-title">Payment Method</h2>

                    <div class="payment-step-tabs" role="tablist" aria-label="Payment methods">
                        <button type="button" class="payment-step-tab is-active" role="tab" aria-selected="true" aria-controls="panel-khqr" id="tab-khqr">
                            KHQR
                        </button>
                        <button type="button" class="payment-step-tab" role="tab" aria-selected="false" aria-controls="panel-card" id="tab-card" tabindex="-1">
                            Debit Card
                        </button>
                    </div>

                    <div class="payment-tab-panels">
                        <div id="panel-khqr" class="payment-tab-panel is-active" role="tabpanel" aria-labelledby="tab-khqr">
                            <div class="payment-khqr">
                                <div class="payment-khqr-qr">
                                    <img src="{{ $qrUrl }}" alt="KHQR payment code for {{ number_format($total, 2) }} USD">
                                </div>
                                <p class="payment-khqr-instruction">Scan this QR code with your Bakong or banking app to complete payment.</p>
                                <div class="payment-khqr-meta">
                                    <span>Merchant: <strong>the DS</strong></span>
                                    <span>Amount: <strong>$ {{ number_format($total, 2) }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div id="panel-card" class="payment-tab-panel" role="tabpanel" aria-labelledby="tab-card" hidden>
                            <div class="payment-card-form">
                                <div class="payment-form-group payment-form-group--wide">
                                    <label for="card-number">Card Number</label>
                                    <input type="text" id="card-number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" inputmode="numeric">
                                </div>

                                <div class="payment-form-row">
                                    <div class="payment-form-group">
                                        <label for="card-expiry">Expiry Date</label>
                                        <input type="text" id="card-expiry" name="card_expiry" placeholder="MM / YY" maxlength="7" inputmode="numeric">
                                    </div>
                                    <div class="payment-form-group">
                                        <label for="card-cvc">CVC</label>
                                        <input type="text" id="card-cvc" name="card_cvc" placeholder="123" maxlength="4" inputmode="numeric">
                                    </div>
                                </div>

                                <div class="payment-form-group payment-form-group--wide">
                                    <label for="card-name">Cardholder Name</label>
                                    <input type="text" id="card-name" name="card_name" placeholder="Name on card">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <aside class="payment-summary-card" aria-label="Order summary">
                    <h2 class="payment-card-title">Cart</h2>
                    <div class="payment-cart-items">
                        @foreach (array_values($cart) as $index => $item)
                            <div class="payment-cart-item">
                                <div class="payment-cart-thumb">
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                    <span class="payment-cart-badge">{{ $index + 1 }}</span>
                                </div>
                                <div class="payment-cart-info">
                                    <strong>{{ $item['name'] }}</strong>
                                    <span>Quantity: {{ $item['quantity'] }}</span>
                                    <span class="payment-cart-size">Size: {{ $item['size'] ?? 'One Size' }}</span>
                                </div>
                                <span class="payment-cart-price">$ {{ number_format($item['price'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="payment-promo">
                        <input type="text" placeholder="Apply Promo Code" aria-label="Promo code">
                        <button type="button">Apply</button>
                    </div>

                    <hr class="payment-divider">

                    <dl class="payment-costs">
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

                    <hr class="payment-divider">

                    <div class="payment-total">
                        <span>Total</span>
                        <strong>$ {{ number_format($total, 2) }}</strong>
                    </div>

                    <button type="submit" class="payment-checkout-btn">Place Order</button>
                </aside>
            </div>
        </form>
    </main>
@endsection

@push('scripts')
<script>
    (function() {
        const tabs = document.querySelectorAll('.payment-step-tab');
        const panels = document.querySelectorAll('.payment-tab-panel');

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const target = tab.getAttribute('aria-controls');

                tabs.forEach((t) => {
                    t.classList.remove('is-active');
                    t.setAttribute('aria-selected', 'false');
                    t.setAttribute('tabindex', '-1');
                });
                panels.forEach((p) => {
                    p.classList.remove('is-active');
                    p.hidden = true;
                });

                tab.classList.add('is-active');
                tab.setAttribute('aria-selected', 'true');
                tab.removeAttribute('tabindex');

                const panel = document.getElementById(target);
                if (panel) {
                    panel.classList.add('is-active');
                    panel.hidden = false;
                }
            });
        });

        const cardNumber = document.getElementById('card-number');
        if (cardNumber) {
            cardNumber.addEventListener('input', (e) => {
                let val = e.target.value.replace(/\D/g, '');
                val = val.slice(0, 16);
                e.target.value = val.replace(/(.{4})/g, '$1 ').trim();
            });
        }

        const cardExpiry = document.getElementById('card-expiry');
        if (cardExpiry) {
            cardExpiry.addEventListener('input', (e) => {
                let val = e.target.value.replace(/\D/g, '');
                val = val.slice(0, 4);
                if (val.length >= 2) {
                    val = val.slice(0, 2) + ' / ' + val.slice(2);
                }
                e.target.value = val;
            });
        }

        const cardCvc = document.getElementById('card-cvc');
        if (cardCvc) {
            cardCvc.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
            });
        }
    })();
</script>
@endpush
