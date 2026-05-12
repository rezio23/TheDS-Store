<x-guest-layout>
    <h1>Log In</h1>
    <hr class="edit-form-divider">

    <!-- Session Status -->
    @if (session('status'))
        <div class="auth-success" style="color: #2a9d8f; margin-bottom: 1rem; font-size: 0.9rem;">{{ session('status') }}</div>
    @endif

    @if (request('registered'))
        <div class="auth-success" style="color: #2a9d8f; margin-bottom: 1rem; font-size: 0.9rem;">Account created successfully. Please log in.</div>
    @endif

    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        @if ($errors->any())
            <div class="auth-errors" style="color: #e63946; margin-bottom: 1rem; font-size: 0.9rem;">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0.25rem 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

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

    <p class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Create an account</a>
    </p>
</x-guest-layout>
