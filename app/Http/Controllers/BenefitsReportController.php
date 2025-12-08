<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\BenefitEnrollment;
use App\Models\StaffLoan;
use App\Models\EmployeeAllowance;
use App\Models\NisContribution;
use App\Models\HealthClaim;
use App\Models\PensionAccount;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BenefitsReportController extends Controller
{
    /**
     * Reports dashboard
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Benefits cost analysis report
     */
    public function benefitsCost(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');

        $enrollments = BenefitEnrollment::with(['benefitPlan', 'employee'])
            ->where('status', 'active')
            ->get();

        $totalEmployeeCost = $enrollments->sum('employee_contribution');
        $totalEmployerCost = $enrollments->sum('employer_contribution');

        $costByType = $enrollments->groupBy('benefitPlan.type')->map(function ($group) {
            return [
                'employee_cost' => $group->sum('employee_contribution'),
                'employer_cost' => $group->sum('employer_contribution'),
                'total_cost' => $group->sum('total_contribution'),
                'enrollment_count' => $group->count(),
            ];
        });

        $costByDepartment = $enrollments->groupBy('employee.department')->map(function ($group) {
            return [
                'employee_cost' => $group->sum('employee_contribution'),
                'employer_cost' => $group->sum('employer_contribution'),
                'total_cost' => $group->sum('total_contribution'),
                'employee_count' => $group->pluck('employee_id')->unique()->count(),
            ];
        });

        return view('reports.benefits-cost', compact(
            'year', 'enrollments', 'totalEmployeeCost', 'totalEmployerCost',
            'costByType', 'costByDepartment'
        ));
    }

    /**
     * NIS/NHT contributions report (SO 2 Form data)
     */
    public function statutoryContributions(Request $request)
    {
        $year = $request->get('year', now()->year);

        $payrolls = Payroll::with('employee')
            ->whereYear('period_end', $year)
            ->where('status', 'paid')
            ->get();

        $summary = [
            'total_gross' => $payrolls->sum('gross_pay'),
            'nis_employee' => $payrolls->sum('nis_employee'),
            'nis_employer' => $payrolls->sum('nis_employer'),
            'nht_employee' => $payrolls->sum('nht_employee'),
            'nht_employer' => $payrolls->sum('nht_employer'),
            'ed_tax_employee' => $payrolls->sum('ed_tax_employee'),
            'ed_tax_employer' => $payrolls->sum('ed_tax_employer'),
            'heart_employer' => $payrolls->sum('heart_employer'),
            'income_tax' => $payrolls->sum('income_tax'),
        ];

        $employeeSummary = $payrolls->groupBy('employee_id')->map(function ($records) {
            return [
                'employee' => $records->first()->employee,
                'gross_pay' => $records->sum('gross_pay'),
                'nis_employee' => $records->sum('nis_employee'),
                'nht_employee' => $records->sum('nht_employee'),
                'ed_tax_employee' => $records->sum('ed_tax_employee'),
                'income_tax' => $records->sum('income_tax'),
                'pay_periods' => $records->count(),
            ];
        });

        $quarters = [];
        for ($q = 1; $q <= 4; $q++) {
            $startMonth = ($q - 1) * 3 + 1;
            $endMonth = $q * 3;

            $quarterPayrolls = $payrolls->filter(function ($p) use ($year, $startMonth, $endMonth) {
                $month = $p->period_end->month;
                return $month >= $startMonth && $month <= $endMonth;
            });

            $quarters[$q] = [
                'gross_pay' => $quarterPayrolls->sum('gross_pay'),
                'total_nis' => $quarterPayrolls->sum('nis_employee') + $quarterPayrolls->sum('nis_employer'),
                'total_nht' => $quarterPayrolls->sum('nht_employee') + $quarterPayrolls->sum('nht_employer'),
                'total_ed_tax' => $quarterPayrolls->sum('ed_tax_employee') + $quarterPayrolls->sum('ed_tax_employer'),
                'heart' => $quarterPayrolls->sum('heart_employer'),
                'paye' => $quarterPayrolls->sum('income_tax'),
            ];
        }

        return view('reports.statutory-contributions', compact('year', 'summary', 'employeeSummary', 'quarters'));
    }

    /**
     * Staff loans report
     */
    public function staffLoans(Request $request)
    {
        $status = $request->get('status', 'active');

        $loans = StaffLoan::with(['employee', 'loanType'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total_principal' => StaffLoan::active()->sum('principal_amount'),
            'total_outstanding' => StaffLoan::active()->sum('outstanding_balance'),
            'monthly_collections' => StaffLoan::active()->sum('monthly_payment'),
            'total_taxable_benefit' => StaffLoan::active()->sum('taxable_benefit'),
            'active_loans' => StaffLoan::active()->count(),
            'pending_applications' => StaffLoan::pending()->count(),
        ];

        $loansByType = StaffLoan::with('loanType')
            ->where('status', 'active')
            ->get()
            ->groupBy('loan_type_id')
            ->map(function ($group) {
                return [
                    'type' => $group->first()->loanType->name,
                    'count' => $group->count(),
                    'total_principal' => $group->sum('principal_amount'),
                    'total_outstanding' => $group->sum('outstanding_balance'),
                ];
            });

        return view('reports.staff-loans', compact('loans', 'summary', 'loansByType', 'status'));
    }

    /**
     * Allowances report
     */
    public function allowances(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        $allowances = EmployeeAllowance::with(['employee', 'allowanceType'])
            ->active()
            ->get();

        $summary = [
            'total_monthly' => $allowances->sum('monthly_amount'),
            'total_annual' => $allowances->sum('annual_amount'),
            'taxable_amount' => $allowances->sum('taxable_amount'),
            'employee_count' => $allowances->pluck('employee_id')->unique()->count(),
        ];

        $byCategory = $allowances->groupBy('allowanceType.category')->map(function ($group) {
            return [
                'count' => $group->count(),
                'monthly_total' => $group->sum('monthly_amount'),
                'taxable_total' => $group->sum('taxable_amount'),
            ];
        });

        $byDepartment = $allowances->groupBy('employee.department')->map(function ($group) {
            return [
                'employee_count' => $group->pluck('employee_id')->unique()->count(),
                'allowance_count' => $group->count(),
                'monthly_total' => $group->sum('monthly_amount'),
            ];
        });

        return view('reports.allowances', compact('allowances', 'summary', 'byCategory', 'byDepartment', 'month'));
    }

    /**
     * Health insurance utilization report
     */
    public function healthInsurance(Request $request)
    {
        $year = $request->get('year', now()->year);

        $claims = HealthClaim::with(['employee', 'enrollment.benefitPlan'])
            ->whereYear('service_date', $year)
            ->get();

        $summary = [
            'total_claimed' => $claims->sum('amount_claimed'),
            'total_approved' => $claims->sum('amount_approved'),
            'total_paid' => $claims->sum('amount_paid'),
            'claims_count' => $claims->count(),
            'pending_count' => $claims->where('status', 'submitted')->count() + $claims->where('status', 'under_review')->count(),
            'approval_rate' => $claims->count() > 0 ? ($claims->whereIn('status', ['approved', 'paid'])->count() / $claims->count()) * 100 : 0,
        ];

        $byType = $claims->groupBy('claim_type')->map(function ($group) {
            return [
                'count' => $group->count(),
                'claimed' => $group->sum('amount_claimed'),
                'approved' => $group->sum('amount_approved'),
                'paid' => $group->sum('amount_paid'),
            ];
        });

        $byMonth = $claims->groupBy(fn($c) => $c->service_date->format('Y-m'))->map(function ($group) {
            return [
                'count' => $group->count(),
                'claimed' => $group->sum('amount_claimed'),
                'paid' => $group->sum('amount_paid'),
            ];
        })->sortKeys();

        return view('reports.health-insurance', compact('year', 'claims', 'summary', 'byType', 'byMonth'));
    }

    /**
     * Pension contributions report
     */
    public function pension(Request $request)
    {
        $year = $request->get('year', now()->year);

        $accounts = PensionAccount::with(['employee', 'contributions' => function ($q) use ($year) {
            $q->whereYear('contribution_date', $year);
        }])->get();

        $summary = [
            'total_balance' => $accounts->sum('total_balance'),
            'total_vested' => $accounts->sum('vested_balance'),
            'ytd_employee' => $accounts->sum('employee_ytd_contributions'),
            'ytd_employer' => $accounts->sum('employer_ytd_contributions'),
            'account_count' => $accounts->count(),
            'fully_vested' => $accounts->where('vesting_percentage', '>=', 100)->count(),
        ];

        return view('reports.pension', compact('year', 'accounts', 'summary'));
    }

    /**
     * Leave utilization report
     */
    public function leaveUtilization(Request $request)
    {
        $year = $request->get('year', now()->year);

        $balances = LeaveBalance::with(['employee', 'leaveType'])
            ->where('year', $year)
            ->get();

        $summary = $balances->groupBy('leave_type_id')->map(function ($group) {
            return [
                'leave_type' => $group->first()->leaveType->name,
                'total_entitled' => $group->sum('entitled_days'),
                'total_used' => $group->sum('used_days'),
                'total_available' => $group->sum('available_days'),
                'utilization_rate' => $group->sum('entitled_days') > 0
                    ? ($group->sum('used_days') / $group->sum('entitled_days')) * 100
                    : 0,
            ];
        });

        $byDepartment = $balances->groupBy('employee.department')->map(function ($group) {
            return [
                'employee_count' => $group->pluck('employee_id')->unique()->count(),
                'total_used' => $group->sum('used_days'),
                'total_available' => $group->sum('available_days'),
            ];
        });

        return view('reports.leave-utilization', compact('year', 'balances', 'summary', 'byDepartment'));
    }

    /**
     * Employee benefits summary (individual)
     */
    public function employeeBenefits(Employee $employee)
    {
        $year = now()->year;

        $data = [
            'benefit_enrollments' => BenefitEnrollment::with('benefitPlan')
                ->where('employee_id', $employee->id)
                ->where('status', 'active')
                ->get(),

            'loans' => StaffLoan::with('loanType')
                ->where('employee_id', $employee->id)
                ->get(),

            'allowances' => EmployeeAllowance::with('allowanceType')
                ->where('employee_id', $employee->id)
                ->active()
                ->get(),

            'leave_balances' => LeaveBalance::with('leaveType')
                ->where('employee_id', $employee->id)
                ->where('year', $year)
                ->get(),

            'payroll_ytd' => Payroll::where('employee_id', $employee->id)
                ->whereYear('period_end', $year)
                ->where('status', 'paid')
                ->get(),
        ];

        $totals = [
            'monthly_benefit_cost' => $data['benefit_enrollments']->sum('employee_contribution'),
            'active_loan_balance' => $data['loans']->where('status', 'active')->sum('outstanding_balance'),
            'monthly_loan_payment' => $data['loans']->where('status', 'active')->sum('monthly_payment'),
            'monthly_allowances' => $data['allowances']->sum('monthly_amount'),
            'ytd_gross' => $data['payroll_ytd']->sum('gross_pay'),
            'ytd_nis' => $data['payroll_ytd']->sum('nis_employee'),
            'ytd_nht' => $data['payroll_ytd']->sum('nht_employee'),
            'ytd_paye' => $data['payroll_ytd']->sum('income_tax'),
        ];

        return view('reports.employee-benefits', compact('employee', 'data', 'totals', 'year'));
    }

    /**
     * Export statutory report (SO 2 data)
     */
    public function exportSO2(Request $request)
    {
        $year = $request->get('year', now()->year);

        $employees = Employee::with(['payrolls' => function ($q) use ($year) {
            $q->whereYear('period_end', $year)->where('status', 'paid');
        }])->whereHas('payrolls', function ($q) use ($year) {
            $q->whereYear('period_end', $year);
        })->get();

        $data = $employees->map(function ($employee) {
            $payrolls = $employee->payrolls;
            return [
                'employee_name' => $employee->full_name,
                'trn' => $employee->trn,
                'nis_number' => $employee->nis_number,
                'gross_emoluments' => $payrolls->sum('gross_pay'),
                'nis_employee' => $payrolls->sum('nis_employee'),
                'nht_employee' => $payrolls->sum('nht_employee'),
                'education_tax' => $payrolls->sum('ed_tax_employee'),
                'income_tax' => $payrolls->sum('income_tax'),
                'net_pay' => $payrolls->sum('net_pay'),
            ];
        });

        // Return as downloadable CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="SO2_Report_' . $year . '.csv"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Employee Name', 'TRN', 'NIS Number', 'Gross Emoluments', 'NIS', 'NHT', 'Education Tax', 'Income Tax', 'Net Pay']);

            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
