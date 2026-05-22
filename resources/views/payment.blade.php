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

        <div class="payment-layout">
            <section class="payment-form-card" aria-label="Payment details">
                <form action="{{ route('payment') }}" method="post" class="payment-layout-form" id="payment-form" autocomplete="off">
                    @csrf
                    <h2 class="payment-card-title">Payment Method</h2>

                    <div class="payment-step-tabs" role="tablist" aria-label="Payment methods">
                        <button type="button" class="payment-step-tab is-active" role="tab" aria-selected="true" aria-controls="panel-khqr" id="tab-khqr">KHQR</button>
                        <button type="button" class="payment-step-tab" role="tab" aria-selected="false" aria-controls="panel-card" id="tab-card" tabindex="-1">Card</button>
                        <button type="button" class="payment-step-tab" role="tab" aria-selected="false" aria-controls="panel-paypal" id="tab-paypal" tabindex="-1">PayPal</button>
                        <button type="button" class="payment-step-tab" role="tab" aria-selected="false" aria-controls="panel-aba" id="tab-aba" tabindex="-1">ABA PayWay</button>
                    </div>

                    <div class="payment-tab-panels">
                        <div id="panel-khqr" class="payment-tab-panel is-active" role="tabpanel" aria-labelledby="tab-khqr">
                            <div class="payment-method-body">
                                <p class="payment-method-text">Pay securely with your Bakong app or any supported banking app using KHQR.</p>
                                <button type="button" class="payment-action-btn" id="open-khqr-modal" aria-haspopup="dialog">Show KHQR Code</button>
                            </div>
                        </div>

                        <div id="panel-card" class="payment-tab-panel" role="tabpanel" aria-labelledby="tab-card" hidden>
                            <div class="payment-method-body">
                                <div class="payment-card-form">
                                    <div class="payment-form-group payment-form-group--wide">
                                        <label for="card-number">Card Number</label>
                                        <input type="text" id="card-number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" minlength="13" inputmode="numeric" pattern="[\d\s]+" title="Enter a valid card number" autocomplete="cc-number">
                                    </div>

                                    <div class="payment-form-row">
                                        <div class="payment-form-group">
                                            <label for="card-expiry">Expiry Date</label>
                                            <input type="text" id="card-expiry" name="card_expiry" placeholder="MM / YY" maxlength="7" minlength="7" inputmode="numeric" pattern="(0[1-9]|1[0-2])\s\/\s\d{2}" title="Format: MM / YY" autocomplete="cc-exp">
                                        </div>
                                        <div class="payment-form-group">
                                            <label for="card-cvc">CVC</label>
                                            <input type="text" id="card-cvc" name="card_cvc" placeholder="123" maxlength="4" minlength="3" inputmode="numeric" pattern="\d{3,4}" title="3 or 4 digit CVC" autocomplete="cc-csc">
                                        </div>
                                    </div>

                                    <div class="payment-form-group payment-form-group--wide">
                                        <label for="card-name">Cardholder Name</label>
                                        <input type="text" id="card-name" name="card_name" placeholder="Name on card" minlength="2" maxlength="255" pattern="[\pL\s\-\'\.]+" title="Enter the cardholder name" autocomplete="cc-name">
                                    </div>
                                </div>
                                <p class="payment-secure-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    Your payment information is encrypted and secure.
                                </p>
                            </div>
                        </div>

                        <div id="panel-paypal" class="payment-tab-panel" role="tabpanel" aria-labelledby="tab-paypal" hidden>
                            <div class="payment-method-body">
                                <p class="payment-method-text">Pay securely with your PayPal account.</p>
                                <div id="paypal-button-container"></div>
                            </div>
                        </div>

                        <div id="panel-aba" class="payment-tab-panel" role="tabpanel" aria-labelledby="tab-aba" hidden>
                            <div class="payment-method-body">
                                <p class="payment-method-text">Pay securely with your ABA Bank account via PayWay.</p>
                                <button type="button" class="payment-action-btn" id="aba-pay-btn">Pay with ABA</button>
                                <p id="aba-pay-error" class="payment-method-error"></p>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            <aside class="payment-summary-card" aria-label="Order summary">
                <h2 class="payment-card-title">Cart</h2>
                <div class="payment-cart-items">
                    @foreach (array_values($cart) as $index => $item)
                        <div class="payment-cart-item">
                            <div class="payment-cart-thumb">
                                <img src="{{ storage_url($item['image']) }}" alt="{{ $item['name'] }}">
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
                    <div class="payment-promo">
                        <span class="payment-promo-code"><i data-lucide="tag"></i> {{ strtoupper($promoCode) }}</span>
                        <form method="post" action="{{ route('remove-promo') }}">
                            @csrf
                            <button type="submit" class="payment-promo-remove">Remove</button>
                        </form>
                    </div>
                @else
                    <form method="post" action="{{ route('apply-promo') }}" class="payment-promo">
                        @csrf
                        <input type="text" name="promo_code" placeholder="Apply Promo Code" aria-label="Promo code" value="{{ old('promo_code') }}" maxlength="255">
                        <button type="submit">Apply</button>
                    </form>
                @endif

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
                    @if ($discount > 0)
                        <div style="color:#228b22;">
                            <dt>Discount</dt>
                            <dd>-$ {{ number_format($discount, 2) }}</dd>
                        </div>
                    @endif
                </dl>

                <hr class="payment-divider">

                <div class="payment-total">
                    <span>Total</span>
                    <strong>$ {{ number_format($total, 2) }}</strong>
                </div>

                <button type="submit" form="payment-form" class="payment-checkout-btn">Place Order</button>
            </aside>
        </div>
    </main>

    <div class="khqr-overlay" id="khqr-overlay" role="dialog" aria-modal="true" aria-labelledby="khqr-modal-title" tabindex="-1">
        <div class="khqr-modal">
            <button type="button" class="khqr-modal-close" id="close-khqr-modal" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <h2 id="khqr-modal-title" class="khqr-modal-title">Scan to Pay with Bakong</h2>
            <div class="khqr-modal-qr">
                <img src="{{ $qrUrl }}" alt="Bakong KHQR code for {{ number_format($total, 2) }} USD">
            </div>
            <div class="khqr-modal-meta">
                <span>Merchant: <strong>the DS</strong></span>
                <span>Amount: <strong>$ {{ number_format($total, 2) }}</strong></span>
            </div>
            <p class="khqr-modal-hint">Open your Bakong app or bank app, choose <strong>Scan KHQR</strong>, and point your camera at the code above.</p>
            <button type="button" class="khqr-modal-done" id="khqr-modal-done">Done</button>
        </div>
    </div>
