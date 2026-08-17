<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSession;
use App\Models\CaseRecord;
use App\Models\Test;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'employee')->count(),
            'active_users' => User::where('role', 'employee')->where('is_active', true)->count(),
            'total_cases' => CaseRecord::count(),
            'today_cases' => CaseRecord::whereDate('created_at', today())->count(),
            'total_tests' => Test::where('is_active', true)->count(),
            'total_revenue' => CaseRecord::sum('total_price'),
            'today_revenue' => CaseRecord::whereDate('created_at', today())->sum('total_price'),
            'online_users' => UserSession::whereNull('logout_time')
                ->whereDate('login_time', today())
                ->distinct('user_id')
                ->count(),
        ];

        $recentCases = CaseRecord::with('creator')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $onlineUsers = UserSession::with('user')
            ->whereNull('logout_time')
            ->whereDate('login_time', today())
            ->orderByDesc('login_time')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentCases', 'onlineUsers'));
    }
}
