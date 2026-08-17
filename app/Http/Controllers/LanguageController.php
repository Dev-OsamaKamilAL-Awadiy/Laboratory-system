<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, $lang)
    {
        // Handle theme switching
        if ($request->has('theme')) {
            $theme = in_array($request->theme, ['light', 'dark']) ? $request->theme : 'light';
            session(['theme' => $theme]);
            return response()->json(['success' => true, 'theme' => $theme]);
        }

        // Handle language switching
        if (in_array($lang, ['ar', 'en'])) {
            session(['language' => $lang]);
            app()->setLocale($lang);

            if (auth()->check()) {
                auth()->user()->update(['language' => $lang]);
            }
        }

        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json(['success' => true, 'lang' => $lang]);
        }

        return redirect()->back();
    }
}
