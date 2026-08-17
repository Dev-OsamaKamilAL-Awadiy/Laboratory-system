<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LanguageController;

// Auth Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Language Switch
Route::get('/lang/{lang}', [LanguageController::class, 'switch'])->name('lang.switch');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
    Route::get('/users/{user}/sessions', [UserController::class, 'sessions'])->name('users.sessions');

    // Tests
    Route::get('/tests', [TestController::class, 'index'])->name('tests.index');
    Route::post('/tests', [TestController::class, 'store'])->name('tests.store');
    Route::put('/tests/{test}', [TestController::class, 'update'])->name('tests.update');
    Route::delete('/tests/{test}', [TestController::class, 'destroy'])->name('tests.destroy');
    Route::patch('/tests/{test}/toggle', [TestController::class, 'toggle'])->name('tests.toggle');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/data', [ReportController::class, 'getData'])->name('reports.data');

    // Settings
    Route::get('/settings', function () {
        $user = auth()->user();
        return view('admin.settings', compact('user'));
    })->name('settings');

    Route::put('/settings/profile', [UserController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [UserController::class, 'changePassword'])->name('settings.password');
});

// Employee Routes
Route::prefix('employee')->name('employee.')->middleware(['auth', 'employee'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');

    // Cases
    Route::get('/cases', [CaseController::class, 'index'])->name('cases.index');
    Route::get('/cases/create', [CaseController::class, 'create'])->name('cases.create');
    Route::post('/cases', [CaseController::class, 'store'])->name('cases.store');
    Route::get('/cases/{case}', [CaseController::class, 'show'])->name('case.details');
    Route::patch('/cases/{case}/status', [CaseController::class, 'updateStatus'])->name('cases.status');
    Route::delete('/cases/{case}', [CaseController::class, 'destroy'])->name('cases.destroy');

    // Case Test Results
    Route::put('/case-tests/{caseTest}/result', [CaseController::class, 'updateResult'])->name('case-tests.result');
});
