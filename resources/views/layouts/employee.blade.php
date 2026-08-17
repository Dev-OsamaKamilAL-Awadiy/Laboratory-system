<?php $lang = $currentLang ?? 'ar'; ?>
<?php $dir = $lang === 'ar' ? 'rtl' : 'ltr'; ?>
<?php include resource_path('views/partials/translations.blade.php'); ?>
<?php function t($trans, $lang, $key) { return $trans[$key][$lang] ?? $key; } ?>

<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $dir }}" data-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', t($trans, $lang, 'dashboard')) - {{ $lang === 'ar' ? $trans['system_name_ar'] : $trans['system_name_en'] }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-layout">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-icon">🔬</div>
                <div class="logo-text">
                    <h2>{{ $lang === 'ar' ? $trans['system_name_ar'] : $trans['system_name_en'] }}</h2>
                    <p>Medical Laboratory System</p>
                </div>
            </div>
            <nav class="sidebar-menu">
                <div class="menu-title">{{ t($trans, $lang, 'employee_menu') }}</div>
                <div class="menu-item">
                    <a href="{{ route('employee.dashboard') }}" class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                        <span class="menu-icon">📊</span>
                        <span>{{ t($trans, $lang, 'dashboard') }}</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('employee.cases.index') }}" class="{{ request()->routeIs('employee.cases.*') ? 'active' : '' }}">
                        <span class="menu-icon">📋</span>
                        <span>{{ t($trans, $lang, 'my_cases') }}</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('employee.cases.create') }}" class="{{ request()->routeIs('employee.cases.create') ? 'active' : '' }}">
                        <span class="menu-icon">➕</span>
                        <span>{{ t($trans, $lang, 'add_case') }}</span>
                    </a>
                </div>
            </nav>
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">{{ substr(auth()->user()->username, 0, 1) }}</div>
                    <div class="user-details">
                        <div class="user-name">{{ auth()->user()->username }}</div>
                        <div class="user-role">{{ t($trans, $lang, 'employee_role') }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div class="topbar-right">
                    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
                    <h1 class="page-title">@yield('page-title', t($trans, $lang, 'dashboard'))</h1>
                </div>
                <div class="topbar-left">
                    <div class="topbar-actions">
                        <a href="{{ route('lang.switch', $lang === 'ar' ? 'en' : 'ar') }}" class="topbar-action-btn lang-btn" title="{{ $lang === 'ar' ? 'Switch to English' : 'التبديل إلى العربية' }}">
                            <span class="topbar-icon">🌐</span>
                            <span class="topbar-label">{{ $lang === 'ar' ? 'EN' : 'عربي' }}</span>
                        </a>
                        <button class="topbar-action-btn theme-btn" onclick="toggleTheme()" id="themeToggle">
                            <span class="topbar-icon" id="themeIcon">{{ session('theme', 'light') === 'dark' ? '☀️' : '🌙' }}</span>
                        </button>
                        <div class="user-menu" onclick="toggleDropdown(this)">
                            <div class="avatar">{{ substr(auth()->user()->username, 0, 1) }}</div>
                            <div class="user-info-topbar">
                                <span class="user-name-topbar">{{ auth()->user()->username }}</span>
                                <span class="user-role-topbar">{{ t($trans, $lang, 'employee_role') }}</span>
                            </div>
                            <span class="topbar-icon user-chevron">▾</span>
                            <div class="dropdown-menu">
                                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                                    @csrf
                                    <button type="submit" class="dropdown-item danger">
                                        <span>🚪</span> <span>{{ t($trans, $lang, 'logout') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success" style="margin-bottom:20px;animation:slideDown 0.3s ease">✅ {{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger" style="margin-bottom:20px;animation:slideDown 0.3s ease">
                        @foreach($errors->all() as $error)
                            <div>⚠️ {{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
