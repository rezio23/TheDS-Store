<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $shopProducts = Product::all()->map(function ($product) {
            $product->tags = array_map('trim', explode(',', $product->tags ?? ''));
            return $product;
        });

        $shopBrandOptions = [
            ['label' => 'All brands', 'value' => ''],
            ['label' => 'Nike', 'value' => 'nike'],
            ['label' => 'Prada', 'value' => 'prada'],
            ['label' => 'Balenciaga', 'value' => 'balenciaga'],
            ['label' => 'Ralph Lauren', 'value' => 'ralph-lauren'],
            ['label' => 'Puma', 'value' => 'puma'],
            ['label' => 'Chanel', 'value' => 'chanel'],
            ['label' => 'Gucci', 'value' => 'gucci'],
            ['label' => 'Adidas', 'value' => 'adidas'],
        ];

        $shopAudienceOptions = [
            ['label' => 'All', 'value' => ''],
            ['label' => 'Man', 'value' => 'man'],
            ['label' => 'Woman', 'value' => 'woman'],
            ['label' => 'Kid', 'value' => 'kid'],
        ];

        $shopHighlights = [
            ['icon' => 'badge-check', 'title' => 'Verified Brands', 'text' => 'Every piece is curated around trusted premium labels.'],
            ['icon' => 'sparkles', 'title' => 'Fresh Rotation', 'text' => 'New drops keep the collection moving with the season.'],
            ['icon' => 'shield-check', 'title' => 'Checkout Care', 'text' => 'Clear pricing, clean browsing, and quick cart actions.'],
        ];

        $shopProductsPerPage = 8;
        $featureLine = ['PREMIUM FABRIC', 'MODERN LIFESTYLE', 'FABRIC QUALITY', 'TIMELESS CUTS', 'CLASSIC AND COMFORT'];
        $shopPageCount = max(1, (int) ceil(count($shopProducts) / $shopProductsPerPage));

        return view('shop', compact('shopProducts', 'shopBrandOptions', 'shopAudienceOptions', 'shopHighlights', 'shopProductsPerPage', 'featureLine', 'shopPageCount'));
    }
}
