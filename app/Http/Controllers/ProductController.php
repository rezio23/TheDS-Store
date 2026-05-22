<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, $slug)
    {
        $product = Product::with('sizes')->where('slug', $slug)->firstOrFail();
        $product->tags = array_map('trim', explode(',', $product->tags ?? ''));

        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->where('brand', $product->brand)
                    ->orWhere('category', $product->category);
            })
            ->limit(4)
            ->get();

        return view('product-detail', compact('product', 'relatedProducts'));
    }
}
