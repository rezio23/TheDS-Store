<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | The DS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@400;600;700;800&family=Krona+One&family=Modak&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('admin.partials.styles')
    <style>
        /* Auth Page */
        .auth-page {
            min-height: calc(100vh - 94px);
            padding: clamp(110px, 10vw, 130px) clamp(34px, 5vw, 104px) clamp(82px, 7vw, 120px);
            display: grid;
            place-items: center;
        }

        .auth-card {
            width: min(520px, 100%);
            padding: clamp(34px, 3.5vw, 58px) clamp(28px, 3.2vw, 52px);
            border-radius: 16px;
            background: var(--bg);
            box-shadow: 0 22px 70px rgba(0, 0, 0, 0.14);
        }

        .auth-card h1 {
            margin: 0 0 28px;
            color: var(--accent);
            font-size: clamp(1.2rem, 1.6vw, 1.55rem);
            line-height: 1.1;
            font-weight: 400;
        }

        .auth-form {
            display: grid;
            gap: 24px;
        }

        .auth-footer {
            margin-top: 28px;
            text-align: center;
            font-size: clamp(0.82rem, 0.95vw, 1.05rem);
        }

        .auth-footer a {
            color: var(--accent);
            transition: color 180ms ease;
        }

        .auth-footer a:hover {
            color: var(--ink);
        }

        /* Edit Form */
        .edit-form-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .edit-form-label {
            margin: 0;
            font-size: clamp(0.92rem, 1.1vw, 1.18rem);
            line-height: 1.2;
            font-weight: 400;
        }

        .edit-form-input {
            width: 100%;
            min-height: 52px;
            padding: 0 18px;
            border: 0;
            border-radius: 10px;
            background: #fff;
            color: var(--ink);
            font-family: var(--font-primary);
            font-size: 0.82rem;
            font-weight: 800;
            outline: 0;
            transition: box-shadow 180ms ease;
        }

        .edit-form-input::placeholder {
            color: rgba(0, 0, 0, 0.28);
            font-family: var(--font-primary);
            font-size: 0.82rem;
            font-weight: 800;
        }

        .edit-form-input:focus {
            box-shadow: 0 0 0 3px rgba(192, 107, 0, 0.14);
        }

        .edit-form-actions {
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
        }

        .edit-form-button {
            min-height: 52px;
            padding: 0 32px;
            border: 0;
            border-radius: 10px;
            font-family: var(--font-main);
            font-size: clamp(0.92rem, 1.1vw, 1.18rem);
            cursor: pointer;
            transition: transform 180ms ease, filter 180ms ease;
        }

        .edit-form-button:hover {
            transform: translateY(-1px);
            filter: brightness(1.08);
        }

        .edit-form-button--full {
            width: 100%;
            justify-content: center;
        }

        .edit-form-button--submit {
            background: var(--accent);
            color: #fff;
        }

        hr.edit-form-divider {
            border: 0;
            border-top: 1px solid rgba(192, 107, 0, 0.2);
            margin: 0 0 24px;
        }

        @media (max-width: 768px) {
            .site-header {
                grid-template-columns: 1fr auto;
                min-height: 64px;
            }
            .site-nav {
                display: none;
            }
            .icon-button.nav-toggle {
                display: grid;
            }
        }
    </style>
</head>
<body>

    <main class="auth-page" style="padding-top: clamp(40px, 6vw, 80px);">
        <div class="auth-card">
            <h1>Admin Login</h1>
            <hr class="edit-form-divider">
            <form class="auth-form" action="{{ route('admin.login') }}" method="post">
                @csrf
                @if ($errors->any())
                    <div class="auth-errors" style="color: #e63946; margin-bottom: 1rem; font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <p style="margin: 0.25rem 0;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="edit-form-group">
                    <label class="edit-form-label" for="admin-email">Email</label>
                    <input class="edit-form-input" id="admin-email" name="email" type="email" placeholder="admin@gmail.com" value="{{ old('email') }}" required>
                </div>
                <div class="edit-form-group">
                    <label class="edit-form-label" for="admin-password">Password</label>
                    <input class="edit-form-input" id="admin-password" name="password" type="password" placeholder="Enter admin password" required>
                </div>
                <div class="edit-form-actions">
                    <button type="submit" class="edit-form-button edit-form-button--submit edit-form-button--full">Log In</button>
                </div>
            </form>
            <p class="auth-footer">
                <a href="{{ route('login') }}">User Login</a>
            </p>
        </div>
    </main>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>
