@extends('layouts.app')

@section('title', 'About')
@section('body_class', 'about-page')

@section('content')
    <main class="about-main">
        <section class="about-section" aria-labelledby="about-heading">
            <div class="about-hero">
                <h1 id="about-heading">About The DS</h1>
                <p>Premium fashion curated for the modern lifestyle.</p>
            </div>

            <div class="about-content">
                <article class="about-block">
                    <h2>Our Story</h2>
                    <p>The DS was founded with a vision to bring the world's most prestigious fashion brands to discerning customers. We believe that style is not just about clothing—it's about identity, confidence, and the art of self-expression.</p>
                </article>

                <article class="about-block">
                    <h2>Our Mission</h2>
                    <p>To curate premium collections that embody quality, authenticity, and timeless design. Every piece in our store is hand-selected to ensure it meets our rigorous standards for craftsmanship and style.</p>
                </article>

                <article class="about-block">
                    <h2>Our Brands</h2>
                    <p>We partner with iconic labels including Balenciaga, Gucci, Nike, Prada, Polo Ralph Lauren, Puma, Chanel, and Adidas. Our relationships with these brands ensure that every item is genuine and of the highest quality.</p>
                </article>
            </div>
        </section>
    </main>
@endsection
