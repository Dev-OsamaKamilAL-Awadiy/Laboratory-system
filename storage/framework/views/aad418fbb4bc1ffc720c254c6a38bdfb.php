<?php $__env->startSection('title', 'إضافة حالة'); ?>
<?php $__env->startSection('page-title', 'إضافة حالة جديدة'); ?>

<?php $__env->startSection('content'); ?>
<form id="caseForm" method="POST" action="<?php echo e(route('employee.cases.store')); ?>">
    <?php echo csrf_field(); ?>

    <!-- Patient Info -->
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3>👤 بيانات المريض</h3></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label>اسم المريض *</label>
                    <input type="text" name="patient_name" id="patientName" required>
                </div>
                <div class="form-group">
                    <label>هاتف المريض</label>
                    <input type="text" name="patient_phone" id="patientPhone">
                </div>
                <div class="form-group">
                    <label>العمر</label>
                    <input type="number" name="patient_age" id="patientAge" min="0" max="150">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>الجنس</label>
                    <div style="display:flex;gap:20px;margin-top:8px">
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer">
                            <input type="radio" name="patient_gender" value="male"> ذكر
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer">
                            <input type="radio" name="patient_gender" value="female"> أنثى
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>اسم الطبيب</label>
                    <input type="text" name="doctor_name" id="doctorName">
                </div>
            </div>
            <div class="form-group">
                <label>ملاحظات</label>
                <textarea name="notes" id="notes" rows="2"></textarea>
            </div>
        </div>
    </div>

    <!-- Tests Selection -->
    <div class="card" style="margin-bottom:20px">
        <div class="card-header">
            <h3>🧪 الفحوصات</h3>
            <div style="display:flex;gap:10px">
                <select id="categoryFilter" onchange="filterTests()" style="min-width:150px">
                    <option value="">جميع الفئات</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>"><?php echo e($cat); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="text" id="testSearch" onkeyup="filterTests()" placeholder="بحث..." style="min-width:150px">
            </div>
        </div>
        <div class="card-body">
            <div class="table-container" style="max-height:300px;overflow-y:auto">
                <table id="testsTable">
                    <thead style="position:sticky;top:0">
                        <tr>
                            <th><input type="checkbox" id="selectAll" onchange="toggleAllTests(this)"></th>
                            <th>الاسم</th>
                            <th>الفئة</th>
                            <th>السعر</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="test-row" data-category="<?php echo e($test->category); ?>" data-name="<?php echo e(strtolower($test->name_ar . ' ' . $test->name_en)); ?>">
                                <td>
                                    <input type="checkbox" class="test-checkbox" value="<?php echo e($test->id); ?>" onchange="toggleTest(this, <?php echo e($test->id); ?>, '<?php echo e(addslashes($test->name_ar)); ?>', <?php echo e($test->price); ?>)">
                                </td>
                                <td><?php echo e($test->name_ar); ?><br><small style="color:var(--text-secondary)"><?php echo e($test->name_en); ?></small></td>
                                <td><?php echo e($test->category); ?></td>
                                <td><?php echo e(number_format($test->price, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Selected Tests -->
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3>✅ الفحوصات المحددة</h3></div>
        <div class="card-body">
            <div id="selectedTestsContainer">
                <div class="empty-state" id="noTestsMsg"><p>لم يتم اختيار أي فحوصات</p></div>
            </div>
            <div style="margin-top:15px;display:flex;justify-content:space-between;align-items:center;border-top:2px solid var(--accent-color);padding-top:15px">
                <h3>الإجمالي:</h3>
                <h2 id="totalPrice" style="color:var(--accent-color)">0.00</h2>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div style="display:flex;gap:12px;justify-content:flex-end">
        <a href="<?php echo e(route('employee.cases.index')); ?>" class="btn btn-outline">رجوع</a>
        <button type="button" class="btn btn-success" onclick="submitCase(false)">💾 حفظ</button>
        <button type="button" class="btn btn-primary" onclick="submitCase(true)">💾 حفظ وإضافة جديدة</button>
    </div>
</form>

<!-- Hidden inputs for tests -->
<div id="testsInputContainer"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
let selectedTests = [];

function toggleTest(checkbox, id, name, price) {
    if (checkbox.checked) {
        if (!selectedTests.find(t => t.id === id)) {
            selectedTests.push({id, name, price});
        }
    } else {
        selectedTests = selectedTests.filter(t => t.id !== id);
    }
    renderSelectedTests();
}

function removeTest(id) {
    selectedTests = selectedTests.filter(t => t.id !== id);
    document.querySelectorAll('.test-checkbox').forEach(cb => {
        if (parseInt(cb.value) === id) cb.checked = false;
    });
    renderSelectedTests();
}

function renderSelectedTests() {
    const container = document.getElementById('selectedTestsContainer');
    const noMsg = document.getElementById('noTestsMsg');

    if (selectedTests.length === 0) {
        container.innerHTML = '<div class="empty-state" id="noTestsMsg"><p>لم يتم اختيار أي فحوصات</p></div>';
        document.getElementById('totalPrice').textContent = '0.00';
        return;
    }

    let html = '<table><thead><tr><th>#</th><th>الفحص</th><th>السعر</th><th></th></tr></thead><tbody>';
    let total = 0;

    selectedTests.forEach((test, i) => {
        total += test.price;
        html += `<tr>
            <td>${i + 1}</td>
            <td>${test.name}</td>
            <td>${test.price.toFixed(2)}</td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeTest(${test.id})">🗑️</button></td>
        </tr>`;
    });

    html += '</tbody></table>';
    container.innerHTML = html;
    document.getElementById('totalPrice').textContent = total.toFixed(2);
}

function toggleAllTests(checkbox) {
    document.querySelectorAll('.test-checkbox').forEach(cb => {
        cb.checked = checkbox.checked;
        const event = new Event('change');
        cb.dispatchEvent(event);
    });
}

function filterTests() {
    const category = document.getElementById('categoryFilter').value.toLowerCase();
    const search = document.getElementById('testSearch').value.toLowerCase();

    document.querySelectorAll('.test-row').forEach(row => {
        const rowCategory = row.getAttribute('data-category')?.toLowerCase() || '';
        const rowName = row.getAttribute('data-name') || '';

        const matchCategory = !category || rowCategory.includes(category);
        const matchSearch = !search || rowName.includes(search);

        row.style.display = matchCategory && matchSearch ? '' : 'none';
    });
}

async function submitCase(newCase) {
    if (!document.getElementById('patientName').value) {
        alert('اسم المريض مطلوب');
        return;
    }
    if (selectedTests.length === 0) {
        alert('يجب اختيار فحص واحد على الأقل');
        return;
    }

    const data = {
        patient_name: document.getElementById('patientName').value,
        patient_phone: document.getElementById('patientPhone').value,
        patient_age: document.getElementById('patientAge').value || null,
        patient_gender: document.querySelector('input[name="patient_gender"]:checked')?.value || null,
        doctor_name: document.getElementById('doctorName').value,
        notes: document.getElementById('notes').value,
        tests: selectedTests.map(t => ({test_id: t.id, price: t.price}))
    };

    try {
        const response = await fetch('<?php echo e(route("employee.cases.store")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify(data)
        });

        if (response.status === 419) {
            alert('انتهت الجلسة، يرجى تسجيل الدخول مرة أخرى');
            window.location.href = '<?php echo e(route("login")); ?>';
            return;
        }

        const result = await response.json();

        if (result.success) {
            if (newCase) {
                document.getElementById('caseForm').reset();
                selectedTests = [];
                document.querySelectorAll('.test-checkbox').forEach(cb => cb.checked = false);
                renderSelectedTests();
                alert('تم الحفظ بنجاح');
            } else {
                window.location.href = '<?php echo e(route("employee.cases.index")); ?>';
            }
        } else {
            if (result.errors) {
                let msgs = [];
                Object.values(result.errors).forEach(arr => msgs.push(arr[0]));
                alert(msgs.join('\n'));
            } else {
                alert(result.message || 'حدث خطأ');
            }
        }
    } catch (error) {
        alert('حدث خطأ في الاتصال: ' + error.message);
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.employee', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp2\htdocs\Laboratory system\Laboratory-system\resources\views/employee/new-case.blade.php ENDPATH**/ ?>