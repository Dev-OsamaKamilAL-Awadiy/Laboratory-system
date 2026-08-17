<?php $__env->startSection('title', 'لوحة التحكم'); ?>
<?php $__env->startSection('page-title', 'لوحة التحكم'); ?>

<?php $__env->startSection('content'); ?>
<!-- Welcome -->
<div class="card" style="margin-bottom:20px">
    <div class="card-body" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <div>
            <h2 style="font-size:20px;margin-bottom:4px">مرحباً، <?php echo e(auth()->user()->username); ?> 👋</h2>
            <p style="color:var(--text-secondary)"><?php echo e(now()->format('l, Y-m-d H:i')); ?></p>
        </div>
        <a href="<?php echo e(route('employee.cases.create')); ?>" class="btn btn-primary">➕ إضافة حالة جديدة</a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff3cd;color:#ffc107">📋</div>
        <div class="stat-info">
            <h3><?php echo e($stats['total_cases']); ?></h3>
            <p>إجمالي الحالات</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#cff4fc;color:#0dcaf0">📅</div>
        <div class="stat-info">
            <h3><?php echo e($stats['today_cases']); ?></h3>
            <p>حالات اليوم</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1e7dd;color:#198754">✅</div>
        <div class="stat-info">
            <h3><?php echo e($stats['completed_cases']); ?></h3>
            <p>حالات مكتملة</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e7f1ff;color:#0d6efd">🧪</div>
        <div class="stat-info">
            <h3><?php echo e($stats['total_tests']); ?></h3>
            <p>إجمالي الفحوصات</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1e7dd;color:#198754">💰</div>
        <div class="stat-info">
            <h3><?php echo e(number_format($stats['total_revenue'], 2)); ?></h3>
            <p>إجمالي الإيرادات</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e7f1ff;color:#0d6efd">⏱️</div>
        <div class="stat-info">
            <h3><?php echo e($stats['total_hours']); ?> ساعة</h3>
            <p>ساعات العمل</p>
        </div>
    </div>
</div>

<!-- Today Session -->
<?php if($todaySession): ?>
<div class="card" style="margin-top:20px">
    <div class="card-header"><h3>🕐 جلسة اليوم</h3></div>
    <div class="card-body">
        <p>وقت الدخول: <strong><?php echo e($todaySession->login_time->format('H:i:s')); ?></strong></p>
        <p>المدة: <strong><?php echo e(round(now()->diffInSeconds($todaySession->login_time) / 3600, 2)); ?> ساعة</strong></p>
    </div>
</div>
<?php endif; ?>

<!-- Recent Cases -->
<div class="card" style="margin-top:20px">
    <div class="card-header">
        <h3>📋 أحدث حالاتي</h3>
        <a href="<?php echo e(route('employee.cases.index')); ?>" class="btn btn-sm btn-outline">عرض الكل</a>
    </div>
    <div class="card-body">
        <?php if($recentCases->count() > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>رقم الحالة</th>
                            <th>اسم المريض</th>
                            <th>الطبيب</th>
                            <th>الحالة</th>
                            <th>السعر</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $recentCases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><a href="<?php echo e(route('employee.case.details', $case->id)); ?>"><?php echo e($case->case_number); ?></a></td>
                                <td><?php echo e($case->patient_name); ?></td>
                                <td><?php echo e($case->doctor_name ?? '-'); ?></td>
                                <td><span class="badge badge-<?php echo e($case->status_color); ?>"><?php echo e($case->status_label); ?></span></td>
                                <td><?php echo e(number_format($case->total_price, 2)); ?></td>
                                <td><?php echo e($case->created_at->format('Y-m-d H:i')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>لا توجد حالات بعد</p>
                <a href="<?php echo e(route('employee.cases.create')); ?>" class="btn btn-primary" style="margin-top:10px">➕ إنشاء أول حالة</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.employee', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\Laboratory system\Laboratory-system\resources\views/employee/dashboard.blade.php ENDPATH**/ ?>