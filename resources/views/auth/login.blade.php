<?php
$lang = session('language', 'ar');
$dir = $lang === 'ar' ? 'rtl' : 'ltr';
include resource_path('views/partials/translations.blade.php');
function tLogin($trans, $lang, $key) { return $trans[$key][$lang] ?? $key; }
?>
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $dir }}" data-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ tLogin($trans, $lang, 'login') }} - {{ $lang === 'ar' ? $trans['system_name_ar'] : $trans['system_name_en'] }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="login-page">
        <div class="login-actions">
            <a href="{{ route('lang.switch', $lang === 'ar' ? 'en' : 'ar') }}" class="topbar-action-btn lang-btn" title="{{ $lang === 'ar' ? 'Switch to English' : 'التبديل إلى العربية' }}">
                <span class="topbar-icon">🌐</span>
                <span class="topbar-label">{{ $lang === 'ar' ? 'EN' : 'عربي' }}</span>
            </a>
            <button class="topbar-action-btn theme-btn" onclick="toggleTheme()" id="themeToggle">
                <span class="topbar-icon" id="themeIcon">{{ session('theme', 'light') === 'dark' ? '☀️' : '🌙' }}</span>
            </button>
        </div>
        <div class="login-container">
            <div class="login-logo">
                <div class="logo-icon">🔬</div>
                <h1>{{ $lang === 'ar' ? $trans['system_name_ar'] : $trans['system_name_en'] }}</h1>
                <p>Medical Laboratory System</p>
            </div>

            @if($errors->any())
                <div class="login-error show">
                    <span>⚠️</span>
                    <span>{{ $errors->first('username') }}</span>
                </div>
            @endif

            <form class="login-form" method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label>{{ tLogin($trans, $lang, 'username') }}</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text" name="username" value="{{ old('username') }}" required placeholder="{{ tLogin($trans, $lang, 'enter_username') }}" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ tLogin($trans, $lang, 'password') }}</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" name="password" required placeholder="{{ tLogin($trans, $lang, 'enter_password') }}">
                    </div>
                </div>
                <button type="submit" class="btn-login">{{ tLogin($trans, $lang, 'login_button') }}</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
