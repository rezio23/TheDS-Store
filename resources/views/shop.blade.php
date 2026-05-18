@extends('layouts.app')

@section('title', 'Shop | The DS')
@section('body_class', 'shop-page')

@section('content')
    <main class="shop-main">
        <section class="shop-hero" aria-label="Luxury fragrance shop banner">
            <figure class="shop-hero-model">
                <img src="{{ asset('assets/images/external/shop-hero-nike.png') }}" alt="Nike Air Max shoes">
            </figure>

            <div class="shop-hero-copy">
                <p class="pixel-note">/2026 Collection<br>New Arrivals</p>
                <h1>Shop the<br><span>- Best Brands</span></h1>
                <p class="hero-subtitle">Curated premium pieces<br>ready for checkout.</p>
            </div>
        </section>

        <section class="shop-collection" aria-labelledby="shop-heading">
            <section class="shop-catalog" id="shop-grid" aria-labelledby="catalog-heading">
                <section class="feature-ribbon" aria-label="Store quality highlights">
                    <div class="feature-track">
                        @for ($i = 0; $i < 4; $i++)
                            @foreach ($featureLine as $feature)
                                <span>{{ $feature }}</span>
                            @endforeach
                        @endfor
                    </div>
                </section>
                <div class="shop-catalog__heading">
                    <div>
                        <h2 id="catalog-heading">All Products</h2>
                        <p>Browse the full edit with category, brand, and audience filters.</p>
                    </div>
                    <span>{{ count($shopProducts) }} pieces</span>
                </div>

                <div class="search-panel shop-search-panel" hidden>
                    <label for="product-search">Search collection</label>
                    <input id="product-search" data-product-search type="search" placeholder="Try Nike, bag, perfume..." maxlength="100">
                </div>

                <div class="shop-toolbar">
                    <nav class="shop-filter-row" aria-label="Shop categories">
                        <button class="is-active" type="button" data-product-group-filter data-filter-value="" aria-pressed="true">All</button>
                        <button type="button" data-product-group-filter data-filter-value="popular" aria-pressed="false">Popular</button>
                        <button type="button" data-product-group-filter data-filter-value="new-drops" aria-pressed="false">New Drops</button>
                        <button type="button" data-product-group-filter data-filter-value="clothes" aria-pressed="false">Clothes</button>
                        <button type="button" data-product-group-filter data-filter-value="perfumes" aria-pressed="false">Perfumes</button>
                        <button type="button" data-product-group-filter data-filter-value="bags" aria-pressed="false">Bags</button>
                        <button type="button" data-product-group-filter data-filter-value="sneakers" aria-pressed="false">Sneakers</button>
                        <button type="button" data-product-group-filter data-filter-value="accessories" aria-pressed="false">Accessories</button>
                        <button type="button" data-product-group-filter data-filter-value="premium" aria-pressed="false">Premium</button>
                    </nav>

                    <div class="shop-selectors" aria-label="Shop filters">
                        <div class="shop-select-control" data-filter-select>
                            <span id="brand_selector" class="shop-select-control__label">Brand</span>
                            <button class="shop-select-toggle" type="button" data-filter-toggle data-product-brand-filter data-filter-value="" aria-haspopup="listbox" aria-expanded="false" aria-controls="shop-brand-list">
                                <span data-filter-current>All brands</span>
                                <i data-lucide="chevron-down"></i>
                            </button>
                            <div class="shop-select-menu" id="shop-brand-list" role="listbox" aria-label="Select brand">
                                @foreach ($shopBrandOptions as $index => $option)
                                    <button class="{{ $index === 0 ? 'is-selected' : '' }}" type="button" role="option" aria-selected="{{ $index === 0 ? 'true' : 'false' }}" data-filter-option data-filter-value="{{ $option['value'] }}">
                                        {{ $option['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="shop-select-control" data-filter-select>
                            <span class="shop-select-control__label">For</span>
                            <button class="shop-select-toggle" type="button" data-filter-toggle data-product-audience-filter data-filter-value="" aria-haspopup="listbox" aria-expanded="false" aria-controls="shop-audience-list">
                                <span data-filter-current>All</span>
                                <i data-lucide="chevron-down"></i>
                            </button>
                            <div class="shop-select-menu" id="shop-audience-list" role="listbox" aria-label="Select audience">
                                @foreach ($shopAudienceOptions as $index => $option)
                                    <button class="{{ $index === 0 ? 'is-selected' : '' }}" type="button" role="option" aria-selected="{{ $index === 0 ? 'true' : 'false' }}" data-filter-option data-filter-value="{{ $option['value'] }}">
                                        {{ $option['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-grid shop-product-grid" data-shop-product-grid>
                    @foreach ($shopProducts as $product)
                        @php
                            $productTags = $product->tags ?? [];
                            $productTagText = implode(' ', $productTags);
                            $productHref = route('product.show', ['slug' => $product->slug]);
                            $brandFilter = '';
                            $brand = strtolower($product->brand ?? '');
                            $brandMap = ['ralph' => 'ralph-lauren', 'polo' => 'ralph-lauren', 'balenciaga' => 'balenciaga', 'chanel' => 'chanel', 'gucci' => 'gucci', 'nike' => 'nike', 'prada' => 'prada', 'puma' => 'puma', 'adidas' => 'adidas'];
                            foreach ($brandMap as $needle => $value) {
                                if (str_contains($brand, $needle)) {
                                    $brandFilter = $value;
                                    break;
                                }
                            }
                            if (!$brandFilter) {
                                $brandFilter = trim((string) preg_replace('/[^a-z0-9]+/', '-', $brand), '-');
                            }

                            $normalizedTags = array_map('strtolower', $productTags);
                            $audience = '';
                            foreach (['man', 'woman', 'kid'] as $a) {
                                if (in_array($a, $normalizedTags, true)) {
                                    $audience = $a;
                                    break;
                                }
                            }

                            $groups = [];
                            if (in_array('popular', $normalizedTags, true)) {
                                $groups[] = 'popular';
                            } else {
                                $groups[] = 'new-drops';
                            }
                            if (in_array('fragrance', $normalizedTags, true)) {
                                $groups[] = 'perfumes';
                            }
                            if (in_array('bag', $normalizedTags, true)) {
                                $groups[] = 'bags';
                            }
                            if (in_array('sneaker', $normalizedTags, true) || in_array('shoes', $normalizedTags, true)) {
                                $groups[] = 'sneakers';
                            }
                            if (count(array_intersect($normalizedTags, ['classic', 'polo', 'jacket', 'sport', 'streetwear'])) > 0) {
                                $groups[] = 'clothes';
                            }
                            if (in_array('luxury', $normalizedTags, true)) {
                                $groups[] = 'premium';
                            }
                            if (in_array('accessory', $normalizedTags, true) || in_array('accessories', $normalizedTags, true)) {
                                $groups[] = 'accessories';
                            }
                            $groupsStr = implode(' ', array_values(array_unique($groups)));
                        @endphp
                        <article class="product-card"
                            data-product-card
                            data-name="{{ strtolower($product->name . ' ' . $product->brand . ' ' . $productTagText) }}"
                            data-tags="{{ strtolower($productTagText) }}"
                            data-brand="{{ $brandFilter }}"
                            data-audience="{{ $audience }}"
                            data-groups="{{ $groupsStr }}"
                        >
                            <a class="product-image" href="{{ $productHref }}" aria-label="View {{ $product->name }}">
                                <img src="{{ storage_url($product->image) }}" alt="{{ $product->name }}">
                            </a>
                            <div class="product-info">
                                <p>{{ $product->brand }}</p>
                                <h3>{{ $product->name }}</h3>
                                <span>{{ $product->description }}</span>
                            </div>
                            @if (!empty($productTags))
                                <div class="product-tags" aria-label="Product tags">
                                    @foreach ($productTags as $tag)
                                        <span>{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="product-actions">
                                <strong>${{ number_format($product->price, 2) }}</strong>
                                <form action="{{ route('cart.add') }}" method="post" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="slug" value="{{ $product->slug }}">
                                    <button class="cart-button" type="submit" data-add-to-cart>
                                        <span>Add to Cart</span>
                                        <i data-lucide="arrow-right"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <nav class="shop-pagination" data-shop-pagination data-page-size="{{ $shopProductsPerPage }}" aria-label="Product pages" {{ $shopPageCount <= 1 ? 'hidden' : '' }}>
                    @for ($page = 1; $page <= $shopPageCount; $page++)
                        <button class="{{ $page === 1 ? 'is-active' : '' }}" type="button" data-product-page="{{ $page }}" aria-label="Show product page {{ $page }}" {{ $page === 1 ? 'aria-current=page' : '' }}>
                            {{ $page }}
                        </button>
                    @endfor
                </nav>
            </section>

            <section class="shop-highlight-band" aria-label="Important shop details">
                @foreach ($shopHighlights as $highlight)
                    <article class="shop-highlight-item">
                        <i data-lucide="{{ $highlight['icon'] }}"></i>
                        <div>
                            <h2>{{ $highlight['title'] }}</h2>
                            <p>{{ $highlight['text'] }}</p>
                        </div>
                    </article>
                @endforeach
            </section>
        </section>
    </main>
@endsection
