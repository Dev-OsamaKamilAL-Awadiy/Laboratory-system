<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'غير مصرح'], 401);
            }
            return redirect()->route('login');
        }

        if (!auth()->user()->isAdmin()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'غير مصرح لك بالدخول'], 403);
            }
            abort(403, 'غير مصرح لك بالدخول');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'حسابك معطل'], 403);
            }
            return redirect()->route('login')->withErrors(['username' => 'حسابك معطل']);
        }

        return $next($request);
    }
}
