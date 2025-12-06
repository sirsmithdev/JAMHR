<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\TimeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\SchedulingController;
use App\Http\Controllers\ComplianceController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employees
    Route::resource('employees', EmployeeController::class);

    // Payroll
    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/create', [PayrollController::class, 'create'])->name('payroll.create');
    Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::post('/payroll/{payroll}/finalize', [PayrollController::class, 'finalize'])->name('payroll.finalize');
    Route::post('/payroll/{payroll}/paid', [PayrollController::class, 'markPaid'])->name('payroll.paid');
    Route::get('/payroll-calculator', [PayrollController::class, 'calculator'])->name('payroll.calculator');
    Route::post('/payroll-calculator', [PayrollController::class, 'calculate'])->name('payroll.calculate');

    // Time & Attendance
    Route::get('/time', [TimeController::class, 'index'])->name('time.index');
    Route::post('/time', [TimeController::class, 'store'])->name('time.store');
    Route::put('/time/{timeEntry}', [TimeController::class, 'update'])->name('time.update');
    Route::delete('/time/{timeEntry}', [TimeController::class, 'destroy'])->name('time.destroy');

    // Leave Management
    Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');
    Route::get('/leave/create', [LeaveController::class, 'create'])->name('leave.create');
    Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
    Route::post('/leave/{leaveRequest}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/{leaveRequest}/reject', [LeaveController::class, 'reject'])->name('leave.reject');
    Route::delete('/leave/{leaveRequest}', [LeaveController::class, 'destroy'])->name('leave.destroy');

    // Incidents
    Route::get('/incidents', [IncidentController::class, 'index'])->name('incidents.index');
    Route::get('/incidents/create', [IncidentController::class, 'create'])->name('incidents.create');
    Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
    Route::get('/incidents/{incident}', [IncidentController::class, 'show'])->name('incidents.show');
    Route::put('/incidents/{incident}', [IncidentController::class, 'update'])->name('incidents.update');
    Route::delete('/incidents/{incident}', [IncidentController::class, 'destroy'])->name('incidents.destroy');

    // Performance
    Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');
    Route::get('/performance/create', [PerformanceController::class, 'create'])->name('performance.create');
    Route::post('/performance', [PerformanceController::class, 'store'])->name('performance.store');
    Route::get('/performance/{appraisal}', [PerformanceController::class, 'show'])->name('performance.show');
    Route::get('/performance/{appraisal}/edit', [PerformanceController::class, 'edit'])->name('performance.edit');
    Route::put('/performance/{appraisal}', [PerformanceController::class, 'update'])->name('performance.update');
    Route::delete('/performance/{appraisal}', [PerformanceController::class, 'destroy'])->name('performance.destroy');

    // Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Scheduling
    Route::get('/scheduling', [SchedulingController::class, 'index'])->name('scheduling.index');
    Route::post('/scheduling', [SchedulingController::class, 'store'])->name('scheduling.store');
    Route::put('/scheduling/{shift}', [SchedulingController::class, 'update'])->name('scheduling.update');
    Route::delete('/scheduling/{shift}', [SchedulingController::class, 'destroy'])->name('scheduling.destroy');
    Route::post('/scheduling/publish', [SchedulingController::class, 'publish'])->name('scheduling.publish');

    // Compliance
    Route::get('/compliance', [ComplianceController::class, 'index'])->name('compliance.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Kiosk (public for clock in/out)
Route::get('/kiosk', [KioskController::class, 'index'])->name('kiosk.index');
Route::post('/kiosk/clock-in', [KioskController::class, 'clockIn'])->name('kiosk.clock-in');
Route::post('/kiosk/clock-out', [KioskController::class, 'clockOut'])->name('kiosk.clock-out');
Route::post('/kiosk/start-break', [KioskController::class, 'startBreak'])->name('kiosk.start-break');
Route::post('/kiosk/end-break', [KioskController::class, 'endBreak'])->name('kiosk.end-break');

require __DIR__.'/auth.php';
