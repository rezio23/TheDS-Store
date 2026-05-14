<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $allProducts = Product::all()->map(function ($product) {
            $product->tags = array_map('trim', explode(',', $product->tags ?? ''));
            return $product;
        });

        $products = $allProducts->filter(function ($p) {
            return in_array('Popular', $p->tags, true);
        })->values();

        $menProducts = $allProducts->filter(function ($p) {
            return in_array('Man', $p->tags, true);
        })->values();

        $womenProducts = $allProducts->filter(function ($p) {
            return in_array('Woman', $p->tags, true);
        })->values();

        $brandBadges = [
            [
                'name' => 'polo',
                'abbr' => 'POLO',
                'logo' => asset('assets/images/external/badge-polo-logo.jpg'),
            ],
            [
                'name' => 'nike',
                'abbr' => 'NIKE',
                'logo' => asset('assets/images/external/badge-nike-logo.jpg'),
            ],
            [
                'name' => 'adidas',
                'abbr' => 'ADIDAS',
                'logo' => asset('assets/images/external/badge-adidas-logo.jpg'),
            ],
        ];

        $tickerBrands = ['POLO', 'BALENCIAGA', 'adidas', 'NIKE', 'PUMA', 'GUCCI', 'POLO', 'VERSACE'];

        $brandRanking = [
            [
                'name' => 'Balenciaga',
                'count' => 48,
                'stack' => [
                    'left' => [
                        'image' => asset('assets/images/external/balenciaga-city-bag.jpg'),
                        'alt' => 'Balenciaga Le City bag Spring 2026',
                    ],
                    'center' => [
                        'image' => asset('assets/images/external/balenciaga-campaign.jpg'),
                        'alt' => 'Balenciaga Heart and Body Spring 2026 campaign',
                    ],
                    'right' => [
                        'image' => asset('assets/images/external/balenciaga-radar-sneaker.jpg'),
                        'alt' => 'Balenciaga Radar sneaker 2026',
                    ],
                ],
            ],
            [
                'name' => 'Gucci',
                'count' => 12,
                'stack' => [
                    'left' => [
                        'image' => asset('assets/images/external/gucci-fw26-001.jpg'),
                        'alt' => 'Gucci FW26 Primavera runway look',
                    ],
                    'center' => [
                        'image' => asset('assets/images/external/gucci-fw26-002.jpg'),
                        'alt' => 'Gucci Fall 2026 collection by Demna',
                    ],
                    'right' => [
                        'image' => asset('assets/images/external/gucci-fw26-003.jpg'),
                        'alt' => 'Gucci FW26 runway detail',
                    ],
                ],
            ],
            [
                'name' => 'Nike',
                'count' => 48,
                'stack' => [
                    'left' => [
                        'image' => asset('assets/images/external/nike-liquid-max-001.jpg'),
                        'alt' => 'Nike Air Liquid Max 2026 product',
                    ],
                    'center' => [
                        'image' => asset('assets/images/external/nike-liquid-max-003.jpg'),
                        'alt' => 'Nike Air Liquid Max lifestyle 2026',
                    ],
                    'right' => [
                        'image' => asset('assets/images/external/nike-liquid-max-005.jpg'),
                        'alt' => 'Nike Air Liquid Max detail 2026',
                    ],
                ],
                'active' => true,
            ],
            [
                'name' => 'Polo',
                'count' => 32,
                'stack' => [
                    'left' => [
                        'image' => asset('assets/images/external/polo-model-001.jpg'),
                        'alt' => 'Polo Ralph Lauren style model',
                    ],
                    'center' => [
                        'image' => asset('assets/images/external/polo-model-002.jpg'),
                        'alt' => 'Preppy polo fashion portrait',
                    ],
                    'right' => [
                        'image' => asset('assets/images/external/polo-golf-lifestyle.jpg'),
                        'alt' => 'Ralph Lauren golf lifestyle',
                    ],
                ],
            ],
            [
                'name' => 'Adidas',
                'count' => 48,
                'stack' => [
                    'left' => [
                        'image' => asset('assets/images/external/adidas-wales-bonner.jpg'),
                        'alt' => 'Adidas Wales Bonner SS26 collection',
                    ],
                    'center' => [
                        'image' => asset('assets/images/external/adidas-adistar.jpg'),
                        'alt' => 'Adidas Adistar Control 5 2026',
                    ],
                    'right' => [
                        'image' => asset('assets/images/external/adidas-bw-run.jpg'),
                        'alt' => 'Adidas BW Run 2026',
                    ],
                ],
            ],
        ];

        $activeBrandIndex = 0;
        foreach ($brandRanking as $index => $brand) {
            if (!empty($brand['active'])) {
                $activeBrandIndex = $index;
                break;
            }
        }
        $activeBrand = $brandRanking[$activeBrandIndex];

        $categoryCards = [
            ['label' => 'Perfume', 'brand' => 'Basmni', 'image' => asset('assets/images/external/category-perfume.png'), 'class' => 'category-card--tall'],
            ['label' => 'Clothes', 'brand' => 'Nike', 'image' => asset('assets/images/external/category-clothes.jpg'), 'class' => 'category-card--high'],
            ['label' => 'Bag', 'brand' => 'Polo', 'image' => asset('assets/images/external/category-bag.jpg'), 'class' => 'category-card--mid'],
            ['label' => 'Accessories', 'brand' => 'Gucci', 'image' => asset('assets/images/external/category-accessories.jpg'), 'class' => 'category-card--wide'],
            ['label' => 'Premium', 'brand' => 'Prada', 'image' => asset('assets/images/external/category-premium.jpg'), 'class' => 'category-card--high'],
        ];

        $featureLine = ['PREMIUM FABRIC', 'MODERN LIFESTYLE', 'FABRIC QUALITY', 'TIMELESS CUTS', 'CLASSIC AND COMFORT'];

        return view('home', compact('products', 'menProducts', 'womenProducts', 'brandBadges', 'tickerBrands', 'brandRanking', 'activeBrandIndex', 'activeBrand', 'categoryCards', 'featureLine'));
    }
}
