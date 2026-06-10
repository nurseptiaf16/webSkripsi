<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'FiberOptic Monitor') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-body">
    <div class="auth-shell">
        <section class="auth-hero" aria-hidden="true">
            <div class="auth-hero-inner">
                <img src="{{ asset('images/logo.png') }}" alt="FiberOptic Monitor" class="auth-logo">
                <h1 class="auth-title">FiberOptic Monitor</h1>
                <p class="auth-subtitle">
                    Pantau performa OLT, pertumbuhan pelanggan, dan laporan prediksi
                    dalam satu dashboard yang rapi dan cepat.
                </p>
                <div class="auth-hero-card">
                    <div class="auth-hero-row">
                        <span class="auth-hero-label">Monitoring</span>
                        <span class="auth-hero-value">Pertumbuhan Pelanggan</span>
                    </div>
                    <div class="auth-hero-row">
                        <span class="auth-hero-label">Prediksi</span>
                        <span class="auth-hero-value">Forecast</span>
                    </div>
                    <div class="auth-hero-row">
                        <span class="auth-hero-label">Laporan</span>
                        <span class="auth-hero-value">Terintegrasi</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="auth-card" aria-label="Login form">
            <div class="auth-card-header">
                <h2>Masuk</h2>
                <p>Gunakan akun yang sudah terdaftar untuk melanjutkan.</p>
            </div>

            @if(session('status'))
                <div class="auth-alert">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf

                <div class="auth-field">
                    <label for="email" class="auth-label">Email</label>
                    <input id="email"
                           type="email"
                           name="email"
                           class="auth-input"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="username">
                    @if($errors->get('email'))
                        <div class="auth-error">{{ $errors->get('email')[0] }}</div>
                    @endif
                </div>

                <div class="auth-field">
                    <label for="password" class="auth-label">Password</label>
                    <input id="password"
                           type="password"
                           name="password"
                           class="auth-input"
                           required
                           autocomplete="current-password">
                    @if($errors->get('password'))
                        <div class="auth-error">{{ $errors->get('password')[0] }}</div>
                    @endif
                </div>

                <div class="auth-row">
                    <label class="auth-check">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="auth-link" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="auth-button">Masuk</button>
            </form>
        </section>
    </div>
</body>
</html>
