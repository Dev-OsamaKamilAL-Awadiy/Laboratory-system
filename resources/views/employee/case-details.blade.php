@extends('layouts.employee')

@section('title', 'تفاصيل الحالة')
@section('page-title', 'تفاصيل الحالة - ' . $case->case_number)

@section('content')
<!-- Case Info -->
<div class="card" style="margin-bottom:20px">
    <div class="card-header">
        <h3>📋 بيانات الحالة</h3>
        <div style="display:flex;gap:8px">
            @foreach(['pending' => '⏳ قيد الانتظار', 'in_progress' => '🔄 قيد التنفيذ', 'completed' => '✅ مكتمل'] as $status => $label)
                <form method="POST" action="{{ route('employee.cases.status', $case->id) }}" style="display:inline">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $status }}">
                    <button type="submit" class="btn btn-sm {{ $case->status === $status ? 'btn-primary' : 'btn-outline' }}">{{ $label }}</button>
                </form>
            @endforeach
        </div>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div><strong>رقم الحالة:</strong> {{ $case->case_number }}</div>
            <div><strong>اسم المريض:</strong> {{ $case->patient_name }}</div>
            <div><strong>الهاتف:</strong> {{ $case->patient_phone ?? '-' }}</div>
            <div><strong>العمر:</strong> {{ $case->patient_age ?? '-' }}</div>
            <div><strong>الجنس:</strong> {{ $case->patient_gender === 'male' ? 'ذكر' : ($case->patient_gender === 'female' ? 'أنثى' : '-') }}</div>
            <div><strong>الطبيب:</strong> {{ $case->doctor_name ?? '-' }}</div>
            <div><strong>التاريخ:</strong> {{ $case->created_at->format('Y-m-d H:i') }}</div>
            <div><strong>أنشأها:</strong> {{ $case->creator->username }}</div>
        </div>
        @if($case->notes)
            <div style="margin-top:10px"><strong>ملاحظات:</strong> {{ $case->notes }}</div>
        @endif
        <div style="margin-top:15px;padding-top:15px;border-top:2px solid var(--accent-color)">
            <strong>السعر الإجمالي:</strong> <span style="font-size:20px;color:var(--accent-color);font-weight:700">{{ number_format($case->total_price, 2) }}</span>
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
        @if($case->caseTests->count() > 0)
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
                        @foreach($case->caseTests as $ct)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $ct->test->name_ar }}</strong><br>
                                    <small style="color:var(--text-secondary)">{{ $ct->test->name_en }}</small>
                                </td>
                                <td>{{ $ct->test->category ?? '-' }}</td>
                                <td>{{ number_format($ct->price, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $ct->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ $ct->status === 'completed' ? 'مكتمل' : 'قيد الانتظار' }}
                                    </span>
                                </td>
                                <td>
                                    <input type="text" value="{{ $ct->result }}" id="result-{{ $ct->id }}" placeholder="أدخل النتيجة" style="width:200px">
                                </td>
                                <td>
                                    <div style="display:flex;gap:4px">
                                        <form method="POST" action="{{ route('employee.case-tests.result', $ct->id) }}" style="display:inline">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="result" id="resultInput-{{ $ct->id }}">
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-sm btn-success" onclick="document.getElementById('resultInput-{{ $ct->id }}').value=document.getElementById('result-{{ $ct->id }}').value">💾</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state"><p>لا توجد فحوصات</p></div>
        @endif
    </div>
</div>

<div style="margin-top:20px">
    <a href="{{ route('employee.cases.index') }}" class="btn btn-outline">← رجوع</a>
</div>
@endsection
