<?php

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $products = Product::with('sizes')
            ->get()
            ->filter(fn ($product) => $product->sizes->isEmpty());

        foreach ($products as $product) {
            $tags = array_map('strtolower', array_filter(array_map('trim', explode(',', $product->tags ?? ''))));

            if (in_array('fragrance', $tags, true)) {
                $sizes = ['30ML', '50ML', '90ML', '100ML', '150ML', 'Refill'];
                $priceMap = ['30ML' => 0.50, '50ML' => 0.70, '90ML' => 0.90, '100ML' => 1.00, '150ML' => 1.30, 'Refill' => 0.60];
            } elseif (in_array('bag', $tags, true)) {
                $sizes = ['Mini', 'Small', 'Medium', 'Large', 'XL', 'One Size'];
                $priceMap = ['Mini' => 0.70, 'Small' => 0.85, 'Medium' => 1.00, 'Large' => 1.20, 'XL' => 1.40, 'One Size' => 1.00];
            } elseif (in_array('sneaker', $tags, true) || in_array('shoes', $tags, true)) {
                $sizes = ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45'];
                $priceMap = [];
            } else {
                $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                $priceMap = [];
            }

            foreach ($sizes as $size) {
                $multiplier = $priceMap[$size] ?? 1.00;
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'price' => round($product->price * $multiplier, 2),
                    'quantity' => $product->stock ?? 0,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $products = Product::with('sizes')
            ->get()
            ->filter(fn ($product) => $product->sizes->isNotEmpty());

        foreach ($products as $product) {
            ProductSize::where('product_id', $product->id)->delete();
        }
    }
};
