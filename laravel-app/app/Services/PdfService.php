<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class PdfService
{
    /**
     * Generate a payslip PDF
     */
    public function generatePayslip(Payroll $payroll): \Barryvdh\DomPDF\PDF
    {
        $payroll->load('employee');

        return Pdf::loadView('pdf.payslip', [
            'payroll' => $payroll,
            'employee' => $payroll->employee,
            'company' => [
                'name' => config('app.company_name', 'JamHR Company'),
                'address' => config('app.company_address', 'Kingston, Jamaica'),
                'phone' => config('app.company_phone', ''),
                'email' => config('app.company_email', ''),
            ],
        ])->setPaper('a4', 'portrait');
    }

    /**
     * Generate employee report PDF
     */
    public function generateEmployeeReport(Collection $employees): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('pdf.employee-report', [
            'employees' => $employees,
            'generated_at' => now(),
            'generated_by' => auth()->user()->name,
        ])->setPaper('a4', 'landscape');
    }

    /**
     * Generate payroll summary report
     */
    public function generatePayrollReport(Collection $payrolls, string $period): \Barryvdh\DomPDF\PDF
    {
        $totals = [
            'gross_pay' => $payrolls->sum('gross_pay'),
            'net_pay' => $payrolls->sum('net_pay'),
            'nis_employee' => $payrolls->sum('nis_employee'),
            'nis_employer' => $payrolls->sum('nis_employer'),
            'nht_employee' => $payrolls->sum('nht_employee'),
            'nht_employer' => $payrolls->sum('nht_employer'),
            'education_tax' => $payrolls->sum('education_tax'),
            'paye' => $payrolls->sum('paye'),
        ];

        return Pdf::loadView('pdf.payroll-report', [
            'payrolls' => $payrolls,
            'period' => $period,
            'totals' => $totals,
            'generated_at' => now(),
        ])->setPaper('a4', 'landscape');
    }

    /**
     * Generate leave report
     */
    public function generateLeaveReport(Collection $leaveRequests, string $period): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('pdf.leave-report', [
            'leaveRequests' => $leaveRequests,
            'period' => $period,
            'generated_at' => now(),
        ])->setPaper('a4', 'portrait');
    }

    /**
     * Generate compliance SO2 form
     */
    public function generateSO2Form(Employee $employee, int $year): \Barryvdh\DomPDF\PDF
    {
        $payrolls = Payroll::where('employee_id', $employee->id)
            ->whereYear('period_start', $year)
            ->get();

        $totals = [
            'gross_emoluments' => $payrolls->sum('gross_pay'),
            'nis' => $payrolls->sum('nis_employee'),
            'nht' => $payrolls->sum('nht_employee'),
            'education_tax' => $payrolls->sum('education_tax'),
            'paye' => $payrolls->sum('paye'),
        ];

        return Pdf::loadView('pdf.so2-form', [
            'employee' => $employee,
            'year' => $year,
            'totals' => $totals,
            'company' => [
                'name' => config('app.company_name', 'JamHR Company'),
                'trn' => config('app.company_trn', ''),
                'address' => config('app.company_address', ''),
            ],
        ])->setPaper('a4', 'portrait');
    }

    /**
     * Generate attendance report
     */
    public function generateAttendanceReport(Collection $timeEntries, string $period): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('pdf.attendance-report', [
            'timeEntries' => $timeEntries,
            'period' => $period,
            'generated_at' => now(),
        ])->setPaper('a4', 'landscape');
    }
}
