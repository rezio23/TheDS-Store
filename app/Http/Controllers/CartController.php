<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = session('cart', []);
        $total = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        return view('cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'slug' => 'required|string',
            'quantity' => 'nullable|integer|min:1',
            'size' => 'nullable|string',
        ]);

        $slug = $request->slug;
        $quantity = max(1, (int) $request->input('quantity', 1));
        $size = $request->input('size', 'M');

        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $cart = session('cart', []);
        $key = $slug . '|' . $size;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'slug' => $product->slug,
                'name' => $product->name,
                'brand' => $product->brand,
                'price' => (float) $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
                'size' => $size,
            ];
        }

        session(['cart' => $cart]);

        if ($request->expectsJson() || $request->ajax()) {
            $cartCount = array_sum(array_column($cart, 'quantity'));
            return response()->json([
                'success' => true,
                'message' => 'Added to cart.',
                'cart_count' => $cartCount,
            ]);
        }

        return redirect()->back()->with('success', 'Added to cart.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:0',
        ]);

        $key = $request->key;
        $quantity = (int) $request->quantity;
        $cart = session('cart', []);

        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $quantity;
            }
        }

        session(['cart' => $cart]);

        if ($request->expectsJson() || $request->ajax()) {
            $cartCount = array_sum(array_column($cart, 'quantity'));
            $total = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));
            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'total' => $total,
            ]);
        }

        return redirect()->route('cart');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $key = $request->key;
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);

        if ($request->expectsJson() || $request->ajax()) {
            $cartCount = array_sum(array_column($cart, 'quantity'));
            $total = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));
            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'total' => $total,
            ]);
        }

        return redirect()->route('cart');
    }
}
