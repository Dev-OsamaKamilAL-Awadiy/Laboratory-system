@extends('layouts.employee')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<!-- Welcome -->
<div class="card" style="margin-bottom:20px">
    <div class="card-body" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <div>
            <h2 style="font-size:20px;margin-bottom:4px">مرحباً، {{ auth()->user()->username }} 👋</h2>
            <p style="color:var(--text-secondary)">{{ now()->format('l, Y-m-d H:i') }}</p>
        </div>
        <a href="{{ route('employee.cases.create') }}" class="btn btn-primary">➕ إضافة حالة جديدة</a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff3cd;color:#ffc107">📋</div>
        <div class="stat-info">
            <h3>{{ $stats['total_cases'] }}</h3>
            <p>إجمالي الحالات</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#cff4fc;color:#0dcaf0">📅</div>
        <div class="stat-info">
            <h3>{{ $stats['today_cases'] }}</h3>
            <p>حالات اليوم</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1e7dd;color:#198754">✅</div>
        <div class="stat-info">
            <h3>{{ $stats['completed_cases'] }}</h3>
            <p>حالات مكتملة</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e7f1ff;color:#0d6efd">🧪</div>
        <div class="stat-info">
            <h3>{{ $stats['total_tests'] }}</h3>
            <p>إجمالي الفحوصات</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1e7dd;color:#198754">💰</div>
        <div class="stat-info">
            <h3>{{ number_format($stats['total_revenue'], 2) }}</h3>
            <p>إجمالي الإيرادات</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e7f1ff;color:#0d6efd">⏱️</div>
        <div class="stat-info">
            <h3>{{ $stats['total_hours'] }} ساعة</h3>
            <p>ساعات العمل</p>
        </div>
    </div>
</div>

<!-- Today Session -->
@if($todaySession)
<div class="card" style="margin-top:20px">
    <div class="card-header"><h3>🕐 جلسة اليوم</h3></div>
    <div class="card-body">
        <p>وقت الدخول: <strong>{{ $todaySession->login_time->format('H:i:s') }}</strong></p>
        <p>المدة: <strong>{{ round(now()->diffInSeconds($todaySession->login_time) / 3600, 2) }} ساعة</strong></p>
    </div>
</div>
@endif

<!-- Recent Cases -->
<div class="card" style="margin-top:20px">
    <div class="card-header">
        <h3>📋 أحدث حالاتي</h3>
        <a href="{{ route('employee.cases.index') }}" class="btn btn-sm btn-outline">عرض الكل</a>
    </div>
    <div class="card-body">
        @if($recentCases->count() > 0)
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
                        @foreach($recentCases as $case)
                            <tr>
                                <td><a href="{{ route('employee.case.details', $case->id) }}">{{ $case->case_number }}</a></td>
                                <td>{{ $case->patient_name }}</td>
                                <td>{{ $case->doctor_name ?? '-' }}</td>
                                <td><span class="badge badge-{{ $case->status_color }}">{{ $case->status_label }}</span></td>
                                <td>{{ number_format($case->total_price, 2) }}</td>
                                <td>{{ $case->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <p>لا توجد حالات بعد</p>
                <a href="{{ route('employee.cases.create') }}" class="btn btn-primary" style="margin-top:10px">➕ إنشاء أول حالة</a>
            </div>
        @endif
    </div>
</div>
@endsection
