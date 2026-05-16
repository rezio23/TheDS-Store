<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    private function getTotals(array $cart, array $shipping = [], ?string $promoCode = null): array
    {
        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $shippingPrice = match ($shipping['shipping_mode'] ?? 'standard') {
            'fast' => 5.99,
            default => 2.99,
        };

        $taxes = round($subtotal * 0.018, 2);

        $promotion = null;
        $discount = 0;

        if ($promoCode) {
            $promotion = Promotion::where('code', strtoupper($promoCode))->first();
            if ($promotion && $promotion->isValid() && $subtotal >= ($promotion->min_order ?? 0)) {
                if ($promotion->type === 'percentage') {
                    $discount = round($subtotal * ($promotion->value / 100), 2);
                } else {
                    $discount = min((float) $promotion->value, $subtotal);
                }
            }
        }

        $total = max(0, $subtotal + $shippingPrice + $taxes - $discount);

        return compact('subtotal', 'shippingPrice', 'taxes', 'discount', 'total', 'promotion');
    }

    public function shipping()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $promoCode = session('promo_code');
        $totals = $this->getTotals($cart, [], $promoCode);

        $user = Auth::user();

        return view('shipping', array_merge(compact('cart', 'user', 'promoCode'), $totals));
    }

    public function storeShipping(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s\-\'\.]+$/u'],
            'phone' => ['required', 'string', 'min:7', 'max:20', 'regex:/^[\d\s\-\+\(\)]+$/'],
            'address_1' => ['required', 'string', 'min:5', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9\s\-]+$/'],
            'email' => ['required', 'email', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'shipping_mode' => ['required', 'string', 'in:standard,fast'],
        ]);

        session(['shipping' => $request->only([
            'full_name', 'phone', 'address_1', 'address_2', 'postal_code',
            'email', 'description', 'shipping_mode',
        ]) + [
            'shipping_cost' => $request->shipping_mode === 'fast' ? 5.99 : 2.99,
        ]]);

        return redirect()->route('payment');
    }

    public function payment()
    {
        $cart = session('cart', []);
        $shipping = session('shipping', []);

        if (empty($cart) || empty($shipping)) {
            return redirect()->route('cart')->with('error', 'Please complete shipping information first.');
        }

        $promoCode = session('promo_code');
        $totals = $this->getTotals($cart, $shipping, $promoCode);

        $qrData = 'KHQR|theDS|' . number_format($totals['total'], 2) . '|USD';
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($qrData);

        return view('payment', array_merge(compact('cart', 'shipping', 'promoCode', 'qrUrl'), $totals));
    }

    public function applyPromo(Request $request)
    {
        $code = strtoupper(trim((string) $request->input('promo_code', '')));
        if ($code === '') {
            return back();
        }

        $request->validate([
            'promo_code' => 'string|max:255',
        ]);
        $promotion = Promotion::where('code', $code)->first();

        if (!$promotion) {
            session()->forget('promo_code');
            return back()->with('promo_error', 'Invalid promo code.');
        }

        if (!$promotion->isValid()) {
            session()->forget('promo_code');
            return back()->with('promo_error', 'This promo code is no longer valid.');
        }

        $cart = session('cart', []);
        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        if ($promotion->min_order && $subtotal < $promotion->min_order) {
            session()->forget('promo_code');
            return back()->with('promo_error', 'Minimum order of $' . number_format($promotion->min_order, 2) . ' required.');
        }

        session(['promo_code' => $code]);
        return back()->with('promo_success', 'Promo code "' . $promotion->code . '" applied! You save ' . $promotion->discount_label . '.');
    }

    public function removePromo()
    {
        session()->forget('promo_code');
        return back()->with('promo_success', 'Promo code removed.');
    }

    public function processPayment(Request $request)
    {
        $cart = session('cart', []);
        $shipping = session('shipping', []);

        if (empty($cart) || empty($shipping)) {
            return redirect()->route('cart')->with('error', 'Invalid checkout state.');
        }

        $cardNumber = trim((string) $request->input('card_number', ''));
        $cardExpiry = trim((string) $request->input('card_expiry', ''));
        $cardCvc = trim((string) $request->input('card_cvc', ''));
        $cardName = trim((string) $request->input('card_name', ''));

        $hasCardFields = $cardNumber !== '' || $cardExpiry !== '' || $cardCvc !== '' || $cardName !== '';
        $paymentMethod = $hasCardFields ? 'debit_card' : 'khqr';

        if ($paymentMethod === 'debit_card') {
            $request->validate([
                'card_number' => ['required', 'string', 'min:13', 'max:19', 'regex:/^[\d\s]+$/'],
                'card_expiry' => ['required', 'string', 'size:7', 'regex:/^(0[1-9]|1[0-2])\s\/\s\d{2}$/'],
                'card_cvc' => ['required', 'string', 'min:3', 'max:4', 'regex:/^\d+$/'],
                'card_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s\-\'\.]+$/u'],
            ]);
        }

        $promoCode = session('promo_code');
        $totals = $this->getTotals($cart, $shipping, $promoCode);

        $address = ($shipping['address_1'] ?? '');
        if (!empty($shipping['address_2'])) {
            $address .= ', ' . $shipping['address_2'];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'promotion_id' => $totals['promotion']?->id,
            'total' => $totals['total'],
            'discount' => $totals['discount'],
            'status' => 'pending',
            'shipping_name' => $shipping['full_name'],
            'shipping_phone' => $shipping['phone'],
            'shipping_address' => $address,
            'shipping_postal' => $shipping['postal_code'],
            'shipping_email' => $shipping['email'],
            'shipping_mode' => $shipping['shipping_mode'],
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'product_brand' => $item['brand'],
                'product_price' => $item['price'],
                'quantity' => $item['quantity'],
                'size' => $item['size'] ?? 'One Size',
                'product_image' => $item['image'] ?? null,
            ]);
        }

        if ($totals['promotion']) {
            $totals['promotion']->increment('uses_count');
        }

        session()->forget(['cart', 'shipping', 'promo_code']);

        return redirect()->route('profile', ['ordered' => 1]);
    }
}
