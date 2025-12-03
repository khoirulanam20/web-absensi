<?php

use App\Helpers;
use App\Http\Controllers\Admin\LocationSettingController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserAttendanceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', function () {
    // return view('welcome');
    return redirect('/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', fn () => Auth::user()->isAdmin ? redirect('/admin') : redirect('/home'));

    // USER AREA
    Route::middleware('user')->group(function () {
        Route::get('/home', HomeController::class)->name('home');

        Route::get('/apply-leave', [UserAttendanceController::class, 'applyLeave'])
            ->name('apply-leave');
        Route::post('/apply-leave', [UserAttendanceController::class, 'storeLeaveRequest'])
            ->name('store-leave-request');

        Route::get('/attendance-history', [UserAttendanceController::class, 'history'])
            ->name('attendance-history');
    });

    // ADMIN AREA
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/', fn () => redirect('/admin/dashboard'));
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Location Settings (Pengaturan Geolokasi)
        Route::get('/location-settings', [LocationSettingController::class, 'index'])
            ->name('admin.location-settings');
        Route::get('/location-settings/create', [LocationSettingController::class, 'create'])
            ->name('admin.location-settings.create');
        Route::post('/location-settings', [LocationSettingController::class, 'store'])
            ->name('admin.location-settings.store');
        Route::get('/location-settings/{id}/edit', [LocationSettingController::class, 'edit'])
            ->name('admin.location-settings.edit');
        Route::put('/location-settings/{id}', [LocationSettingController::class, 'update'])
            ->name('admin.location-settings.update');
        Route::delete('/location-settings/{id}', [LocationSettingController::class, 'destroy'])
            ->name('admin.location-settings.destroy');

        // User/Employee/Karyawan
        Route::resource('/employees', EmployeeController::class)
            ->only(['index'])
            ->names(['index' => 'admin.employees']);

        // Master Data
        Route::get('/masterdata/division', [MasterDataController::class, 'division'])
            ->name('admin.masters.division');
        Route::get('/masterdata/job-title', [MasterDataController::class, 'jobTitle'])
            ->name('admin.masters.job-title');
        Route::get('/masterdata/education', [MasterDataController::class, 'education'])
            ->name('admin.masters.education');
        Route::get('/masterdata/shift', [MasterDataController::class, 'shift'])
            ->name('admin.masters.shift');
        Route::get('/masterdata/admin', [MasterDataController::class, 'admin'])
            ->name('admin.masters.admin');

        // Presence/Absensi
        Route::get('/attendances', [AttendanceController::class, 'index'])
            ->name('admin.attendances');

        // Presence/Absensi
        Route::get('/attendances/report', [AttendanceController::class, 'report'])
            ->name('admin.attendances.report');

        // Import/Export
        Route::get('/import-export/users', [ImportExportController::class, 'users'])
            ->name('admin.import-export.users');
        Route::get('/import-export/attendances', [ImportExportController::class, 'attendances'])
            ->name('admin.import-export.attendances');

        Route::post('/users/import', [ImportExportController::class, 'importUsers'])
            ->name('admin.users.import');
        Route::post('/attendances/import', [ImportExportController::class, 'importAttendances'])
            ->name('admin.attendances.import');

        Route::get('/users/export', [ImportExportController::class, 'exportUsers'])
            ->name('admin.users.export');
        Route::get('/attendances/export', [ImportExportController::class, 'exportAttendances'])
            ->name('admin.attendances.export');

        // Payroll
        Route::get('/payroll', [PayrollController::class, 'index'])
            ->name('admin.payroll.index');
        Route::get('/payroll/salary-components', [PayrollController::class, 'salaryComponents'])
            ->name('admin.payroll.salary-components');
        Route::get('/payroll/employee-salaries', [PayrollController::class, 'employeeSalaries'])
            ->name('admin.payroll.employee-salaries');
        Route::get('/payroll/generate', [PayrollController::class, 'generate'])
            ->name('admin.payroll.generate');
        Route::get('/payroll/{id}', [PayrollController::class, 'show'])
            ->name('admin.payroll.show');
        Route::get('/payroll/{id}/pdf', [PayrollController::class, 'pdf'])
            ->name('admin.payroll.pdf');

        // Invoice Management
        Route::get('/invoice', [InvoiceController::class, 'index'])
            ->name('admin.invoice.index');
        Route::get('/invoice/{id}', [InvoiceController::class, 'show'])
            ->name('admin.invoice.show');
        Route::get('/invoice/{id}/pdf', [InvoiceController::class, 'pdf'])
            ->name('admin.invoice.pdf');

        // Asset Management
        Route::get('/assets', function () {
            return view('admin.assets.index');
        })->name('admin.assets.index');

        // Tools - Budget Calculator
        Route::view('/tools/budget-calculator', 'admin.tools.budget-calculator')
            ->name('admin.tools.budget-calculator');

        // Performance / KPI Management
        Route::get('/performance', function () {
            return view('admin.performance.index');
        })->name('admin.performance.index');
        Route::get('/performance/kpi', function () {
            return view('admin.performance.kpi');
        })->name('admin.performance.kpi');
        Route::get('/performance/cycles', function () {
            return view('admin.performance.cycles');
        })->name('admin.performance.cycles');
        Route::get('/performance/review', function () {
            return view('admin.performance.review');
        })->name('admin.performance.review');
    });
});

// Tools (accessible by semua user login, termasuk admin)
Route::middleware(['auth'])->group(function () {
    Route::view('/tools/prd-generator', 'tools.prd-generator')
        ->name('tools.prd-generator');
});

// Performance Routes (accessible by users)
Route::middleware(['auth'])->group(function () {
    Route::get('/performance', function () {
        return view('performance.index');
    })->name('performance.index');
    Route::get('/performance/tutorial', function () {
        return view('performance.tutorial');
    })->name('performance.tutorial');
    Route::get('/performance/self/{cycle}', function ($cycle) {
        return view('performance.self-assessment', ['cycleId' => $cycle]);
    })->name('performance.self');
    Route::get('/performance/manager/{cycle}', function ($cycle) {
        return view('performance.manager-review', ['cycleId' => $cycle]);
    })->name('performance.manager');
    Route::get('/performance/360/{cycle}', function ($cycle) {
        return view('performance.peer-review', ['cycleId' => $cycle]);
    })->name('performance.360');
    Route::get('/performance/results/{assessment}', function ($assessment) {
        return view('performance.results', ['assessmentId' => $assessment]);
    })->name('performance.results');
    Route::get('/performance/manager-history', function () {
        return view('performance.manager-history');
    })->name('performance.manager-history');
});

// User Payroll Routes (accessible by both user and admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/payroll', function () {
        return view('payroll.index');
    })->name('payroll.index');
    Route::get('/payroll/{id}', [\App\Http\Controllers\Admin\PayrollController::class, 'show'])
        ->name('payroll.show');
    Route::get('/payroll/{id}/pdf', [\App\Http\Controllers\Admin\PayrollController::class, 'pdf'])
        ->name('payroll.pdf');
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(Helpers::getNonRootBaseUrlPath() . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    $path = config('app.debug') ? '/livewire/livewire.js' : '/livewire/livewire.min.js';
    return Route::get(url($path), $handle);
});
