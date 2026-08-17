<?php $lang = $currentLang ?? 'ar'; ?>
<?php $dir = $lang === 'ar' ? 'rtl' : 'ltr'; ?>
<?php include resource_path('views/partials/translations.blade.php'); ?>
<?php function t($trans, $lang, $key) { return $trans[$key][$lang] ?? $key; } ?>

<!DOCTYPE html>
<html lang="<?php echo e($lang); ?>" dir="<?php echo e($dir); ?>" data-theme="<?php echo e(session('theme', 'light')); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', t($trans, $lang, 'dashboard')); ?> - <?php echo e($lang === 'ar' ? $trans['system_name_ar'] : $trans['system_name_en']); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
</head>
<body>
    <div class="app-layout">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-icon">🔬</div>
                <div class="logo-text">
                    <h2><?php echo e($lang === 'ar' ? $trans['system_name_ar'] : $trans['system_name_en']); ?></h2>
                    <p>Medical Laboratory System</p>
                </div>
            </div>
            <nav class="sidebar-menu">
                <div class="menu-title"><?php echo e(t($trans, $lang, 'employee_menu')); ?></div>
                <div class="menu-item">
                    <a href="<?php echo e(route('employee.dashboard')); ?>" class="<?php echo e(request()->routeIs('employee.dashboard') ? 'active' : ''); ?>">
                        <span class="menu-icon">📊</span>
                        <span><?php echo e(t($trans, $lang, 'dashboard')); ?></span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="<?php echo e(route('employee.cases.index')); ?>" class="<?php echo e(request()->routeIs('employee.cases.*') ? 'active' : ''); ?>">
                        <span class="menu-icon">📋</span>
                        <span><?php echo e(t($trans, $lang, 'my_cases')); ?></span>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="<?php echo e(route('employee.cases.create')); ?>" class="<?php echo e(request()->routeIs('employee.cases.create') ? 'active' : ''); ?>">
                        <span class="menu-icon">➕</span>
                        <span><?php echo e(t($trans, $lang, 'add_case')); ?></span>
                    </a>
                </div>
            </nav>
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar"><?php echo e(substr(auth()->user()->username, 0, 1)); ?></div>
                    <div class="user-details">
                        <div class="user-name"><?php echo e(auth()->user()->username); ?></div>
                        <div class="user-role"><?php echo e(t($trans, $lang, 'employee_role')); ?></div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div class="topbar-right">
                    <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
                    <h1 class="page-title"><?php echo $__env->yieldContent('page-title', t($trans, $lang, 'dashboard')); ?></h1>
                </div>
                <div class="topbar-left">
                    <div class="topbar-actions">
                        <a href="<?php echo e(route('lang.switch', $lang === 'ar' ? 'en' : 'ar')); ?>" class="topbar-action-btn lang-btn" title="<?php echo e($lang === 'ar' ? 'Switch to English' : 'التبديل إلى العربية'); ?>">
                            <span class="topbar-icon">🌐</span>
                            <span class="topbar-label"><?php echo e($lang === 'ar' ? 'EN' : 'عربي'); ?></span>
                        </a>
                        <button class="topbar-action-btn theme-btn" onclick="toggleTheme()" id="themeToggle">
                            <span class="topbar-icon" id="themeIcon"><?php echo e(session('theme', 'light') === 'dark' ? '☀️' : '🌙'); ?></span>
                        </button>
                        <div class="user-menu" onclick="toggleDropdown(this)">
                            <div class="avatar"><?php echo e(substr(auth()->user()->username, 0, 1)); ?></div>
                            <div class="user-info-topbar">
                                <span class="user-name-topbar"><?php echo e(auth()->user()->username); ?></span>
                                <span class="user-role-topbar"><?php echo e(t($trans, $lang, 'employee_role')); ?></span>
                            </div>
                            <span class="topbar-icon user-chevron">▾</span>
                            <div class="dropdown-menu">
                                <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin:0">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item danger">
                                        <span>🚪</span> <span><?php echo e(t($trans, $lang, 'logout')); ?></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content">
                <?php if(session('success')): ?>
                    <div class="alert alert-success" style="margin-bottom:20px;animation:slideDown 0.3s ease">✅ <?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger" style="margin-bottom:20px;animation:slideDown 0.3s ease">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>⚠️ <?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp2\htdocs\Laboratory system\Laboratory-system\resources\views/layouts/employee.blade.php ENDPATH**/ ?>