<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CaseRecord;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Reports page
    public function index(Request $request)
    {
        $employees = User::where('role', 'employee')->orderBy('username')->get();
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');
        $selectedEmployee = $request->employee_id ?? '';

        return view('admin.reports', compact('employees', 'dateFrom', 'dateTo', 'selectedEmployee'));
    }

    // Get report data (AJAX)
    public function getData(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');
        $userId = $request->employee_id;

        $query = User::where('role', 'employee');

        if ($userId) {
            $query->where('id', $userId);
        }

        $employees = $query->orderBy('username')->get();

        $reportData = [];

        foreach ($employees as $employee) {
            $casesQuery = CaseRecord::where('created_by', $employee->id)
                ->whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo);

            $totalCases = (clone $casesQuery)->count();
            $totalRevenue = (clone $casesQuery)->sum('total_price');

            // Get cases with tests
            $cases = (clone $casesQuery)->orderByDesc('created_at')->get();

            $totalTests = 0;
            $casesDetails = [];

            foreach ($cases as $case) {
                $caseTests = $case->caseTests()->with('test')->get();
                $totalTests += $caseTests->count();

                $casesDetails[] = [
                    'case' => $case,
                    'tests' => $caseTests,
                ];
            }

            // Working hours
            $totalSeconds = UserSession::where('user_id', $employee->id)
                ->whereDate('login_time', '>=', $dateFrom)
                ->whereDate('login_time', '<=', $dateTo)
                ->selectRaw('COALESCE(SUM(TIMESTAMPDIFF(SECOND, login_time, COALESCE(logout_time, NOW()))), 0) as total')
                ->value('total');

            $totalHours = round($totalSeconds / 3600, 2);

            // Login sessions
            $sessions = UserSession::where('user_id', $employee->id)
                ->whereDate('login_time', '>=', $dateFrom)
                ->whereDate('login_time', '<=', $dateTo)
                ->orderByDesc('login_time')
                ->get();

            $reportData[] = [
                'employee' => $employee,
                'total_cases' => $totalCases,
                'total_tests' => $totalTests,
                'total_revenue' => $totalRevenue,
                'total_hours' => $totalHours,
                'cases_details' => $casesDetails,
                'sessions' => $sessions,
            ];
        }

        // Overall summary
        $summary = [
            'total_cases' => collect($reportData)->sum('total_cases'),
            'total_tests' => collect($reportData)->sum('total_tests'),
            'total_revenue' => collect($reportData)->sum('total_revenue'),
            'total_hours' => collect($reportData)->sum('total_hours'),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];

        return response()->json([
            'success' => true,
            'data' => $reportData,
            'summary' => $summary,
        ]);
    }
}
