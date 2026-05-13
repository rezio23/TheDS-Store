<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Paradigme Eau de Parfum',
                'slug' => 'paradigme-eau-de-parfum',
                'brand' => 'Basmni',
                'description' => 'A timeless fragrance with notes of amber and vanilla.',
                'price' => 120.00,
                'image' => 'products/paradigme.jpg',
                'gallery' => '',
                'tags' => 'Popular,Woman,Fragrance',
                'category' => 'perfumes',
                'badge' => 'New',
                'rating' => '5',
                'stock' => 50,
            ],
            [
                'name' => 'Air Max Pulse',
                'slug' => 'air-max-pulse',
                'brand' => 'Nike',
                'description' => 'Premium sneakers with Air cushioning technology.',
                'price' => 180.00,
                'image' => 'products/air-max.jpg',
                'gallery' => '',
                'tags' => 'Popular,Man,Sneaker,Sport',
                'category' => 'sneakers',
                'badge' => 'Best Seller',
                'rating' => '4.8',
                'stock' => 30,
            ],
            [
                'name' => 'Le City Bag',
                'slug' => 'le-city-bag',
                'brand' => 'Balenciaga',
                'description' => 'Iconic leather handbag with signature hardware.',
                'price' => 2500.00,
                'image' => 'products/le-city.jpg',
                'gallery' => '',
                'tags' => 'Popular,Woman,Bag,Luxury',
                'category' => 'bags',
                'badge' => 'Premium',
                'rating' => '5',
                'stock' => 10,
            ],
            [
                'name' => 'Polo Shirt Classic',
                'slug' => 'polo-shirt-classic',
                'brand' => 'Ralph Lauren',
                'description' => 'Classic fit polo shirt in premium cotton pique.',
                'price' => 95.00,
                'image' => 'products/polo.jpg',
                'gallery' => '',
                'tags' => 'Popular,Man,Polo,Classic',
                'category' => 'clothes',
                'badge' => 'Classic',
                'rating' => '4.5',
                'stock' => 100,
            ],
            [
                'name' => 'Gucci Bloom',
                'slug' => 'gucci-bloom',
                'brand' => 'Gucci',
                'description' => 'Floral fragrance with notes of jasmine and tuberose.',
                'price' => 145.00,
                'image' => 'products/gucci-bloom.jpg',
                'gallery' => '',
                'tags' => 'Woman,Fragrance,Luxury',
                'category' => 'perfumes',
                'badge' => 'Luxury',
                'rating' => '4.9',
                'stock' => 25,
            ],
            [
                'name' => 'Adidas Ultraboost',
                'slug' => 'adidas-ultraboost',
                'brand' => 'Adidas',
                'description' => 'Responsive running shoes with Boost midsole.',
                'price' => 190.00,
                'image' => 'products/ultraboost.jpg',
                'gallery' => '',
                'tags' => 'Man,Sneaker,Sport,Streetwear',
                'category' => 'sneakers',
                'badge' => 'Sport',
                'rating' => '4.7',
                'stock' => 40,
            ],
            [
                'name' => 'Prada Nylon Backpack',
                'slug' => 'prada-nylon-backpack',
                'brand' => 'Prada',
                'description' => 'Iconic nylon backpack with leather trim.',
                'price' => 1800.00,
                'image' => 'products/prada-backpack.jpg',
                'gallery' => '',
                'tags' => 'Man,Bag,Accessory,Luxury',
                'category' => 'bags',
                'badge' => 'Designer',
                'rating' => '4.8',
                'stock' => 15,
            ],
            [
                'name' => 'Puma RS-X',
                'slug' => 'puma-rs-x',
                'brand' => 'Puma',
                'description' => 'Bold retro-inspired sneakers with chunky sole.',
                'price' => 120.00,
                'image' => 'products/puma-rsx.jpg',
                'gallery' => '',
                'tags' => 'Kid,Sneaker,Streetwear',
                'category' => 'sneakers',
                'badge' => 'Trending',
                'rating' => '4.6',
                'stock' => 60,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