@endsection

@push('scripts')
@if ($paypalClientId)
<script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency=USD"></script>
@endif
<script>
    (function() {
        const openBtn = document.getElementById('open-khqr-modal');
        const closeBtn = document.getElementById('close-khqr-modal');
        const doneBtn = document.getElementById('khqr-modal-done');
        const overlay = document.getElementById('khqr-overlay');

        function openModal() {
            overlay.classList.add('is-open');
            document.body.style.overflow = 'hidden';
            closeBtn.focus();
        }

        function closeModal() {
            overlay.classList.remove('is-open');
            document.body.style.overflow = '';
            if (openBtn) openBtn.focus();
        }

        if (openBtn) openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (doneBtn) doneBtn.addEventListener('click', closeModal);

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeModal();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
                closeModal();
            }
        });

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

        const abaPayBtn = document.getElementById('aba-pay-btn');
        const abaPayError = document.getElementById('aba-pay-error');
        if (abaPayBtn) {
            abaPayBtn.addEventListener('click', () => {
                if (abaPayError) {
                    abaPayError.style.display = 'none';
                    abaPayError.textContent = '';
                }
                abaPayBtn.disabled = true;
                abaPayBtn.textContent = 'Redirecting...';

                fetch('{{ route('payment.aba') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                }).then(function(res) {
                    return res.json();
                }).then(function(data) {
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        if (abaPayError) {
                            abaPayError.textContent = data.message || 'Payment initiation failed. Please try again.';
                            abaPayError.style.display = 'block';
                        }
                        abaPayBtn.disabled = false;
                        abaPayBtn.textContent = 'Pay with ABA';
                    }
                }).catch(function(err) {
                    if (abaPayError) {
                        abaPayError.textContent = 'An error occurred. Please try again.';
                        abaPayError.style.display = 'block';
                    }
                    abaPayBtn.disabled = false;
                    abaPayBtn.textContent = 'Pay with ABA';
                });
            });
        }

        @if ($paypalClientId)
        let paypalButtonsRendered = false;
        function renderPayPalButtons() {
            if (paypalButtonsRendered || typeof paypal === 'undefined') return;
            paypalButtonsRendered = true;
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '{{ number_format($total, 2, '.', '') }}'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return fetch('{{ route('payment.paypal') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            paypal_order_id: data.orderID
                        })
                    }).then(function(res) {
                        return res.json();
                    }).then(function(details) {
                        if (details.success) {
                            window.location.href = details.redirect;
                        } else {
                            alert(details.message || 'Payment failed. Please try again.');
                        }
                    }).catch(function(err) {
                        alert('An error occurred during payment. Please try again.');
                    });
                },
                onError: function(err) {
                    console.error('PayPal error:', err);
                    alert('An error occurred with PayPal. Please try again.');
                }
            }).render('#paypal-button-container');
        }

        const paypalTab = document.getElementById('tab-paypal');
        if (paypalTab) {
            paypalTab.addEventListener('click', function() {
                renderPayPalButtons();
            });
        }
        @endif
    })();
</script>
@endpush
