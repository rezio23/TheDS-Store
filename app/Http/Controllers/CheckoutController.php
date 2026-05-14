<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function shipping()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $shippingPrice = 2.99;
        $taxes = round($subtotal * 0.018, 2);
        $total = $subtotal + $shippingPrice + $taxes;

        $user = Auth::user();

        return view('shipping', compact('cart', 'subtotal', 'shippingPrice', 'taxes', 'total', 'user'));
    }

    public function storeShipping(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string|max:500',
            'shipping_mode' => 'required|string|in:standard,fast',
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

        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $shippingPrice = match ($shipping['shipping_mode'] ?? 'standard') {
            'fast' => 5.99,
            default => 2.99,
        };

        $taxes = round($subtotal * 0.018, 2);
        $total = $subtotal + $shippingPrice + $taxes;

        $qrData = 'KHQR|theDS|' . number_format($total, 2) . '|USD';
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($qrData);

        return view('payment', compact('cart', 'shipping', 'subtotal', 'shippingPrice', 'taxes', 'total', 'qrUrl'));
    }

    public function processPayment(Request $request)
    {
        $cart = session('cart', []);
        $shipping = session('shipping', []);

        if (empty($cart) || empty($shipping)) {
            return redirect()->route('cart')->with('error', 'Invalid checkout state.');
        }

        $errors = [];

        $cardNumber = trim((string) $request->input('card_number', ''));
        $cardExpiry = trim((string) $request->input('card_expiry', ''));
        $cardCvc = trim((string) $request->input('card_cvc', ''));
        $cardName = trim((string) $request->input('card_name', ''));

        $hasCardFields = $cardNumber !== '' || $cardExpiry !== '' || $cardCvc !== '' || $cardName !== '';
        $paymentMethod = $hasCardFields ? 'debit_card' : 'khqr';

        if ($paymentMethod === 'debit_card') {
            if ($cardNumber === '') $errors[] = 'Card number is required.';
            if ($cardExpiry === '') $errors[] = 'Expiry date is required.';
            if ($cardCvc === '') $errors[] = 'CVC is required.';
            if ($cardName === '') $errors[] = 'Cardholder name is required.';
        }

        if (!empty($errors)) {
            return redirect()->route('payment')->withErrors($errors);
        }

        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $shippingPrice = match ($shipping['shipping_mode'] ?? 'standard') {
            'fast' => 5.99,
            default => 2.99,
        };

        $taxes = round($subtotal * 0.018, 2);
        $total = $subtotal + $shippingPrice + $taxes;

        $address = ($shipping['address_1'] ?? '');
        if (!empty($shipping['address_2'])) {
            $address .= ', ' . $shipping['address_2'];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
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

        session()->forget(['cart', 'shipping']);

        return redirect()->route('profile', ['ordered' => 1]);
    }
}
