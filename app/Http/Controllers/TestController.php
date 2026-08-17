<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    // List tests
    public function index(Request $request)
    {
        $query = Test::query();

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%$search%")
                  ->orWhere('name_en', 'LIKE', "%$search%")
                  ->orWhere('category', 'LIKE', "%$search%");
            });
        }

        if ($category = $request->category) {
            $query->where('category', $category);
        }

        $tests = $query->orderBy('category')->orderBy('name_ar')->paginate(20);
        $categories = Test::getCategories();

        return view('admin.tests', compact('tests', 'categories'));
    }

    // Store test
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:200',
            'name_en' => 'required|string|max:200',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
        ]);

        Test::create($request->only([
            'name_ar', 'name_en', 'price', 'category', 'description_ar', 'description_en'
        ]));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم إضافة الفحص بنجاح']);
        }

        return back()->with('success', 'تم إضافة الفحص بنجاح');
    }

    // Update test
    public function update(Request $request, Test $test)
    {
        $request->validate([
            'name_ar' => 'required|string|max:200',
            'name_en' => 'required|string|max:200',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
        ]);

        $test->update($request->only([
            'name_ar', 'name_en', 'price', 'category', 'description_ar', 'description_en'
        ]));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث الفحص بنجاح']);
        }

        return back()->with('success', 'تم تحديث الفحص بنجاح');
    }

    // Delete test
    public function destroy(Request $request, Test $test)
    {
        $test->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم حذف الفحص بنجاح']);
        }

        return back()->with('success', 'تم حذف الفحص بنجاح');
    }

    // Toggle active
    public function toggle(Request $request, Test $test)
    {
        $test->update(['is_active' => !$test->is_active]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث الحالة بنجاح']);
        }

        return back()->with('success', 'تم تحديث الحالة بنجاح');
    }
}
