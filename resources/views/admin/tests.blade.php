@extends('layouts.admin')

@section('title', 'إدارة الفحوصات')
@section('page-title', 'الفحوصات')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>🧪 إدارة الفحوصات</h3>
        <button class="btn btn-primary" onclick="document.getElementById('addTestModal').classList.add('show')">+ إضافة فحص</button>
    </div>

    <div class="card-body" style="border-bottom:1px solid var(--border-color)">
        <form method="GET" class="search-box" style="display:flex;gap:10px;flex-wrap:wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم..." style="flex:1;min-width:200px">
            <select name="category" style="min-width:150px">
                <option value="">جميع الفئات</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">بحث</button>
            <a href="{{ route('admin.tests.index') }}" class="btn btn-outline">مسح</a>
        </form>
    </div>

    <div class="card-body">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم (عربي)</th>
                        <th>الاسم (إنجليزي)</th>
                        <th>الفئة</th>
                        <th>السعر</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tests as $index => $test)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $test->name_ar }}</strong></td>
                            <td>{{ $test->name_en }}</td>
                            <td>{{ $test->category ?? '-' }}</td>
                            <td>{{ number_format($test->price, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $test->is_active ? 'success' : 'danger' }}">
                                    {{ $test->is_active ? 'نشط' : 'معطل' }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;gap:4px">
                                    <button class="btn btn-sm btn-outline" onclick='editTest({!! $test->toJSON() !!})'>✏️</button>
                                    <form method="POST" action="{{ route('admin.tests.toggle', $test->id) }}" style="display:inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-{{ $test->is_active ? 'warning' : 'success' }}">
                                            {{ $test->is_active ? '🔒' : '🔓' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.tests.destroy', $test->id) }}" style="display:inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state">لا توجد فحوصات</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:15px">{{ $tests->withQueryString()->links() }}</div>
    </div>
</div>

<!-- Add Test Modal -->
<div class="modal-overlay" id="addTestModal">
    <div class="modal">
        <div class="modal-header">
            <h3>إضافة فحص</h3>
            <button onclick="document.getElementById('addTestModal').classList.remove('show')" style="background:none;border:none;font-size:24px;cursor:pointer">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.tests.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>اسم الفحص (عربي)</label>
                    <input type="text" name="name_ar" required>
                </div>
                <div class="form-group">
                    <label>اسم الفحص (إنجليزي)</label>
                    <input type="text" name="name_en" required>
                </div>
                <div class="form-group">
                    <label>السعر</label>
                    <input type="number" name="price" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>الفئة</label>
                    <input type="text" name="category" list="categories" placeholder="اختر أو أدخل فئة">
                    <datalist id="categories">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label>الوصف (عربي)</label>
                    <textarea name="description_ar" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>الوصف (إنجليزي)</label>
                    <textarea name="description_en" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('addTestModal').classList.remove('show')">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Test Modal -->
<div class="modal-overlay" id="editTestModal">
    <div class="modal">
        <div class="modal-header">
            <h3>تعديل الفحص</h3>
            <button onclick="document.getElementById('editTestModal').classList.remove('show')" style="background:none;border:none;font-size:24px;cursor:pointer">&times;</button>
        </div>
        <form id="editTestForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>اسم الفحص (عربي)</label>
                    <input type="text" name="name_ar" id="editNameAr" required>
                </div>
                <div class="form-group">
                    <label>اسم الفحص (إنجليزي)</label>
                    <input type="text" name="name_en" id="editNameEn" required>
                </div>
                <div class="form-group">
                    <label>السعر</label>
                    <input type="number" name="price" id="editPrice" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>الفئة</label>
                    <input type="text" name="category" id="editCategory" list="categories2">
                    <datalist id="categories2">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label>الوصف (عربي)</label>
                    <textarea name="description_ar" id="editDescAr" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>الوصف (إنجليزي)</label>
                    <textarea name="description_en" id="editDescEn" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('editTestModal').classList.remove('show')">إلغاء</button>
                <button type="submit" class="btn btn-primary">تحديث</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editTest(test) {
    document.getElementById('editTestForm').action = `/admin/tests/${test.id}`;
    document.getElementById('editNameAr').value = test.name_ar;
    document.getElementById('editNameEn').value = test.name_en;
    document.getElementById('editPrice').value = test.price;
    document.getElementById('editCategory').value = test.category || '';
    document.getElementById('editDescAr').value = test.description_ar || '';
    document.getElementById('editDescEn').value = test.description_en || '';
    document.getElementById('editTestModal').classList.add('show');
}
</script>
@endsection
