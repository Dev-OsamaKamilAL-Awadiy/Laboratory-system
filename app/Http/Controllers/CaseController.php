<?php

namespace App\Http\Controllers;

use App\Models\CaseRecord;
use App\Models\CaseTest;
use App\Models\Test;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    // List cases (employee's own)
    public function index(Request $request)
    {
        $query = CaseRecord::where('created_by', auth()->id());

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'LIKE', "%$search%")
                  ->orWhere('patient_name', 'LIKE', "%$search%")
                  ->orWhere('doctor_name', 'LIKE', "%$search%");
            });
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $cases = $query->orderByDesc('created_at')->paginate(20);

        return view('employee.cases', compact('cases'));
    }

    // Show create form
    public function create()
    {
        $tests = Test::where('is_active', true)->orderBy('category')->orderBy('name_ar')->get();
        $categories = Test::getCategories();

        return view('employee.new-case', compact('tests', 'categories'));
    }

    // Store new case
    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:150',
            'patient_phone' => 'nullable|string|max:20',
            'patient_age' => 'nullable|integer|min:0|max:150',
            'patient_gender' => 'nullable|in:male,female',
            'doctor_name' => 'nullable|string|max:150',
            'notes' => 'nullable|string',
            'tests' => 'required|array|min:1',
            'tests.*.test_id' => 'required|exists:tests,id',
            'tests.*.price' => 'required|numeric|min:0',
        ]);

        $totalPrice = collect($request->tests)->sum('price');

        $case = CaseRecord::create([
            'case_number' => CaseRecord::generateCaseNumber(),
            'patient_name' => $request->patient_name,
            'patient_phone' => $request->patient_phone,
            'patient_age' => $request->patient_age,
            'patient_gender' => $request->patient_gender,
            'doctor_name' => $request->doctor_name,
            'notes' => $request->notes,
            'status' => 'pending',
            'total_price' => $totalPrice,
            'created_by' => auth()->id(),
        ]);

        foreach ($request->tests as $test) {
            CaseTest::create([
                'case_id' => $case->id,
                'test_id' => $test['test_id'],
                'price' => $test['price'],
                'status' => 'pending',
            ]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم إنشاء الحالة بنجاح', 'case_id' => $case->id]);
        }

        return redirect()->route('employee.case.details', $case->id)
            ->with('success', 'تم إنشاء الحالة بنجاح');
    }

    // Show case details
    public function show(CaseRecord $case)
    {
        if ($case->created_by !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $case->load(['caseTests.test', 'creator']);

        return view('employee.case-details', compact('case'));
    }

    // Update case status
    public function updateStatus(Request $request, CaseRecord $case)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $case->update(['status' => $request->status]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث الحالة بنجاح']);
        }

        return back()->with('success', 'تم تحديث الحالة بنجاح');
    }

    // Update test result
    public function updateResult(Request $request, CaseTest $caseTest)
    {
        $request->validate([
            'result' => 'nullable|string',
            'status' => 'required|in:pending,completed',
        ]);

        $caseTest->update([
            'result' => $request->result,
            'status' => $request->status,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث النتيجة بنجاح']);
        }

        return back()->with('success', 'تم تحديث النتيجة بنجاح');
    }

    // Delete case
    public function destroy(Request $request, CaseRecord $case)
    {
        $case->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم حذف الحالة بنجاح']);
        }

        return back()->with('success', 'تم حذف الحالة بنجاح');
    }
}
