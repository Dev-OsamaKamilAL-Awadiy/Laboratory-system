@extends('layouts.admin')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#e7f1ff;color:#0d6efd">👥</div>
        <div class="stat-info">
            <h3>{{ $stats['total_users'] }}</h3>
            <p>إجمالي الموظفين</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1e7dd;color:#198754">✅</div>
        <div class="stat-info">
            <h3>{{ $stats['active_users'] }}</h3>
            <p>الموظفين النشطين</p>
        </div>
    </div>
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
        <div class="stat-icon" style="background:#d1e7dd;color:#198754">💵</div>
        <div class="stat-info">
            <h3>{{ number_format($stats['today_revenue'], 2) }}</h3>
            <p>إيرادات اليوم</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1e7dd;color:#198754">🟢</div>
        <div class="stat-info">
            <h3>{{ $stats['online_users'] }}</h3>
            <p>المتصلين حالياً</p>
        </div>
    </div>
</div>

<!-- Recent Cases & Online Users -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-top:20px">
    <!-- Recent Cases -->
    <div class="card">
        <div class="card-header">
            <h3>📋 أحدث الحالات</h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline">عرض الكل</a>
        </div>
        <div class="card-body">
            @if($recentCases->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>رقم الحالة</th>
                                <th>اسم المريض</th>
                                <th>الحالة</th>
                                <th>السعر</th>
                                <th>أنشأها</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentCases as $case)
                                <tr>
                                    <td><strong>{{ $case->case_number }}</strong></td>
                                    <td>{{ $case->patient_name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $case->status_color }}">{{ $case->status_label }}</span>
                                    </td>
                                    <td>{{ number_format($case->total_price, 2) }}</td>
                                    <td>{{ $case->creator->username }}</td>
                                    <td>{{ $case->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <p>لا توجد حالات بعد</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Online Users -->
    <div class="card">
        <div class="card-header">
            <h3>🟢 المتصلين حالياً</h3>
        </div>
        <div class="card-body">
            @if($onlineUsers->count() > 0)
                @foreach($onlineUsers as $session)
                    <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border-color)">
                        <div style="width:10px;height:10px;background:#198754;border-radius:50%;animation:pulse 2s infinite"></div>
                        <div>
                            <div style="font-weight:600;font-size:14px">{{ $session->user->username }}</div>
                            <div style="font-size:12px;color:var(--text-secondary)">دخل في {{ $session->login_time->format('H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p>لا يوجد مستخدمين متصلين</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="display:flex;gap:12px;margin-top:20px">
    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">👥 إدارة المستخدمين</a>
    <a href="{{ route('admin.tests.index') }}" class="btn btn-success">🧪 إدارة الفحوصات</a>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-warning">📈 التقارير</a>
</div>
@endsection
