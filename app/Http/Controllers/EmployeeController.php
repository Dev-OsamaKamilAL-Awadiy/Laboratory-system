<?php

namespace App\Http\Controllers;

use App\Models\CaseRecord;
use App\Models\Test;
use App\Models\UserSession;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Stats
        $stats = [
            'total_cases' => CaseRecord::where('created_by', $user->id)->count(),
            'today_cases' => CaseRecord::where('created_by', $user->id)->whereDate('created_at', today())->count(),
            'completed_cases' => CaseRecord::where('created_by', $user->id)->where('status', 'completed')->count(),
            'total_tests' => CaseRecord::where('created_by', $user->id)
                ->join('case_tests', 'cases.id', '=', 'case_tests.case_id')
                ->count(),
            'today_tests' => CaseRecord::where('created_by', $user->id)
                ->whereDate('cases.created_at', today())
                ->join('case_tests', 'cases.id', '=', 'case_tests.case_id')
                ->count(),
            'total_revenue' => CaseRecord::where('created_by', $user->id)->sum('total_price'),
            'today_revenue' => CaseRecord::where('created_by', $user->id)->whereDate('created_at', today())->sum('total_price'),
        ];

        // Working hours
        $totalSeconds = UserSession::where('user_id', $user->id)
            ->selectRaw('COALESCE(SUM(TIMESTAMPDIFF(SECOND, login_time, COALESCE(logout_time, NOW()))), 0) as total')
            ->value('total');
        $stats['total_hours'] = round($totalSeconds / 3600, 2);

        // Today's session
        $todaySession = UserSession::where('user_id', $user->id)
            ->whereDate('login_time', today())
            ->latest('login_time')
            ->first();

        // Recent cases
        $recentCases = CaseRecord::where('created_by', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('employee.dashboard', compact('stats', 'todaySession', 'recentCases'));
    }
}
