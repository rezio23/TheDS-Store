<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'The DS')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">

    @stack('styles')
</head>
<body class="@yield('body_class')">
    @include('components.navbar')

    @yield('content')

    @include('components.footer')

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script>
    (function() {
        var toastEl = document.createElement('div');
        toastEl.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;background:#111;color:#fff;padding:12px 20px;border-radius:8px;font-size:0.9rem;opacity:0;transition:opacity 0.3s;pointer-events:none;';
        document.body.appendChild(toastEl);

        function showToast(msg) {
            toastEl.textContent = msg;
            toastEl.style.opacity = '1';
            setTimeout(function() { toastEl.style.opacity = '0'; }, 2500);
        }

        function updateBagCount(count) {
            var badge = document.querySelector('.bag-count');
            if (badge) badge.textContent = count;
        }

        function submitFormViaFetch(form, btn) {
            var formData = new FormData(form);
            var action = form.getAttribute('action');
            btn.disabled = true;
            fetch(action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            }).then(function(res) { return res.json(); }).then(function(data) {
                btn.disabled = false;
                if (data && data.success) {
                    showToast(data.message || 'Saved.');
                    if (typeof data.cart_count !== 'undefined') updateBagCount(data.cart_count);
                    if (typeof data.favorited !== 'undefined' && btn) {
                        btn.textContent = data.favorited ? 'Unfavorite' : 'Favorite';
                    }
                } else {
                    showToast('Something went wrong.');
                }
            }).catch(function() {
                btn.disabled = false;
                showToast('Something went wrong.');
            });
        }

        document.addEventListener('click', function(e) {
            var cartBtn = e.target.closest('[data-add-to-cart]');
            if (cartBtn) {
                var form = cartBtn.closest('form');
                if (form) {
                    e.preventDefault();
                    submitFormViaFetch(form, cartBtn);
                    return;
                }
            }
            var favBtn = e.target.closest('[data-add-to-favorite]');
            if (favBtn) {
                var form = favBtn.closest('form');
                if (form) {
                    e.preventDefault();
                    submitFormViaFetch(form, favBtn);
                    return;
                }
            }
        });
    })();
    </script>

    @stack('scripts')
</body>
</html>
