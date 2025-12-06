<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\LeaveRequest;
use App\Models\TimeEntry;
use App\Services\PdfService;
use App\Traits\Exportable;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    use Exportable;

    protected PdfService $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Export employees to CSV
     */
    public function employeesCsv(Request $request)
    {
        $this->authorize('employees.export');

        $employees = Employee::with('user')
            ->when($request->department, fn($q, $dept) => $q->where('department', $dept))
            ->get();

        return $this->exportToCsv(
            $employees,
            $this->getExportColumns('employees'),
            'employees_' . now()->format('Y-m-d') . '.csv'
        );
    }

    /**
     * Export employees to PDF
     */
    public function employeesPdf(Request $request)
    {
        $this->authorize('employees.export');

        $employees = Employee::with('user')
            ->when($request->department, fn($q, $dept) => $q->where('department', $dept))
            ->get();

        $pdf = $this->pdfService->generateEmployeeReport($employees);

        return $pdf->download('employees_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export payroll to CSV
     */
    public function payrollCsv(Request $request)
    {
        $this->authorize('payroll.export');

        $payrolls = Payroll::with('employee')
            ->when($request->month, fn($q, $month) => $q->whereMonth('period_start', $month))
            ->when($request->year, fn($q, $year) => $q->whereYear('period_start', $year))
            ->get();

        return $this->exportToCsv(
            $payrolls,
            $this->getExportColumns('payroll'),
            'payroll_' . now()->format('Y-m-d') . '.csv'
        );
    }

    /**
     * Export payroll summary to PDF
     */
    public function payrollPdf(Request $request)
    {
        $this->authorize('payroll.export');

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $payrolls = Payroll::with('employee')
            ->whereMonth('period_start', $month)
            ->whereYear('period_start', $year)
            ->get();

        $period = now()->setMonth($month)->setYear($year)->format('F Y');
        $pdf = $this->pdfService->generatePayrollReport($payrolls, $period);

        return $pdf->download('payroll_report_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Download individual payslip
     */
    public function payslip(Payroll $payroll)
    {
        $this->authorize('payroll.view');

        $pdf = $this->pdfService->generatePayslip($payroll);

        return $pdf->download("payslip_{$payroll->employee->full_name}_{$payroll->period_end->format('Y-m-d')}.pdf");
    }

    /**
     * Export leave requests to CSV
     */
    public function leaveCsv(Request $request)
    {
        $this->authorize('leave.view');

        $leaves = LeaveRequest::with('employee')
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->type, fn($q, $type) => $q->where('type', $type))
            ->get();

        return $this->exportToCsv(
            $leaves,
            $this->getExportColumns('leave'),
            'leave_requests_' . now()->format('Y-m-d') . '.csv'
        );
    }

    /**
     * Export time entries to CSV
     */
    public function timeCsv(Request $request)
    {
        $this->authorize('time.view');

        $entries = TimeEntry::with('employee')
            ->when($request->start_date, fn($q, $date) => $q->whereDate('date', '>=', $date))
            ->when($request->end_date, fn($q, $date) => $q->whereDate('date', '<=', $date))
            ->get();

        return $this->exportToCsv(
            $entries,
            $this->getExportColumns('time'),
            'time_entries_' . now()->format('Y-m-d') . '.csv'
        );
    }

    /**
     * Generate SO2 form PDF
     */
    public function so2Form(Employee $employee, Request $request)
    {
        $this->authorize('reports.export');

        $year = $request->input('year', now()->year - 1);

        $pdf = $this->pdfService->generateSO2Form($employee, $year);

        return $pdf->download("SO2_{$employee->full_name}_{$year}.pdf");
    }
}
