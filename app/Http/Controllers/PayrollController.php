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
        return view('payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'gross_pay' => 'required|numeric|min:0',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        // Calculate year-to-date earnings for NIS cap
        $yearStart = now()->startOfYear();
        $annualEarningsToDate = Payroll::where('employee_id', $employee->id)
            ->where('period_end', '>=', $yearStart)
            ->sum('gross_pay');

        // Calculate payroll using the service
        $calculation = $this->calculator->calculate(
            $employee,
            $validated['gross_pay'],
            $annualEarningsToDate
        );

        $payroll = Payroll::create([
            'employee_id' => $employee->id,
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'gross_pay' => $calculation['gross_pay'],
            'nht_employee' => $calculation['nht_employee'],
            'nht_employer' => $calculation['nht_employer'],
            'nis_employee' => $calculation['nis_employee'],
            'nis_employer' => $calculation['nis_employer'],
            'ed_tax_employee' => $calculation['ed_tax_employee'],
            'ed_tax_employer' => $calculation['ed_tax_employer'],
            'heart_employer' => $calculation['heart_employer'],
            'income_tax' => $calculation['income_tax'],
            'net_pay' => $calculation['net_pay'],
            'status' => 'draft',
        ]);

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
            'gross_pay' => 'required|numeric|min:0',
        ]);

        $employee = new Employee(); // Dummy employee for calculation
        $calculation = $this->calculator->calculate($employee, $validated['gross_pay'], 0);

        return response()->json($calculation);
    }
}
