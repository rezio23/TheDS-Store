@extends('layouts.app')

@section('title', $product->name . ' | The DS')
@section('body_class', 'product-detail-page')

@section('content')
    @php
        function getProductCategory($product) {
            $tags = array_map('strtolower', $product->tags ?? []);
            $audience = in_array('woman', $tags, true) ? "Women's" : "Men's";
            if (in_array('fragrance', $tags, true)) return $audience . ' Perfume';
            if (in_array('bag', $tags, true)) return 'Luxury Bag';
            if (in_array('sneaker', $tags, true)) return $audience . ' Sneaker';
            if (in_array('shoes', $tags, true)) return $audience . ' Shoes';
            if (in_array('jacket', $tags, true)) return $audience . ' Jacket';
            if (in_array('polo', $tags, true)) return $audience . ' Polo Shirt';
            return 'Premium Product';
        }

        function getProductSizes($product) {
            if ($product->sizes && $product->sizes->count()) {
                return $product->sizes->pluck('size')->toArray();
            }
            $tags = array_map('strtolower', $product->tags ?? []);
            if (in_array('fragrance', $tags, true)) return ['30ML', '50ML', '90ML', '100ML', '150ML', 'Refill'];
            if (in_array('bag', $tags, true)) return ['Mini', 'Small', 'Medium', 'Large', 'XL', 'One Size'];
            if (in_array('sneaker', $tags, true) || in_array('shoes', $tags, true)) return ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45'];
            return ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        }

        function getDefaultActiveSize($product, $sizes) {
            if ($product->sizes && $product->sizes->count()) {
                return $product->sizes->first()->size ?? 'M';
            }
            $tags = array_map('strtolower', $product->tags ?? []);
            if (in_array('fragrance', $tags, true)) return '100ML';
            if (in_array('bag', $tags, true)) return 'Medium';
            if (in_array('sneaker', $tags, true) || in_array('shoes', $tags, true)) return '40';
            return 'M';
        }

        function getSizePrice($product, $size) {
            if ($product->sizes && $product->sizes->count()) {
                $found = $product->sizes->firstWhere('size', $size);
                return $found ? (float) $found->price : (float) $product->price;
            }
            $multiplier = 1.00;
            $fragranceMap = ['30ML' => 0.50, '50ML' => 0.70, '90ML' => 0.90, '100ML' => 1.00, '150ML' => 1.30, 'Refill' => 0.60];
            $bagMap = ['Mini' => 0.70, 'Small' => 0.85, 'Medium' => 1.00, 'Large' => 1.20, 'XL' => 1.40, 'One Size' => 1.00];
            if (isset($fragranceMap[$size])) $multiplier = $fragranceMap[$size];
            elseif (isset($bagMap[$size])) $multiplier = $bagMap[$size];
            return (float) $product->price * $multiplier;
        }

        function getSizeQuantity($product, $size) {
            if ($product->sizes && $product->sizes->count()) {
                $found = $product->sizes->firstWhere('size', $size);
                return $found ? (int) $found->quantity : null;
            }
            return $product->stock ?? null;
        }

        $product->category = getProductCategory($product);
        $sizes = getProductSizes($product);
        $activeSize = getDefaultActiveSize($product, $sizes);
        $activeSizePrice = getSizePrice($product, $activeSize);
        $gallery = array_filter(explode('|', $product->gallery ?? ''));
        $isFavorited = Auth::check() ? \App\Models\Favorite::where('user_id', Auth::id())->where('product_id', $product->id)->exists() : false;
    @endphp

    <main class="product-detail-main">
        <nav class="product-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('shop') }}">Shop</a>
            <span>/</span>
            <a href="{{ route('shop') }}#shop-grid">{{ str_replace("'s", '', $product->category) }}</a>
            <span>/</span>
            <span>{{ $product->name }}</span>
        </nav>

        <section class="product-detail-layout" aria-labelledby="product-title">
            <div class="product-detail-media">
                <div class="product-gallery" data-product-gallery>
                    <div class="product-thumbs" role="list" aria-label="Product images">
                        @foreach ($gallery as $index => $image)
                            <button class="product-thumb {{ $index === 0 ? 'is-active' : '' }}" type="button" role="listitem" data-product-gallery-thumb data-gallery-image="{{ storage_url($image) }}" data-gallery-alt="{{ $product->name }} product image" aria-label="Show image {{ $index + 1 }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                <img src="{{ storage_url($image) }}" alt="">
                            </button>
                        @endforeach
                    </div>

                    <figure class="product-hero-media">
                        <img data-product-gallery-main src="{{ storage_url($gallery[0] ?? $product->image) }}" alt="{{ $product->name }} product image">
                        <figcaption class="product-badges" aria-label="Product badges">
                            <span>{{ $product->badge }}</span>
                            <span>{{ $product->rating }} star</span>
                        </figcaption>
                    </figure>
                </div>

                <div class="product-detail-actions">
                    <form action="{{ route('cart.add') }}" method="post" class="detail-cart-form" style="display:inline;">
                        @csrf
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="slug" value="{{ $product->slug }}">
                        <input type="hidden" name="size" value="{{ $activeSize }}" id="detail-cart-size">
                        <input type="hidden" name="price" value="{{ $activeSizePrice }}" id="detail-cart-price">
                        <button class="product-primary-button" type="submit" data-add-to-cart>
                            <span>Add to Cart</span>
                        </button>
                    </form>
                    <form action="{{ route('favorites.toggle') }}" method="post" style="display:inline;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button class="product-secondary-button" type="submit" data-add-to-favorite>{{ $isFavorited ? 'Unfavorite' : 'Favorite' }}</button>
                    </form>
                </div>
            </div>

            <article class="product-detail-info">
                <p class="product-detail-brand">{{ $product->brand }}</p>
                <h1 id="product-title">{{ $product->name }}</h1>
                <p class="product-detail-description">{{ $product->description }}</p>
                <p class="product-detail-category">{{ $product->category }}</p>
                <p class="product-detail-price" data-base-price="{{ $product->price }}" data-size-price="{{ number_format($activeSizePrice, 2) }}">$ {{ number_format($activeSizePrice, 2) }}</p>

                <section class="product-option-group" aria-labelledby="product-size-title">
                    <h2 id="product-size-title">Size</h2>
                    <div class="product-size-grid" data-product-size-group>
                        @foreach ($sizes as $size)
                            @php
                                $sizePrice = getSizePrice($product, $size);
                                $sizeQty = getSizeQuantity($product, $size);
                                $outOfStock = $sizeQty !== null && $sizeQty <= 0;
                            @endphp
                            <button class="{{ $size === $activeSize ? 'is-active' : '' }} {{ $outOfStock ? 'is-disabled' : '' }}" type="button" data-product-size-option data-size-value="{{ $size }}" data-size-price="{{ number_format($sizePrice, 2) }}" data-size-qty="{{ $sizeQty }}" {{ $outOfStock ? 'disabled' : '' }} aria-pressed="{{ $size === $activeSize ? 'true' : 'false' }}">
                                {{ $size }}
                                @if ($sizeQty !== null)
                                    <small>({{ $sizeQty }} left)</small>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </section>

                <section class="product-option-group product-similar-group" aria-labelledby="similar-product-title">
                    <h2 id="similar-product-title">Similar Product</h2>
                    <div class="product-similar-list">
                        @foreach ($relatedProducts as $related)
                            @php
                                $relatedGallery = array_filter(explode('|', $related->gallery ?? ''));
                                $relatedImage = $relatedGallery[0] ?? '';
                            @endphp
                            <a href="{{ route('product.show', ['slug' => $related->slug]) }}" aria-label="View {{ $related->name }}" title="{{ $related->name }}">
                                <img src="{{ storage_url($relatedImage) }}" alt="{{ $related->name }}">
                            </a>
                        @endforeach
                    </div>
                </section>
            </article>
        </section>
    </main>
@endsection
