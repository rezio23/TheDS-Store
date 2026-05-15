<x-guest-layout>
    <x-slot name="title">Log In | The DS</x-slot>

    <h1>Log In</h1>
    <hr class="edit-form-divider">

    <!-- Session Status -->
    @if (session('status'))
        <div class="auth-success" style="color: #2a9d8f; margin-bottom: 1rem; font-size: 0.9rem;">{{ session('status') }}</div>
    @endif

    @if (request('registered'))
        <div class="auth-success" style="color: #2a9d8f; margin-bottom: 1rem; font-size: 0.9rem;">Account created successfully. Please log in.</div>
    @endif

    <form class="auth-form" id="login-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div id="login-errors" class="auth-errors" style="color: #e63946; margin-bottom: 1rem; font-size: 0.9rem; display: none;"></div>

        <div class="edit-form-group">
            <label class="edit-form-label" for="login-email">Email</label>
            <input class="edit-form-input" id="login-email" name="email" type="email" value="{{ old('email') }}" placeholder="e.g. sombath@gmail.com" required autofocus>
        </div>

        <div class="edit-form-group">
            <label class="edit-form-label" for="login-password">Password</label>
            <input class="edit-form-input" id="login-password" name="password" type="password" placeholder="Enter your password" required>
        </div>

        <div class="edit-form-group" style="display:flex; flex-direction:row; align-items:center; gap:0.5rem; margin-top:0.25rem;">
            <input type="checkbox" id="remember-me" name="remember" value="1" style="width:auto; cursor:pointer;">
            <label for="remember-me" style="margin:0; font-size:0.9rem; color:#666; cursor:pointer;">Remember me</label>
        </div>

        <div class="edit-form-actions">
            <button type="submit" class="edit-form-button edit-form-button--submit edit-form-button--full">Log In</button>
        </div>
    </form>

    <script>
    (function() {
        var form = document.getElementById('login-form');
        var errorBox = document.getElementById('login-errors');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            errorBox.style.display = 'none';
            errorBox.innerHTML = '';

            var formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            }).then(function(response) {
                var contentType = response.headers.get('content-type') || '';

                if (response.redirected && response.url.indexOf('/login') === -1) {
                    window.location.href = response.url;
                    return;
                }

                if (contentType.indexOf('application/json') !== -1) {
                    return response.json().then(function(data) {
                        var messages = [];
                        if (data.message) messages.push(data.message);
                        if (data.errors) {
                            for (var key in data.errors) {
                                messages.push(data.errors[key].join('\n'));
                            }
                        }
                        if (messages.length) {
                            errorBox.innerHTML = messages.join('<br>');
                        } else {
                            errorBox.textContent = 'Login failed. Please check your credentials.';
                        }
                        errorBox.style.display = 'block';
                    });
                }

                return response.text().then(function(html) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');
                    var errorContainer = doc.querySelector('#login-errors, .auth-errors');
                    if (errorContainer && errorContainer.innerHTML.trim()) {
                        errorBox.innerHTML = errorContainer.innerHTML;
                        errorBox.style.display = 'block';
                        return;
                    }
                    var errorList = doc.querySelectorAll('[data-validation-error], .input-error');
                    if (errorList.length) {
                        var msg = Array.from(errorList).map(function(el) { return el.textContent; }).join('<br>');
                        errorBox.innerHTML = msg;
                        errorBox.style.display = 'block';
                        return;
                    }
                    errorBox.textContent = 'Login failed. Please check your credentials.';
                    errorBox.style.display = 'block';
                });
            }).catch(function() {
                errorBox.textContent = 'Something went wrong. Please try again.';
                errorBox.style.display = 'block';
            });
        });
    })();
    </script>

    <p class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Create an account</a>
    </p>
</x-guest-layout>
