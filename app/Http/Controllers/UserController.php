<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List users
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'LIKE', "%$search%")
                  ->orWhere('phone', 'LIKE', "%$search%");
            });
        }

        if ($role = $request->role) {
            $query->where('role', $role);
        }

        $users = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.users', compact('users'));
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,employee',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'is_active' => true,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم إضافة المستخدم بنجاح']);
        }

        return back()->with('success', 'تم إضافة المستخدم بنجاح');
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,employee',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث المستخدم بنجاح']);
        }

        return back()->with('success', 'تم تحديث المستخدم بنجاح');
    }

    // Delete user
    public function destroy(Request $request, User $user)
    {
        $user->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم حذف المستخدم بنجاح']);
        }

        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }

    // Toggle active status
    public function toggle(Request $request, User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث الحالة بنجاح']);
        }

        return back()->with('success', 'تم تحديث الحالة بنجاح');
    }

    // Get user sessions
    public function sessions(Request $request, User $user)
    {
        $sessions = $user->sessions()
            ->orderByDesc('login_time')
            ->limit(50)
            ->get();

        return view('admin.user-sessions', compact('user', 'sessions'));
    }

    // Update own profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'phone' => $request->phone,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تحديث الملف الشخصي']);
        }

        return back()->with('success', 'تم تحديث الملف الشخصي');
    }

    // Change password
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'كلمة المرور الحالية غير صحيحة'], 422);
            }
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم تغيير كلمة المرور بنجاح']);
        }

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}
