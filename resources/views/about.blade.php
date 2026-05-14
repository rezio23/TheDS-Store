@extends('layouts.app')

@section('title', 'About | The DS')
@section('body_class', 'about-page')

@section('content')
    <main class="about-main">
        <section class="about-hero" aria-labelledby="about-hero-heading">
            <div class="about-hero-copy">
                <p class="pixel-note"><span>Home / About /</span> Our Story</p>
                <h1 id="about-hero-heading">The DS —<br><span>Curated Luxury</span></h1>
                <p class="about-hero-lead">Born from a passion for premium fashion and authentic streetwear, The DS brings the world's most coveted brands to your doorstep.</p>
                <a href="{{ route('shop') }}" class="about-hero-cta">
                    Explore the Collection
                    <i data-lucide="arrow-right" aria-hidden="true"></i>
                </a>
            </div>
            <figure class="about-hero-model">
                <img src="{{ asset('assets/images/external/about-hero.png') }}" alt="Luxury fashion boutique interior">
            </figure>
        </section>

        <section class="about-story" aria-labelledby="about-story-heading">
            <div class="about-story-card">
                <h2 id="about-story-heading">Our Mission</h2>
                <p>We believe luxury should be accessible, authentic, and effortless. The DS was founded to bridge the gap between global premium brands and style-conscious individuals who demand quality without compromise.</p>
                <p>From limited-edition sneakers to timeless fragrances, every piece in our collection is hand-selected and verified for authenticity. We partner directly with authorized distributors to ensure that what you receive is exactly what the brand intended.</p>
            </div>
        </section>

        <section class="about-stats" aria-label="Key metrics">
            <div class="about-stat">
                <strong>120+</strong>
                <span>Premium Brands</span>
            </div>
            <div class="about-stat">
                <strong>50K</strong>
                <span>Happy Customers</span>
            </div>
            <div class="about-stat">
                <strong>15</strong>
                <span>Countries Served</span>
            </div>
            <div class="about-stat">
                <strong>99%</strong>
                <span>Authentic Products</span>
            </div>
        </section>

        <section class="about-values" aria-labelledby="about-values-heading">
            <h2 id="about-values-heading">Why Shop With Us</h2>
            <div class="about-values-grid">
                <article class="about-value-card">
                    <i data-lucide="shield-check" aria-hidden="true"></i>
                    <h3>Authenticity Guaranteed</h3>
                    <p>Every product is sourced directly from brand-authorized distributors. We never compromise on authenticity.</p>
                </article>
                <article class="about-value-card">
                    <i data-lucide="truck" aria-hidden="true"></i>
                    <h3>Fast Global Shipping</h3>
                    <p>From Phnom Penh to Paris, our logistics network ensures your order arrives swiftly and safely.</p>
                </article>
                <article class="about-value-card">
                    <i data-lucide="headphones" aria-hidden="true"></i>
                    <h3>Dedicated Support</h3>
                    <p>Our team is here to help with sizing, styling advice, or any questions about your order.</p>
                </article>
                <article class="about-value-card">
                    <i data-lucide="refresh-ccw" aria-hidden="true"></i>
                    <h3>Easy Returns</h3>
                    <p>Not the perfect fit? Return within 30 days for a full refund or exchange — no questions asked.</p>
                </article>
            </div>
        </section>

        <section class="about-team" aria-labelledby="about-team-heading">
            <div class="about-team-header">
                <p class="pixel-note"><span>Inside DS /</span> People</p>
                <h2 id="about-team-heading">Meet the Team</h2>
                <p>The people keeping each drop curated, verified, packed, and moving.</p>
            </div>
            <div class="about-team-grid">
                <article class="about-team-card">
                    <div class="about-team-card__top">
                        <span class="about-team-card__avatar">
                            <img src="{{ asset('assets/images/external/about-team-avatar.jpg') }}" alt="Vichhean Sombath">
                        </span>
                        <span class="about-team-card__focus">Brand vision</span>
                    </div>
                    <div class="about-team-card__body">
                        <p class="about-team-card__role">Founder &amp; CEO</p>
                        <h3>Vichhean Sombath</h3>
                        <p class="about-team-card__summary">Sets the standard for authentic drops, premium sourcing, and the overall DS experience.</p>
                    </div>
                </article>
                <article class="about-team-card">
                    <div class="about-team-card__top">
                        <span class="about-team-card__avatar">
                            <img src="{{ asset('assets/images/external/about-creative-director.jpg') }}" alt="Creative Director">
                        </span>
                        <span class="about-team-card__focus">Product curation</span>
                    </div>
                    <div class="about-team-card__body">
                        <p class="about-team-card__role">Head of Curation</p>
                        <h3>Creative Director</h3>
                        <p class="about-team-card__summary">Builds collections around standout silhouettes, seasonal trends, and everyday wearability.</p>
                    </div>
                </article>
                <article class="about-team-card">
                    <div class="about-team-card__top">
                        <span class="about-team-card__avatar">
                            <img src="{{ asset('assets/images/external/about-operations-lead.jpg') }}" alt="Operations Lead">
                        </span>
                        <span class="about-team-card__focus">Order flow</span>
                    </div>
                    <div class="about-team-card__body">
                        <p class="about-team-card__role">Logistics &amp; Fulfillment</p>
                        <h3>Operations Lead</h3>
                        <p class="about-team-card__summary">Keeps orders moving smoothly from verification to packing, delivery, and support follow-up.</p>
                    </div>
                </article>
            </div>
        </section>

        <section class="about-cta-band" aria-label="Call to action">
            <p>Ready to elevate your wardrobe?</p>
            <a href="{{ route('shop') }}">Shop Now</a>
        </section>
    </main>
@endsection
