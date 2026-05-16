<x-guest-layout>
    <x-slot name="title">Log In | The DS</x-slot>

    <h1>Log In</h1>
    <hr class="edit-form-divider">

    @if (session('status'))
        <div style="margin-bottom: 1rem; background: #fff; border: 1px solid #e0e0e0; border-left: 4px solid #2a9d8f; border-radius: 10px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
            <span style="display:flex;align-items:center;justify-content:center;width:28px;height:28px;background:#e6f4f1;border-radius:50%;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2a9d8f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </span>
            <p style="margin:0;color:#1a1a1a;font-size:0.9rem;">{{ session('status') }}</p>
        </div>
    @endif

    @if (request('registered'))
        <div style="margin-bottom: 1rem; background: #fff; border: 1px solid #e0e0e0; border-left: 4px solid #2a9d8f; border-radius: 10px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
            <span style="display:flex;align-items:center;justify-content:center;width:28px;height:28px;background:#e6f4f1;border-radius:50%;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2a9d8f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </span>
            <p style="margin:0;color:#1a1a1a;font-size:0.9rem;">Account created successfully. Please log in.</p>
        </div>
    @endif

    <form class="auth-form" id="login-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div id="login-errors" style="margin-bottom: 1rem; background: #fff; border: 1px solid #e0e0e0; border-left: 4px solid #e63946; border-radius: 10px; padding: 1rem 1.25rem; display: none; align-items: flex-start; gap: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
            <span style="display:flex;align-items:center;justify-content:center;width:28px;height:28px;background:#fdeaea;border-radius:50%;flex-shrink:0;margin-top:2px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </span>
            <div id="login-error-text" style="flex:1;color:#1a1a1a;font-size:0.9rem;"></div>
        </div>

        <div class="edit-form-group">
            <label class="edit-form-label" for="login-email">Email</label>
            <input class="edit-form-input" id="login-email" name="email" type="email" value="{{ old('email') }}" placeholder="e.g. sombath@gmail.com" required autofocus autocomplete="email" maxlength="255">
        </div>

        <div class="edit-form-group">
            <label class="edit-form-label" for="login-password">Password</label>
            <input class="edit-form-input" id="login-password" name="password" type="password" placeholder="Enter your password" required minlength="8" autocomplete="current-password">
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
        var errorText = document.getElementById('login-error-text');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            errorBox.style.display = 'none';
            if (errorText) errorText.innerHTML = '';

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
                        if (data.errors) {
                            for (var key in data.errors) {
                                messages.push(data.errors[key].join('\n'));
                            }
                        }
                        if (!messages.length && data.message) {
                            messages.push(data.message);
                        }
                        if (messages.length) {
                            if (errorText) errorText.innerHTML = messages.join('<br>');
                        } else {
                            if (errorText) errorText.textContent = 'Login failed. Please check your credentials.';
                        }
                        errorBox.style.display = 'flex';
                    });
                }

                return response.text().then(function(html) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');
                    var errorContainer = doc.querySelector('#login-errors, .auth-errors');
                    if (errorContainer && errorContainer.innerHTML.trim()) {
                        if (errorText) errorText.innerHTML = errorContainer.innerHTML;
                        errorBox.style.display = 'flex';
                        return;
                    }
                    var errorList = doc.querySelectorAll('[data-validation-error], .input-error');
                    if (errorList.length) {
                        var msg = Array.from(errorList).map(function(el) { return el.textContent; }).join('<br>');
                        if (errorText) errorText.innerHTML = msg;
                        errorBox.style.display = 'flex';
                        return;
                    }
                    if (errorText) errorText.textContent = 'Login failed. Please check your credentials.';
                    errorBox.style.display = 'flex';
                });
            }).catch(function() {
                if (errorText) errorText.textContent = 'Something went wrong. Please try again.';
                errorBox.style.display = 'flex';
            });
        });
    })();
    </script>

    <p class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Create an account</a>
    </p>
</x-guest-layout>
