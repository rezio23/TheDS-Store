<x-guest-layout>
    <x-slot name="title">Sign Up | The DS</x-slot>

    <h1>Sign Up</h1>
    <hr class="edit-form-divider">

    <form class="auth-form" method="POST" action="{{ route('register') }}">
        @csrf

        @if ($errors->any())
            <div class="auth-errors" style="color: #e63946; margin-bottom: 1rem; font-size: 0.9rem;">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0.25rem 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="edit-form-group">
            <label class="edit-form-label" for="signup-full-name">Full name</label>
            <input class="edit-form-input" id="signup-full-name" name="full_name" type="text" value="{{ old('full_name') }}" placeholder="e.g. John Smith" required autofocus>
        </div>

        <div class="edit-form-group">
            <label class="edit-form-label" for="signup-email">Email</label>
            <input class="edit-form-input" id="signup-email" name="email" type="email" value="{{ old('email') }}" placeholder="e.g. sombath@gmail.com" required>
        </div>

        <div class="edit-form-group">
            <label class="edit-form-label" for="signup-password">Password</label>
            <input class="edit-form-input" id="signup-password" name="password" type="password" placeholder="Create a password" required>
        </div>

        <div class="edit-form-group">
            <label class="edit-form-label" for="signup-confirm-password">Confirm Password</label>
            <input class="edit-form-input" id="signup-confirm-password" name="password_confirmation" type="password" placeholder="Confirm your password" required>
        </div>

        <div class="edit-form-actions">
            <button type="submit" class="edit-form-button edit-form-button--submit edit-form-button--full">Sign Up</button>
        </div>
    </form>

    <p class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Log in</a>
    </p>
</x-guest-layout>
