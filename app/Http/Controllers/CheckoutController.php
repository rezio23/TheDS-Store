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

        $shippingOptions = [
            ['value' => 'standard', 'label' => 'Standard Shipping', 'price' => 5.00, 'time' => '5-7 business days'],
            ['value' => 'express', 'label' => 'Express Shipping', 'price' => 15.00, 'time' => '2-3 business days'],
            ['value' => 'next_day', 'label' => 'Next Day Delivery', 'price' => 25.00, 'time' => '1 business day'],
        ];

        $user = Auth::user();

        return view('shipping', compact('cart', 'subtotal', 'shippingOptions', 'user'));
    }

    public function storeShipping(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:50',
            'shipping_address' => 'required|string',
            'shipping_postal' => 'required|string|max:20',
            'shipping_email' => 'required|email|max:255',
            'shipping_mode' => 'required|string',
        ]);

        session(['shipping' => $request->only([
            'shipping_name', 'shipping_phone', 'shipping_address',
            'shipping_postal', 'shipping_email', 'shipping_mode',
        ])]);

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
            'express' => 15.00,
            'next_day' => 25.00,
            default => 5.00,
        };

        $total = $subtotal + $shippingPrice;

        return view('payment', compact('cart', 'shipping', 'subtotal', 'shippingPrice', 'total'));
    }

    public function processPayment(Request $request)
    {
        $cart = session('cart', []);
        $shipping = session('shipping', []);

        if (empty($cart) || empty($shipping)) {
            return redirect()->route('cart')->with('error', 'Invalid checkout state.');
        }

        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $shippingPrice = match ($shipping['shipping_mode'] ?? 'standard') {
            'express' => 15.00,
            'next_day' => 25.00,
            default => 5.00,
        };

        $total = $subtotal + $shippingPrice;

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'pending',
            'shipping_name' => $shipping['shipping_name'],
            'shipping_phone' => $shipping['shipping_phone'],
            'shipping_address' => $shipping['shipping_address'],
            'shipping_postal' => $shipping['shipping_postal'],
            'shipping_email' => $shipping['shipping_email'],
            'shipping_mode' => $shipping['shipping_mode'],
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'product_brand' => $item['brand'],
                'product_price' => $item['price'],
                'quantity' => $item['quantity'],
                'size' => $item['size'],
            ]);
        }

        session()->forget(['cart', 'shipping']);

        return redirect()->route('profile')->with('success', 'Order placed successfully!');
    }
}
