<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $lang = session('language', 'ar');

        if (auth()->check()) {
            $lang = auth()->user()->language ?? $lang;
        }

        app()->setLocale($lang);

        view()->share('currentLang', $lang);
        view()->share('dir', $lang === 'ar' ? 'rtl' : 'ltr');

        return $next($request);
    }
}
