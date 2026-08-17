<?php $__env->startSection('title', 'حالاتي'); ?>
<?php $__env->startSection('page-title', 'الحالات'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>📋 حالاتي</h3>
        <a href="<?php echo e(route('employee.cases.create')); ?>" class="btn btn-primary">+ إضافة حالة</a>
    </div>

    <div class="card-body" style="border-bottom:1px solid var(--border-color)">
        <form method="GET" class="search-box" style="display:flex;gap:10px;flex-wrap:wrap">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث برقم الحالة أو اسم المريض..." style="flex:1;min-width:200px">
            <select name="status" style="min-width:150px">
                <option value="">جميع الحالات</option>
                <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>قيد الانتظار</option>
                <option value="in_progress" <?php echo e(request('status') === 'in_progress' ? 'selected' : ''); ?>>قيد التنفيذ</option>
                <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>مكتمل</option>
            </select>
            <button type="submit" class="btn btn-primary">بحث</button>
            <a href="<?php echo e(route('employee.cases.index')); ?>" class="btn btn-outline">مسح</a>
        </form>
    </div>

    <div class="card-body">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>رقم الحالة</th>
                        <th>اسم المريض</th>
                        <th>الطبيب</th>
                        <th>الفحوصات</th>
                        <th>السعر</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $cases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><a href="<?php echo e(route('employee.case.details', $case->id)); ?>"><strong><?php echo e($case->case_number); ?></strong></a></td>
                            <td><?php echo e($case->patient_name); ?></td>
                            <td><?php echo e($case->doctor_name ?? '-'); ?></td>
                            <td><?php echo e($case->caseTests->count()); ?></td>
                            <td><?php echo e(number_format($case->total_price, 2)); ?></td>
                            <td><span class="badge badge-<?php echo e($case->status_color); ?>"><?php echo e($case->status_label); ?></span></td>
                            <td><?php echo e($case->created_at->format('Y-m-d')); ?></td>
                            <td>
                                <div style="display:flex;gap:4px">
                                    <a href="<?php echo e(route('employee.case.details', $case->id)); ?>" class="btn btn-sm btn-outline">👁️</a>
                                    <form method="POST" action="<?php echo e(route('employee.cases.destroy', $case->id)); ?>" style="display:inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8"><div class="empty-state">لا توجد حالات</div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="margin-top:15px"><?php echo e($cases->withQueryString()->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.employee', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\Laboratory system\Laboratory-system\resources\views/employee/cases.blade.php ENDPATH**/ ?>