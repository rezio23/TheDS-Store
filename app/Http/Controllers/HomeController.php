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
                'logo' => 'https://static.vecteezy.com/system/resources/previews/023/867/295/non_2x/polo-brand-logo-white-symbol-clothes-design-icon-abstract-illustration-with-black-background-free-vector.jpg',
            ],
            [
                'name' => 'nike',
                'abbr' => 'NIKE',
                'logo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHLm_ETnATw3cjxh5JahsJfORsm6HzZts5VA&s',
            ],
            [
                'name' => 'adidas',
                'abbr' => 'ADIDAS',
                'logo' => 'https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/28549a58-638c-4112-81a9-ab45e3bb4453/dg0ugic-e8f7c206-aa5e-4cf5-afdb-ba5c97554682.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiIvZi8yODU0OWE1OC02MzhjLTQxMTItODFhOS1hYjQ1ZTNiYjQ0NTMvZGcwdWdpYy1lOGY3YzIwNi1hYTVlLTRjZjUtYWZkYi1iYTVjOTc1NTQ2ODIuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.2wxaDYrhrd1rwunDfAhi4ooNzr_ZzkRLCwXmW9xaVyQ',
            ],
        ];

        $tickerBrands = ['POLO', 'BALENCIAGA', 'adidas', 'NIKE', 'PUMA', 'GUCCI', 'POLO', 'VERSACE'];

        $brandRanking = [
            [
                'name' => 'Balenciaga',
                'count' => 48,
                'stack' => [
                    'left' => [
                        'image' => 'https://www.stylerave.com/wp-content/uploads/2025/05/balenciaga-le-city-bag-ezgif.com-avif-to-jpg-converter-1.jpg',
                        'alt' => 'Balenciaga Le City bag Spring 2026',
                    ],
                    'center' => [
                        'image' => 'https://image-cdn.hypb.st/https%3A%2F%2Fhypebeast.com%2Fimage%2F2026%2F02%2F17%2Fpierpaolo-piccioli-debut-balenciaga-heart-and-body-campaign-hudson-williams-winona-ryder-harris-dickinson-001.jpg?q=75&w=1200&cbr=1&fit=max',
                        'alt' => 'Balenciaga Heart and Body Spring 2026 campaign',
                    ],
                    'right' => [
                        'image' => 'https://image-cdn.hypb.st/https%3A%2F%2Fhypebeast.com%2Fimage%2F2026%2F03%2F16%2Fbalenciaga-radar-sneaker-ballerina-release-info.jpg?q=75&w=1200&cbr=1&fit=max',
                        'alt' => 'Balenciaga Radar sneaker 2026',
                    ],
                ],
            ],
            [
                'name' => 'Gucci',
                'count' => 12,
                'stack' => [
                    'left' => [
                        'image' => 'https://images.squarespace-cdn.com/content/v1/59b2777f49fc2b50d073cb2b/1772522023035-35BKTMVFZURWQ4MDOIH6/1000247973.jpg',
                        'alt' => 'Gucci FW26 Primavera runway look',
                    ],
                    'center' => [
                        'image' => 'https://images.squarespace-cdn.com/content/v1/59b2777f49fc2b50d073cb2b/1772522023236-M1OSFIRE8G768JV986J6/1000247976.jpg',
                        'alt' => 'Gucci Fall 2026 collection by Demna',
                    ],
                    'right' => [
                        'image' => 'https://images.squarespace-cdn.com/content/v1/59b2777f49fc2b50d073cb2b/1772522026051-6O4AVQSF4K4Y9ZYT95ZC/1000247979.jpg',
                        'alt' => 'Gucci FW26 runway detail',
                    ],
                ],
            ],
            [
                'name' => 'Nike',
                'count' => 48,
                'stack' => [
                    'left' => [
                        'image' => 'https://image-cdn.hypb.st/https%3A%2F%2Fhypebeast.com%2Fimage%2F2026%2F03%2F10%2Fnike-air-liquid-max-announcement-info-1.jpg?q=75&w=1200&cbr=1&fit=max',
                        'alt' => 'Nike Air Liquid Max 2026 product',
                    ],
                    'center' => [
                        'image' => 'https://image-cdn.hypb.st/https%3A%2F%2Fhypebeast.com%2Fimage%2F2026%2F03%2F10%2Fnike-air-liquid-max-announcement-info-3.jpg?q=75&w=1200&cbr=1&fit=max',
                        'alt' => 'Nike Air Liquid Max lifestyle 2026',
                    ],
                    'right' => [
                        'image' => 'https://image-cdn.hypb.st/https%3A%2F%2Fhypebeast.com%2Fimage%2F2026%2F03%2F10%2Fnike-air-liquid-max-announcement-info-5.jpg?q=75&w=1200&cbr=1&fit=max',
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
                        'image' => 'https://images.pexels.com/photos/16048133/pexels-photo-16048133.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                        'alt' => 'Polo Ralph Lauren style model',
                    ],
                    'center' => [
                        'image' => 'https://images.pexels.com/photos/7270145/pexels-photo-7270145.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                        'alt' => 'Preppy polo fashion portrait',
                    ],
                    'right' => [
                        'image' => 'https://trendygolfusa.com/cdn/shop/files/LAUNCHES_HERO_7c49c26e-fc63-4418-a7d4-2d4b3d44ece2.jpg?v=1689281730',
                        'alt' => 'Ralph Lauren golf lifestyle',
                    ],
                ],
            ],
            [
                'name' => 'Adidas',
                'count' => 48,
                'stack' => [
                    'left' => [
                        'image' => 'https://justfreshkicks.com/wp-content/uploads/2026/04/wales-bonner-adidas-summer-2026-collection-release-date.jpg',
                        'alt' => 'Adidas Wales Bonner SS26 collection',
                    ],
                    'center' => [
                        'image' => 'https://justfreshkicks.com/wp-content/uploads/2026/05/adistar-control-5-PR-scaled.jpg',
                        'alt' => 'Adidas Adistar Control 5 2026',
                    ],
                    'right' => [
                        'image' => 'https://justfreshkicks.com/wp-content/uploads/2026/05/adidas-bw-run-set-scaled.jpg',
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
            ['label' => 'Perfume', 'brand' => 'Basmni', 'image' => 'https://static.wixstatic.com/media/7187d3_912f04d78e424ab98bd1bf0decaa5c72~mv2.png/v1/fill/w_560,h_746,al_c,q_90,usm_0.66_1.00_0.01,enc_avif,quality_auto/7187d3_912f04d78e424ab98bd1bf0decaa5c72~mv2.png', 'class' => 'category-card--tall'],
            ['label' => 'Clothes', 'brand' => 'Nike', 'image' => 'https://static.nike.com/a/images/f_auto,cs_srgb/w_1536,c_limit/5feaa9c2-a959-4986-872a-54ab79f32485/nike-lookbook.jpg', 'class' => 'category-card--high'],
            ['label' => 'Bag', 'brand' => 'Polo', 'image' => 'https://assets.vogue.com/photos/66f8397bb531aa4c6be8a91b/master/w_2560%2Cc_limit/00017-polo-ralph-lauren-spring-2025-ready-to-wear-detail-credit-brand.jpg', 'class' => 'category-card--mid'],
            ['label' => 'Accessories', 'brand' => 'Gucci', 'image' => 'https://www.net-a-porter.com/variants/images/46376663162894040/ou/w2000_q60.jpg', 'class' => 'category-card--wide'],
            ['label' => 'Premium', 'brand' => 'Prada', 'image' => 'https://www.packshotfactory.co.uk/leather-goods-explorer/prada-handbag_001393_p.jpg', 'class' => 'category-card--high'],
        ];

        $featureLine = ['PREMIUM FABRIC', 'MODERN LIFESTYLE', 'FABRIC QUALITY', 'TIMELESS CUTS', 'CLASSIC AND COMFORT'];

        return view('home', compact('products', 'menProducts', 'womenProducts', 'brandBadges', 'tickerBrands', 'brandRanking', 'activeBrandIndex', 'activeBrand', 'categoryCards', 'featureLine'));
    }
}
