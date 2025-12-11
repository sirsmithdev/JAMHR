<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollCalculator;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected PayrollCalculator $calculator;

    public function __construct(PayrollCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function index()
    {
        $payrolls = Payroll::with('employee')
            ->orderBy('period_end', 'desc')
            ->paginate(15);

        $taxRates = PayrollCalculator::getTaxRates();

        return view('payroll.index', compact('payrolls', 'taxRates'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        $payFrequencies = Employee::getPayFrequencies();
        $payTypes = Employee::getPayTypes();
        $taxRates = PayrollCalculator::getTaxRates();

        return view('payroll.create', compact('employees', 'payFrequencies', 'payTypes', 'taxRates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'hours_worked' => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'other_earnings' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        // Calculate year-to-date earnings for NIS cap
        $yearStart = now()->startOfYear();
        $annualEarningsToDate = Payroll::where('employee_id', $employee->id)
            ->where('period_end', '>=', $yearStart)
            ->sum('gross_pay');

        // Use the enhanced payroll creation method
        $payroll = $this->calculator->createPayroll(
            $employee,
            new \DateTime($validated['period_start']),
            new \DateTime($validated['period_end']),
            $validated['hours_worked'] ?? null,
            $validated['overtime_hours'] ?? null,
            $validated['allowances'] ?? 0,
            $validated['bonus'] ?? 0,
            $validated['commission'] ?? 0,
            $validated['other_earnings'] ?? 0,
            $validated['loan_deduction'] ?? 0,
            $validated['other_deductions'] ?? 0,
            $annualEarningsToDate
        );

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll record created successfully.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee');
        return view('payroll.show', compact('payroll'));
    }

    public function finalize(Payroll $payroll)
    {
        $payroll->update(['status' => 'finalized']);
        return redirect()->route('payroll.index')
            ->with('success', 'Payroll finalized successfully.');
    }

    public function markPaid(Payroll $payroll)
    {
        $payroll->update(['status' => 'paid']);
        return redirect()->route('payroll.index')
            ->with('success', 'Payroll marked as paid.');
    }

    public function calculator()
    {
        $employees = Employee::orderBy('first_name')->get();
        $taxRates = PayrollCalculator::getTaxRates();
        return view('payroll.calculator', compact('employees', 'taxRates'));
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'gross_pay' => 'nullable|numeric|min:0',
            'hours_worked' => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
        ]);

        if (isset($validated['employee_id'])) {
            $employee = Employee::findOrFail($validated['employee_id']);

            // Calculate gross based on employee's pay type
            $grossBreakdown = $this->calculator->calculateGrossPay(
                $employee,
                $validated['hours_worked'] ?? null,
                $validated['overtime_hours'] ?? null
            );

            // Calculate full payroll
            $yearStart = now()->startOfYear();
            $annualEarningsToDate = Payroll::where('employee_id', $employee->id)
                ->where('period_end', '>=', $yearStart)
                ->sum('gross_pay');

            $calculation = $this->calculator->calculate(
                $employee,
                $grossBreakdown['gross_pay'],
                $annualEarningsToDate
            );

            return response()->json(array_merge($grossBreakdown, $calculation, [
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->full_name,
                    'pay_type' => $employee->pay_type,
                    'pay_type_label' => $employee->pay_type_label,
                    'pay_frequency' => $employee->pay_frequency,
                    'pay_frequency_label' => $employee->pay_frequency_label,
                    'hourly_rate' => $employee->effective_hourly_rate,
                    'standard_hours' => $employee->getDefaultStandardHours(),
                ],
            ]));
        }

        // Legacy: simple calculation without employee
        $employee = new Employee();
        $calculation = $this->calculator->calculate($employee, $validated['gross_pay'] ?? 0, 0);

        return response()->json($calculation);
    }

    /**
     * Get employee payroll details for AJAX
     */
    public function getEmployeeDetails(Employee $employee)
    {
        return response()->json([
            'id' => $employee->id,
            'name' => $employee->full_name,
            'pay_type' => $employee->pay_type,
            'pay_type_label' => $employee->pay_type_label,
            'pay_frequency' => $employee->pay_frequency,
            'pay_frequency_label' => $employee->pay_frequency_label,
            'hourly_rate' => $employee->effective_hourly_rate,
            'standard_hours' => $employee->getDefaultStandardHours(),
            'monthly_salary' => $employee->monthly_salary,
            'fortnightly_salary' => $employee->fortnightly_salary,
            'is_hourly' => $employee->isHourlyPaid(),
        ]);
    }

    /**
     * Batch payroll creation for multiple employees
     */
    public function batchCreate()
    {
        $employees = Employee::orderBy('first_name')->get();
        $payFrequencies = Employee::getPayFrequencies();

        return view('payroll.batch', compact('employees', 'payFrequencies'));
    }

    /**
     * Store batch payroll records
     */
    public function batchStore(Request $request)
    {
        $validated = $request->validate([
            'pay_frequency' => 'required|in:fortnightly,monthly',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        $periodStart = new \DateTime($validated['period_start']);
        $periodEnd = new \DateTime($validated['period_end']);
        $createdCount = 0;

        foreach ($validated['employee_ids'] as $employeeId) {
            $employee = Employee::find($employeeId);

            // Skip if employee pay frequency doesn't match
            if ($employee->pay_frequency !== $validated['pay_frequency']) {
                continue;
            }

            // Get hours worked from time entries for hourly employees
            $hoursWorked = null;
            if ($employee->isHourlyPaid()) {
                $hoursWorked = $employee->getHoursWorkedInPeriod(
                    $validated['period_start'],
                    $validated['period_end']
                );
            }

            // Calculate year-to-date earnings
            $yearStart = now()->startOfYear();
            $annualEarningsToDate = Payroll::where('employee_id', $employee->id)
                ->where('period_end', '>=', $yearStart)
                ->sum('gross_pay');

            // Create payroll record
            $this->calculator->createPayroll(
                $employee,
                $periodStart,
                $periodEnd,
                $hoursWorked,
                null, // overtime calculated automatically
                0,    // allowances
                0,    // bonus
                0,    // commission
                0,    // other earnings
                0,    // loan deduction
                0,    // other deductions
                $annualEarningsToDate
            );

            $createdCount++;
        }

        return redirect()->route('payroll.index')
            ->with('success', "{$createdCount} payroll records created successfully.");
    }
}
