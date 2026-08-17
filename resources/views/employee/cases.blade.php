@extends('layouts.employee')

@section('title', 'حالاتي')
@section('page-title', 'الحالات')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>📋 حالاتي</h3>
        <a href="{{ route('employee.cases.create') }}" class="btn btn-primary">+ إضافة حالة</a>
    </div>

    <div class="card-body" style="border-bottom:1px solid var(--border-color)">
        <form method="GET" class="search-box" style="display:flex;gap:10px;flex-wrap:wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث برقم الحالة أو اسم المريض..." style="flex:1;min-width:200px">
            <select name="status" style="min-width:150px">
                <option value="">جميع الحالات</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
            </select>
            <button type="submit" class="btn btn-primary">بحث</button>
            <a href="{{ route('employee.cases.index') }}" class="btn btn-outline">مسح</a>
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
                    @forelse($cases as $case)
                        <tr>
                            <td><a href="{{ route('employee.case.details', $case->id) }}"><strong>{{ $case->case_number }}</strong></a></td>
                            <td>{{ $case->patient_name }}</td>
                            <td>{{ $case->doctor_name ?? '-' }}</td>
                            <td>{{ $case->caseTests->count() }}</td>
                            <td>{{ number_format($case->total_price, 2) }}</td>
                            <td><span class="badge badge-{{ $case->status_color }}">{{ $case->status_label }}</span></td>
                            <td>{{ $case->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div style="display:flex;gap:4px">
                                    <a href="{{ route('employee.case.details', $case->id) }}" class="btn btn-sm btn-outline">👁️</a>
                                    <form method="POST" action="{{ route('employee.cases.destroy', $case->id) }}" style="display:inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="empty-state">لا توجد حالات</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:15px">{{ $cases->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
