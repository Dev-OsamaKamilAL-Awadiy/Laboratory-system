<?php $__env->startSection('title', 'تفاصيل الحالة'); ?>
<?php $__env->startSection('page-title', 'تفاصيل الحالة - ' . $case->case_number); ?>

<?php $__env->startSection('content'); ?>
<!-- Case Info -->
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <h3>📋 بيانات الحالة</h3>
        <div style="display:flex;gap:8px">
            <?php $__currentLoopData = ['pending' => '⏳ قيد الانتظار', 'in_progress' => '🔄 قيد التنفيذ', 'completed' => '✅ مكتمل']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <form method="POST" action="<?php echo e(route('employee.cases.status', $case->id)); ?>" style="display:inline">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <input type="hidden" name="status" value="<?php echo e($status); ?>">
                    <button type="submit" class="btn btn-sm <?php echo e($case->status === $status ? 'btn-primary' : 'btn-outline'); ?>"><?php echo e($label); ?></button>
                </form>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div><strong>رقم الحالة:</strong> <?php echo e($case->case_number); ?></div>
            <div><strong>اسم المريض:</strong> <?php echo e($case->patient_name); ?></div>
            <div><strong>الهاتف:</strong> <?php echo e($case->patient_phone ?? '-'); ?></div>
            <div><strong>العمر:</strong> <?php echo e($case->patient_age ?? '-'); ?></div>
            <div><strong>الجنس:</strong> <?php echo e($case->patient_gender === 'male' ? 'ذكر' : ($case->patient_gender === 'female' ? 'أنثى' : '-')); ?></div>
            <div><strong>الطبيب:</strong> <?php echo e($case->doctor_name ?? '-'); ?></div>
            <div><strong>التاريخ:</strong> <?php echo e($case->created_at->format('Y-m-d H:i')); ?></div>
            <div><strong>أنشأها:</strong> <?php echo e($case->creator->username); ?></div>
        </div>
        <?php if($case->notes): ?>
            <div style="margin-top:10px"><strong>ملاحظات:</strong> <?php echo e($case->notes); ?></div>
        <?php endif; ?>
        <div style="margin-top:15px;padding-top:15px;border-top:2px solid var(--accent-color)">
            <strong>السعر الإجمالي:</strong> <span style="font-size:20px;color:var(--accent-color);font-weight:700"><?php echo e(number_format($case->total_price, 2)); ?></span>
        </div>
    </div>
</div>

<!-- Tests -->
<div class="card">
    <div class="card-header">
        <h3>🧪 الفحوصات</h3>
        <button class="btn btn-sm btn-outline" onclick="window.print()">🖨️ طباعة</button>
    </div>
    <div class="card-body">
        <?php if($case->caseTests->count() > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الفحص</th>
                            <th>الفئة</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>النتيجة</th>
                            <th>تحديث</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $case->caseTests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <strong><?php echo e($ct->test->name_ar); ?></strong><br>
                                    <small style="color:var(--text-secondary)"><?php echo e($ct->test->name_en); ?></small>
                                </td>
                                <td><?php echo e($ct->test->category ?? '-'); ?></td>
                                <td><?php echo e(number_format($ct->price, 2)); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo e($ct->status === 'completed' ? 'success' : 'warning'); ?>">
                                        <?php echo e($ct->status === 'completed' ? 'مكتمل' : 'قيد الانتظار'); ?>

                                    </span>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo e($ct->result); ?>" id="result-<?php echo e($ct->id); ?>" placeholder="أدخل النتيجة" style="width:200px">
                                </td>
                                <td>
                                    <div style="display:flex;gap:4px">
                                        <form method="POST" action="<?php echo e(route('employee.case-tests.result', $ct->id)); ?>" style="display:inline">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                            <input type="hidden" name="result" id="resultInput-<?php echo e($ct->id); ?>">
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-sm btn-success" onclick="document.getElementById('resultInput-<?php echo e($ct->id); ?>').value=document.getElementById('result-<?php echo e($ct->id); ?>').value">💾</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state"><p>لا توجد فحوصات</p></div>
        <?php endif; ?>
    </div>
</div>

<div style="margin-top:20px">
    <a href="<?php echo e(route('employee.cases.index')); ?>" class="btn btn-outline">← رجوع</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.employee', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\Laboratory system\Laboratory-system\resources\views/employee/case-details.blade.php ENDPATH**/ ?>