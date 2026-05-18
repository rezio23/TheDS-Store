@extends('layouts.app')

@section('title', 'The DS | Luxury Ecommerce')

@section('body_class', 'home-page')

@section('content')
    <main>
        <section class="hero-section" aria-labelledby="hero-heading">
        <div class="hero-copy">
            <p class="pixel-note">/New Arrival<br>Collection 2026</p>
            <h1 id="hero-heading">Stylish your <span>- Fashion</span></h1>

            <div class="brand-badges" aria-label="Featured brands">
                @foreach ($brandBadges as $badge)
                    <span class="brand-badge" title="{{ $badge['name'] }}">
                        <img id="banner_main" src="{{ $badge['logo'] }}" alt="{{ $badge['abbr'] }} logo">
                    </span>
                @endforeach
                <a class="add-orbit" href="#shop" aria-label="Explore more brands">
                    <i data-lucide="plus"></i>
                </a>
            </div>
        </div>

        <figure class="hero-model">
            <img src="{{ asset('assets/images/external/hero-nike-sneakers.png') }}" alt="High top Nike sneakers">
        </figure>

        <div class="hero-sidecopy">
            <p id="home_text">Explore many types<br>of BRAND with the best<br>stylize design</p>
            <h2>Every<br><span>where -</span></h2>
        </div>
    </section>

    <section class="brand-ticker" aria-label="Luxury brands">
        <div class="brand-track">
            @for ($i = 0; $i < 4; $i++)
                @foreach ($tickerBrands as $brand)
                    <span>{{ $brand }}</span>
                @endforeach
            @endfor
        </div>
    </section>

    <section class="moment-section" id="about" aria-labelledby="moment-heading">
        <div class="moment-media">
            <h2 id="moment-heading">All about -<br><span>2026</span> moment</h2>
            <div class="moment-stack">
                <img class="stack-img stack-img--left" data-brand-stack="left" src="{{ $activeBrand['stack']['left']['image'] }}" alt="{{ $activeBrand['stack']['left']['alt'] }}">
                <img class="stack-img stack-img--center" data-brand-stack="center" src="{{ $activeBrand['stack']['center']['image'] }}" alt="{{ $activeBrand['stack']['center']['alt'] }}">
                <img class="stack-img stack-img--right" data-brand-stack="right" src="{{ $activeBrand['stack']['right']['image'] }}" alt="{{ $activeBrand['stack']['right']['alt'] }}">
            </div>
            <a class="outline-cta" href="{{ route('shop', ['brand' => strtolower($activeBrand['name']) === 'polo' ? 'ralph-lauren' : strtolower($activeBrand['name'])]) }}#brand_selector" data-see-product>
                See Product
                <i data-lucide="arrow-right"></i>
            </a>
        </div>

        <div class="moment-copy">
            <ul class="brand-ranking" aria-label="Brand inventory counts">
                @foreach ($brandRanking as $index => $brand)
                    <li class="{{ !empty($brand['active']) ? 'is-active' : '' }}" data-brand-item data-brand-index="{{ $index }}" data-slot="{{ $index - $activeBrandIndex }}">
                        <button
                            type="button"
                            data-brand-trigger
                            data-brand-name="{{ $brand['name'] }}"
                            data-brand-filter="{{ strtolower($brand['name']) === 'polo' ? 'ralph-lauren' : strtolower($brand['name']) }}"
                            data-brand-left-image="{{ $brand['stack']['left']['image'] }}"
                            data-brand-left-alt="{{ $brand['stack']['left']['alt'] }}"
                            data-brand-center-image="{{ $brand['stack']['center']['image'] }}"
                            data-brand-center-alt="{{ $brand['stack']['center']['alt'] }}"
                            data-brand-right-image="{{ $brand['stack']['right']['image'] }}"
                            data-brand-right-alt="{{ $brand['stack']['right']['alt'] }}"
                            aria-pressed="{{ !empty($brand['active']) ? 'true' : 'false' }}"
                        >
                            <span class="brand-label">
                                <span class="brand-label__short">{{ substr($brand['name'], 0, 3) }}{{ strlen($brand['name']) > 3 ? '...' : '' }}</span>
                                <span class="brand-label__full">{{ $brand['name'] }}</span>
                            </span>
                            <span>({{ $brand['count'] }})</span>
                        </button>
                    </li>
                @endforeach
            </ul>
            <p class="pixel-quote">Everything is absolutely perfect!<br>From the fabric quality to the flawless fit.</p>
        </div>
    </section>

    <span class="section-anchor" id="gender" aria-hidden="true"></span>

    <section class="category-section" id="shop" aria-labelledby="category-heading">
        <section class="feature-ribbon" aria-label="Store quality highlights">
            <div class="feature-track">
                @for ($i = 0; $i < 4; $i++)
                    @foreach ($featureLine as $feature)
                        <span>{{ $feature }}</span>
                    @endforeach
                @endfor
            </div>
        </section>

        <div class="section-heading">
            <h2 id="category-heading">Explore with all<br><span>- luxuries</span></h2>
            <a href="#new" class="text-link">2026</a>
        </div>

        <div class="category-board">
            @foreach ($categoryCards as $card)
                <article class="category-card {{ $card['class'] }}">
                    @php
                        $map = ['Perfume' => 'perfumes', 'Clothes' => 'clothes', 'Bag' => 'bags', 'Accessories' => 'accessories', 'Premium' => 'premium'];
                        $catHref = isset($map[$card['label']]) ? route('shop', ['category' => $map[$card['label']]]) . '#shop-grid' : route('shop') . '#shop-grid';
                    @endphp
                    <a href="{{ $catHref }}" aria-label="Shop {{ $card['label'] }}">
                        <img src="{{ $card['image'] }}" alt="{{ $card['label'] }} fashion category">
                        <span class="category-title">{{ $card['label'] }}</span>
                        <span class="category-brand">{{ $card['brand'] }}</span>
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    <section class="products-section" id="new" aria-labelledby="products-heading">
        <div class="section-heading section-heading--products">
            <h2 id="products-heading">New drops<br><span>- ready now</span></h2>
            <p class="pixel-note">Curated premium pieces<br>for daily movement.</p>
        </div>

        <div class="search-panel" hidden>
            <label for="product-search">Search collection</label>
            <input id="product-search" data-product-search type="search" placeholder="Try Nike, bag, puffer..." maxlength="100">
        </div>

        <div class="products-panel">
            <button
                class="products-panel__heading"
                type="button"
                data-product-toggle
                aria-expanded="true"
                aria-controls="products-popular"
            >
                <span>Popular</span>
                <i data-lucide="chevron-down" aria-hidden="true"></i>
            </button>

            <div class="product-panel-content product-panel-content--popular" id="products-popular" data-product-content>
                <div class="product-grid">
                    @foreach ($products->take(4) as $product)
                        @php
                            $productTags = $product->tags ?? [];
                            $productTagText = implode(' ', $productTags);
                            $productHref = route('product.show', ['slug' => $product->slug]);
                        @endphp
                        <article
                            class="product-card"
                            data-product-card
                            data-name="{{ strtolower($product->name . ' ' . $product->brand . ' ' . $productTagText) }}"
                            data-tags="{{ strtolower($productTagText) }}"
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
            </div>

            @php
                $productAccordions = [
                    'man' => ['label' => 'Man', 'products' => $menProducts],
                    'woman' => ['label' => 'Woman', 'products' => $womenProducts],
                ];
            @endphp

            @foreach ($productAccordions as $key => $group)
                @php $panelId = 'products-' . $key; @endphp
                <button
                    class="product-panel-row"
                    type="button"
                    data-product-toggle
                    aria-expanded="false"
                    aria-controls="{{ $panelId }}"
                >
                    <span>{{ $group['label'] }}</span>
                    <i data-lucide="chevron-down" aria-hidden="true"></i>
                </button>
                <div class="product-panel-content" id="{{ $panelId }}" data-product-content hidden>
                    <div class="product-grid product-grid--nested">
                        @foreach ($group['products']->take(4) as $product)
                            @php
                                $productTags = $product->tags ?? [];
                                $productTagText = implode(' ', $productTags);
                                $productHref = route('product.show', ['slug' => $product->slug]);
                            @endphp
                            <article
                                class="product-card"
                                data-product-card
                                data-name="{{ strtolower($product->name . ' ' . $product->brand . ' ' . $group['label'] . ' ' . $productTagText) }}"
                                data-tags="{{ strtolower($productTagText) }}"
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
                </div>
            @endforeach
        </div>
    </section>
    </main>
@endsection
