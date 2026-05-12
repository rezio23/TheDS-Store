@extends('layouts.app')

@section('title', $product->name)
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
            $tags = array_map('strtolower', $product->tags ?? []);
            if (in_array('fragrance', $tags, true)) return ['30ML', '50ML', '90ML', '100ML', '150ML', 'Refill'];
            if (in_array('bag', $tags, true)) return ['Mini', 'Small', 'Medium', 'Large', 'XL', 'One Size'];
            if (in_array('sneaker', $tags, true) || in_array('shoes', $tags, true)) return ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45'];
            return ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        }

        function getDefaultActiveSize($product, $sizes) {
            $tags = array_map('strtolower', $product->tags ?? []);
            if (in_array('fragrance', $tags, true)) return '100ML';
            if (in_array('bag', $tags, true)) return 'Medium';
            if (in_array('sneaker', $tags, true) || in_array('shoes', $tags, true)) return '40';
            return 'M';
        }

        function getSizePriceMultiplier($size) {
            $fragranceMap = ['30ML' => 0.50, '50ML' => 0.70, '90ML' => 0.90, '100ML' => 1.00, '150ML' => 1.30, 'Refill' => 0.60];
            $bagMap = ['Mini' => 0.70, 'Small' => 0.85, 'Medium' => 1.00, 'Large' => 1.20, 'XL' => 1.40, 'One Size' => 1.00];
            if (isset($fragranceMap[$size])) return $fragranceMap[$size];
            if (isset($bagMap[$size])) return $bagMap[$size];
            return 1.00;
        }

        $product->category = getProductCategory($product);
        $sizes = getProductSizes($product);
        $activeSize = getDefaultActiveSize($product, $sizes);
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
                    @if (!empty($gallery))
                        <div class="product-thumbs" role="list" aria-label="Product images">
                            @foreach ($gallery as $index => $image)
                                <button class="product-thumb {{ $index === 0 ? 'is-active' : '' }}" type="button" role="listitem" data-product-gallery-thumb data-gallery-image="{{ asset($image) }}" data-gallery-alt="{{ $product->name }} product image" aria-label="Show image {{ $index + 1 }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                    <img src="{{ asset($image) }}" alt="">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <figure class="product-hero-media">
                        <img data-product-gallery-main src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                        @if (!empty($product->badge) || !empty($product->rating))
                            <figcaption class="product-badges" aria-label="Product badges">
                                @if (!empty($product->badge))
                                    <span>{{ $product->badge }}</span>
                                @endif
                                @if (!empty($product->rating))
                                    <span>{{ $product->rating }} star</span>
                                @endif
                            </figcaption>
                        @endif
                    </figure>
                </div>

                <div class="product-detail-actions">
                    <form action="{{ route('cart.add') }}" method="post" class="detail-cart-form" style="display:inline;">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $product->slug }}">
                        <input type="hidden" name="size" value="{{ $activeSize }}" id="detail-cart-size">
                        <button class="product-primary-button" type="submit" data-add-to-cart>
                            <span>Add to Cart</span>
                        </button>
                    </form>
                    @auth
                        <form action="{{ route('favorites.toggle') }}" method="post" style="display:inline;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button class="product-secondary-button" type="submit">{{ $isFavorited ? 'Unfavorite' : 'Favorite' }}</button>
                        </form>
                    @endauth
                </div>
            </div>

            <article class="product-detail-info">
                <p class="product-detail-brand">{{ $product->brand }}</p>
                <h1 id="product-title">{{ $product->name }}</h1>
                <p class="product-detail-description">{{ $product->description }}</p>
                <p class="product-detail-category">{{ $product->category }}</p>
                <p class="product-detail-price" data-base-price="{{ $product->price }}" data-size-price="{{ number_format($product->price * getSizePriceMultiplier($activeSize), 2) }}">$ {{ number_format($product->price * getSizePriceMultiplier($activeSize), 2) }}</p>

                <section class="product-option-group" aria-labelledby="product-size-title">
                    <h2 id="product-size-title">Size</h2>
                    <div class="product-size-grid" data-product-size-group>
                        @foreach ($sizes as $size)
                            <button class="{{ $size === $activeSize ? 'is-active' : '' }}" type="button" data-product-size-option data-size-value="{{ $size }}" data-size-multiplier="{{ getSizePriceMultiplier($size) }}" aria-pressed="{{ $size === $activeSize ? 'true' : 'false' }}">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </section>

                @if ($relatedProducts->count() > 0)
                    <section class="product-option-group product-similar-group" aria-labelledby="similar-product-title">
                        <h2 id="similar-product-title">Similar Product</h2>
                        <div class="product-similar-list">
                            @foreach ($relatedProducts as $related)
                                @php
                                    $relatedGallery = array_filter(explode('|', $related->gallery ?? ''));
                                    $relatedImage = $relatedGallery[0] ?? $related->image;
                                @endphp
                                <a href="{{ route('product.show', ['slug' => $related->slug]) }}" class="product-similar-item">
                                    <img src="{{ asset($relatedImage) }}" alt="{{ $related->name }}">
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </article>
        </section>
    </main>
@endsection
