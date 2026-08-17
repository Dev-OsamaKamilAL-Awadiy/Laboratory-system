@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')
@section('page-title', 'المستخدمين')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>👥 إدارة المستخدمين</h3>
        <button class="btn btn-primary" onclick="document.getElementById('addModal').classList.add('show')">+ إضافة مستخدم</button>
    </div>

    <div class="card-body" style="border-bottom:1px solid var(--border-color)">
        <form method="GET" class="search-box" style="display:flex;gap:10px;flex-wrap:wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الهاتف..." style="flex:1;min-width:200px">
            <select name="role" style="min-width:150px">
                <option value="">جميع الأدوار</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>مدير</option>
                <option value="employee" {{ request('role') === 'employee' ? 'selected' : '' }}>موظف</option>
            </select>
            <button type="submit" class="btn btn-primary">بحث</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">مسح</a>
        </form>
    </div>

    <div class="card-body">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المستخدم</th>
                        <th>الهاتف</th>
                        <th>الدور</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $user->username }}</strong></td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $user->role === 'admin' ? 'info' : 'success' }}">
                                    {{ $user->role === 'admin' ? 'مدير' : 'موظف' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                                    {{ $user->is_active ? 'نشط' : 'معطل' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div style="display:flex;gap:4px">
                                    <button class="btn btn-sm btn-outline" onclick="editUser({{ $user->id }}, '{{ $user->username }}', '{{ $user->phone }}', '{{ $user->role }}')">✏️</button>
                                    <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}" style="display:inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-{{ $user->is_active ? 'warning' : 'success' }}">
                                            {{ $user->is_active ? '🔒' : '🔓' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display:inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="empty-state">لا يوجد مستخدمين</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:15px">{{ $users->withQueryString()->links() }}</div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal">
        <div class="modal-header">
            <h3>إضافة مستخدم</h3>
            <button onclick="document.getElementById('addModal').classList.remove('show')" style="background:none;border:none;font-size:24px;cursor:pointer">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>اسم المستخدم</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>كلمة المرور</label>
                    <input type="password" name="password" required minlength="6">
                </div>
                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>الدور</label>
                    <select name="role" required>
                        <option value="employee">موظف</option>
                        <option value="admin">مدير</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('addModal').classList.remove('show')">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <h3>تعديل المستخدم</h3>
            <button onclick="document.getElementById('editModal').classList.remove('show')" style="background:none;border:none;font-size:24px;cursor:pointer">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>اسم المستخدم</label>
                    <input type="text" name="username" id="editUsername" required>
                </div>
                <div class="form-group">
                    <label>كلمة المرور الجديدة (اتركها فارغة لعدم التغيير)</label>
                    <input type="password" name="password">
                </div>
                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="text" name="phone" id="editPhone">
                </div>
                <div class="form-group">
                    <label>الدور</label>
                    <select name="role" id="editRole" required>
                        <option value="employee">موظف</option>
                        <option value="admin">مدير</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('editModal').classList.remove('show')">إلغاء</button>
                <button type="submit" class="btn btn-primary">تحديث</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editUser(id, username, phone, role) {
    document.getElementById('editForm').action = `/admin/users/${id}`;
    document.getElementById('editUsername').value = username;
    document.getElementById('editPhone').value = phone;
    document.getElementById('editRole').value = role;
    document.getElementById('editModal').classList.add('show');
}
</script>
@endsection
