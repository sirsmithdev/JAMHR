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
use App\Http\Controllers\HiringController;
use App\Http\Controllers\TerminationController;
use App\Http\Controllers\DisciplinaryController;
use App\Http\Controllers\PayslipController;
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

    // Payslips
    Route::get('/payslips/bulk-email', [PayslipController::class, 'bulkEmailForm'])->name('payslips.bulk-email');
    Route::post('/payslips/bulk-email', [PayslipController::class, 'bulkEmail'])->name('payslips.bulk-email.send');
    Route::post('/payslips/generate-batch', [PayslipController::class, 'generateBatch'])->name('payslips.generate-batch');
    Route::get('/payslips/{payroll}', [PayslipController::class, 'show'])->name('payslips.show');
    Route::get('/payslips/{payroll}/download', [PayslipController::class, 'download'])->name('payslips.download');
    Route::get('/payslips/{payroll}/view', [PayslipController::class, 'view'])->name('payslips.view');
    Route::get('/payslips/{payroll}/preview', [PayslipController::class, 'preview'])->name('payslips.preview');
    Route::post('/payslips/{payroll}/email', [PayslipController::class, 'email'])->name('payslips.email');

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

    // Hiring - Job Postings
    Route::get('/hiring', [HiringController::class, 'index'])->name('hiring.index');
    Route::get('/hiring/postings/create', [HiringController::class, 'create'])->name('hiring.postings.create');
    Route::post('/hiring/postings', [HiringController::class, 'store'])->name('hiring.postings.store');
    Route::get('/hiring/postings/{posting}', [HiringController::class, 'show'])->name('hiring.postings.show');
    Route::get('/hiring/postings/{posting}/edit', [HiringController::class, 'edit'])->name('hiring.postings.edit');
    Route::put('/hiring/postings/{posting}', [HiringController::class, 'update'])->name('hiring.postings.update');
    Route::delete('/hiring/postings/{posting}', [HiringController::class, 'destroy'])->name('hiring.postings.destroy');

    // Hiring - Applications
    Route::get('/hiring/applications', [HiringController::class, 'applications'])->name('hiring.applications');
    Route::get('/hiring/applications/create/{posting?}', [HiringController::class, 'applicationCreate'])->name('hiring.applications.create');
    Route::post('/hiring/applications', [HiringController::class, 'applicationStore'])->name('hiring.applications.store');
    Route::get('/hiring/applications/{application}', [HiringController::class, 'applicationShow'])->name('hiring.applications.show');
    Route::put('/hiring/applications/{application}', [HiringController::class, 'applicationUpdate'])->name('hiring.applications.update');
    Route::post('/hiring/applications/{application}/hire', [HiringController::class, 'hireApplication'])->name('hiring.applications.hire');

    // Hiring - Interviews
    Route::get('/hiring/interviews', [HiringController::class, 'interviews'])->name('hiring.interviews');
    Route::get('/hiring/interviews/create/{application?}', [HiringController::class, 'interviewCreate'])->name('hiring.interviews.create');
    Route::post('/hiring/interviews', [HiringController::class, 'interviewStore'])->name('hiring.interviews.store');
    Route::get('/hiring/interviews/{interview}', [HiringController::class, 'interviewShow'])->name('hiring.interviews.show');
    Route::put('/hiring/interviews/{interview}', [HiringController::class, 'interviewUpdate'])->name('hiring.interviews.update');

    // Terminations
    Route::get('/terminations', [TerminationController::class, 'index'])->name('terminations.index');
    Route::get('/terminations/create', [TerminationController::class, 'create'])->name('terminations.create');
    Route::post('/terminations', [TerminationController::class, 'store'])->name('terminations.store');
    Route::get('/terminations/{termination}', [TerminationController::class, 'show'])->name('terminations.show');
    Route::get('/terminations/{termination}/edit', [TerminationController::class, 'edit'])->name('terminations.edit');
    Route::put('/terminations/{termination}', [TerminationController::class, 'update'])->name('terminations.update');
    Route::delete('/terminations/{termination}', [TerminationController::class, 'destroy'])->name('terminations.destroy');
    Route::post('/terminations/{termination}/checklist', [TerminationController::class, 'updateChecklist'])->name('terminations.checklist');
    Route::get('/terminations/{termination}/exit-interview', [TerminationController::class, 'exitInterview'])->name('terminations.exit-interview');
    Route::post('/terminations/{termination}/exit-interview', [TerminationController::class, 'storeExitInterview'])->name('terminations.exit-interview.store');
    Route::get('/terminations/{termination}/calculate-pay', [TerminationController::class, 'calculateFinalPay'])->name('terminations.calculate-pay');

    // Disciplinary Actions
    Route::get('/disciplinary', [DisciplinaryController::class, 'index'])->name('disciplinary.index');
    Route::get('/disciplinary/create/{employee?}', [DisciplinaryController::class, 'create'])->name('disciplinary.create');
    Route::post('/disciplinary', [DisciplinaryController::class, 'store'])->name('disciplinary.store');
    Route::get('/disciplinary/{disciplinary}', [DisciplinaryController::class, 'show'])->name('disciplinary.show');
    Route::get('/disciplinary/{disciplinary}/edit', [DisciplinaryController::class, 'edit'])->name('disciplinary.edit');
    Route::put('/disciplinary/{disciplinary}', [DisciplinaryController::class, 'update'])->name('disciplinary.update');
    Route::delete('/disciplinary/{disciplinary}', [DisciplinaryController::class, 'destroy'])->name('disciplinary.destroy');
    Route::post('/disciplinary/{disciplinary}/acknowledge', [DisciplinaryController::class, 'acknowledge'])->name('disciplinary.acknowledge');
    Route::post('/disciplinary/{disciplinary}/response', [DisciplinaryController::class, 'addResponse'])->name('disciplinary.response');
    Route::post('/disciplinary/{disciplinary}/pip-outcome', [DisciplinaryController::class, 'updatePipOutcome'])->name('disciplinary.pip-outcome');
    Route::get('/disciplinary/employee/{employee}', [DisciplinaryController::class, 'employeeHistory'])->name('disciplinary.employee-history');

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
