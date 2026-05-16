<x-guest-layout>
    <x-slot name="title">Sign Up | The DS</x-slot>

    <h1>Sign Up</h1>
    <hr class="edit-form-divider">

    <form class="auth-form" method="POST" action="{{ route('register') }}">
        @csrf

        @if ($errors->any())
            <div style="margin-bottom: 1rem; background: #fff; border: 1px solid #e0e0e0; border-left: 4px solid #e63946; border-radius: 10px; padding: 1rem 1.25rem; display: flex; align-items: flex-start; gap: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                <span style="display:flex;align-items:center;justify-content:center;width:28px;height:28px;background:#fdeaea;border-radius:50%;flex-shrink:0;margin-top:2px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e63946" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </span>
                <div style="flex:1;">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 0.15rem 0; color: #1a1a1a; font-size: 0.9rem;">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="edit-form-group">
            <label class="edit-form-label" for="signup-full-name">Full name</label>
            <input class="edit-form-input" id="signup-full-name" name="full_name" type="text" value="{{ old('full_name') }}" placeholder="e.g. John Smith" required autofocus autocomplete="name" minlength="2" maxlength="255">
        </div>

        <div class="edit-form-group">
            <label class="edit-form-label" for="signup-email">Email</label>
            <input class="edit-form-input" id="signup-email" name="email" type="email" value="{{ old('email') }}" placeholder="e.g. sombath@gmail.com" required autocomplete="email" maxlength="255">
        </div>

        <div class="edit-form-group">
            <label class="edit-form-label" for="signup-password">Password</label>
            <input class="edit-form-input" id="signup-password" name="password" type="password" placeholder="Create a password" required minlength="8" autocomplete="new-password">
        </div>

        <div class="edit-form-group">
            <label class="edit-form-label" for="signup-confirm-password">Confirm Password</label>
            <input class="edit-form-input" id="signup-confirm-password" name="password_confirmation" type="password" placeholder="Confirm your password" required minlength="8" autocomplete="new-password">
        </div>

        <div class="edit-form-actions">
            <button type="submit" class="edit-form-button edit-form-button--submit edit-form-button--full">Sign Up</button>
        </div>
    </form>

    <p class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Log in</a>
    </p>
</x-guest-layout>
