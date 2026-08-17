@extends('layouts.admin')

@section('title', 'الإعدادات')
@section('page-title', 'الإعدادات')

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <!-- Profile -->
    <div class="card">
        <div class="card-header"><h3>👤 الملف الشخصي</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.profile') }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>اسم المستخدم</label>
                    <input type="text" value="{{ $user->username }}" disabled>
                </div>
                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ $user->phone }}">
                </div>
                <button type="submit" class="btn btn-primary">تحديث الملف</button>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="card">
        <div class="card-header"><h3>🔒 تغيير كلمة المرور</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.password') }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>كلمة المرور الحالية</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label>كلمة المرور الجديدة</label>
                    <input type="password" name="new_password" required minlength="6">
                </div>
                <div class="form-group">
                    <label>تأكيد كلمة المرور</label>
                    <input type="password" name="new_password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-warning">تغيير كلمة المرور</button>
            </form>
        </div>
    </div>
</div>

<!-- System Info -->
<div class="card" style="margin-top:20px">
    <div class="card-header"><h3>ℹ️ معلومات النظام</h3></div>
    <div class="card-body">
        <div class="form-row">
            <div><strong>اسم النظام:</strong> نظام المختبر الطبي</div>
            <div><strong>الإصدار:</strong> 1.0.0</div>
            <div><strong>الإطار:</strong> Laravel {{ app()->version() }}</div>
            <div><strong>PHP:</strong> {{ phpversion() }}</div>
        </div>
    </div>
</div>
@endsection
