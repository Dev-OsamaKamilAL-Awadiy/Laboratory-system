@extends('layouts.admin')

@section('title', 'التقارير')
@section('page-title', 'التقارير')

@section('content')
<!-- Filter -->
<div class="card" style="margin-bottom:20px">
    <div class="card-header"><h3>🔍 تصفية التقرير</h3></div>
    <div class="card-body">
        <form id="reportForm" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
            <div class="form-group" style="margin:0">
                <label>من تاريخ</label>
                <input type="date" id="dateFrom" value="{{ $dateFrom }}">
            </div>
            <div class="form-group" style="margin:0">
                <label>إلى تاريخ</label>
                <input type="date" id="dateTo" value="{{ $dateTo }}">
            </div>
            <div class="form-group" style="margin:0">
                <label>الموظف</label>
                <select id="employeeId">
                    <option value="">جميع الموظفين</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ $selectedEmployee == $emp->id ? 'selected' : '' }}>{{ $emp->username }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn btn-primary" onclick="generateReport()">📊 إنشاء التقرير</button>
            <button type="button" class="btn btn-outline" onclick="window.print()">🖨️ طباعة</button>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="stats-grid" id="summaryCards" style="display:none">
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff3cd;color:#ffc107">📋</div>
        <div class="stat-info">
            <h3 id="sumCases">0</h3>
            <p>إجمالي الحالات</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e7f1ff;color:#0d6efd">🧪</div>
        <div class="stat-info">
            <h3 id="sumTests">0</h3>
            <p>إجمالي الفحوصات</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#d1e7dd;color:#198754">💰</div>
        <div class="stat-info">
            <h3 id="sumRevenue">0</h3>
            <p>إجمالي الإيرادات</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#e7f1ff;color:#0d6efd">⏱️</div>
        <div class="stat-info">
            <h3 id="sumHours">0</h3>
            <p>إجمالي ساعات العمل</p>
        </div>
    </div>
</div>

<!-- Report Results -->
<div id="reportResults"></div>
@endsection

@section('scripts')
<script>
async function generateReport() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const employeeId = document.getElementById('employeeId').value;

    document.getElementById('reportResults').innerHTML = '<div style="text-align:center;padding:40px">⏳ جاري تحميل التقرير...</div>';
    document.getElementById('summaryCards').style.display = 'none';

    try {
        const params = new URLSearchParams({date_from: dateFrom, date_to: dateTo});
        if (employeeId) params.append('employee_id', employeeId);

        const response = await fetch(`{{ route("admin.reports.data") }}?${params}`, {
            headers: {'Accept': 'application/json'}
        });
        const result = await response.json();

        if (result.success) {
            // Show summary
            document.getElementById('sumCases').textContent = result.summary.total_cases;
            document.getElementById('sumTests').textContent = result.summary.total_tests;
            document.getElementById('sumRevenue').textContent = parseFloat(result.summary.total_revenue).toFixed(2);
            document.getElementById('sumHours').textContent = result.summary.total_hours + ' ساعة';
            document.getElementById('summaryCards').style.display = 'grid';

            // Render details
            let html = '';
            result.data.forEach(item => {
                html += `<div class="card" style="margin-top:20px">
                    <div class="card-header">
                        <h3>👤 ${item.employee.username}</h3>
                        <div style="display:flex;gap:15px;font-size:14px">
                            <span>📋 ${item.total_cases} حالة</span>
                            <span>🧪 ${item.total_tests} فحص</span>
                            <span>💰 ${parseFloat(item.total_revenue).toFixed(2)}</span>
                            <span>⏱️ ${item.total_hours} ساعة</span>
                        </div>
                    </div>
                    <div class="card-body">`;

                if (item.cases_details.length > 0) {
                    html += '<div class="table-container"><table><thead><tr><th>رقم الحالة</th><th>المريض</th><th>الفحوصات</th><th>المبلغ</th><th>التاريخ</th></tr></thead><tbody>';
                    item.cases_details.forEach(cd => {
                        html += `<tr>
                            <td>${cd.case.case_number}</td>
                            <td>${cd.case.patient_name}</td>
                            <td>${cd.tests.map(t => t.test.name_ar).join(', ')}</td>
                            <td>${parseFloat(cd.case.total_price).toFixed(2)}</td>
                            <td>${new Date(cd.case.created_at).toLocaleDateString('ar')}</td>
                        </tr>`;
                    });
                    html += '</tbody></table></div>';
                } else {
                    html += '<div class="empty-state"><p>لا توجد حالات في هذه الفترة</p></div>';
                }

                html += '</div></div>';
            });

            document.getElementById('reportResults').innerHTML = html;
        }
    } catch (error) {
        document.getElementById('reportResults').innerHTML = '<div class="alert alert-danger">حدث خطأ في تحميل التقرير</div>';
    }
}

// Auto-generate if filters are set
@if($selectedEmployee)
    generateReport();
@endif
</script>
@endsection
