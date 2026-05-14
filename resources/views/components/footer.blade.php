<footer class="site-footer">
    <section class="footer-brand" aria-label="Store footer">
        <a class="brand-mark brand-mark--footer" href="{{ url('/') }}#home">the DS</a>
        <p><span>- Fabric Luxury</span><br>and Premium</p>

        <div class="footer-socials" aria-label="Social links">
            <a class="footer-social" href="#" aria-label="Facebook" title="Facebook">
                <img src="{{ asset('assets/images/external/social-facebook.png') }}" alt="">
            </a>
            <a class="footer-social" href="#" aria-label="Telegram" title="Telegram">
                <img src="{{ asset('assets/images/external/social-telegram.png') }}" alt="">
            </a>
            <a class="footer-social" href="#" aria-label="Instagram" title="Instagram">
                <img src="{{ asset('assets/images/external/social-instagram.png') }}" alt="">
            </a>
            <a class="footer-social" href="#" aria-label="TikTok" title="TikTok">
                <img src="{{ asset('assets/images/external/social-tiktok.jpg') }}" alt="">
            </a>
        </div>
    </section>

    <div class="footer-groups">
        <section class="footer-group">
            <h2>Location</h2>
            <p>Phnom Penh, Cambodia</p>
        </section>
        <section class="footer-group">
            <h2>Call Us</h2>
            <p><a href="tel:+855112233">+855 112 233</a></p>
        </section>
        <section class="footer-group">
            <h2>Email</h2>
            <p><a href="mailto:thedaservice@store.com">thedaservice@store.com</a></p>
        </section>

        <nav class="footer-group footer-links" aria-label="Footer home links">
            <h2>Home</h2>
            <a href="{{ url('/') }}#about">About</a>
            <a href="{{ url('/') }}#new">Products</a>
            <a href="{{ url('/') }}#gender">Categories</a>
        </nav>
        <nav class="footer-group footer-links" aria-label="Footer shop links">
            <h2>Shop</h2>
            <a href="{{ route('shop', ['category' => 'clothes']) }}#shop-grid">Clothes</a>
            <a href="{{ route('shop', ['category' => 'perfumes']) }}#shop-grid">Perfumes</a>
            <a href="{{ route('shop', ['category' => 'accessories']) }}#shop-grid">Accessories</a>
            <a href="{{ route('shop', ['category' => 'bags']) }}#shop-grid">Bag</a>
            <a href="{{ route('shop', ['category' => 'sneakers']) }}#shop-grid">Sneakers</a>
        </nav>
        <nav class="footer-group footer-links footer-links--brands" aria-label="Footer brand links">
            <h2>Brand</h2>
            <div>
                <a href="{{ route('shop') }}#shop-grid">Polo</a>
                <a href="{{ route('shop') }}#shop-grid">Balenciaga</a>
                <a href="{{ route('shop') }}#shop-grid">Prada</a>
                <a href="{{ route('shop') }}#shop-grid">Puma</a>
                <a href="{{ route('shop') }}#shop-grid">Gucci</a>
                <a href="{{ route('shop') }}#shop-grid">Nike</a>
            </div>
        </nav>
        <nav class="footer-group footer-links" aria-label="Footer support links">
            <h2>Support</h2>
            <a href="{{ route('help-center') }}">Help Center</a>
            <a href="{{ route('terms') }}">Terms &amp; Conditions</a>
        </nav>
    </div>
</footer>
