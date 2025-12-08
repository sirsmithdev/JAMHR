<?php

namespace App\Http\Controllers;

use App\Models\StaffLoan;
use App\Models\LoanType;
use App\Models\LoanRepayment;
use App\Models\Employee;
use Illuminate\Http\Request;

class StaffLoansController extends Controller
{
    /**
     * Loans dashboard
     */
    public function index(Request $request)
    {
        $query = StaffLoan::with(['employee', 'loanType']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('loan_type_id')) {
            $query->where('loan_type_id', $request->loan_type_id);
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total_active' => StaffLoan::active()->count(),
            'total_outstanding' => StaffLoan::active()->sum('outstanding_balance'),
            'pending_applications' => StaffLoan::pending()->count(),
            'monthly_collections' => LoanRepayment::whereMonth('due_date', now()->month)
                ->whereYear('due_date', now()->year)
                ->sum('scheduled_amount'),
        ];

        $loanTypes = LoanType::active()->get();

        return view('loans.index', compact('loans', 'stats', 'loanTypes'));
    }

    /**
     * Loan types management
     */
    public function types()
    {
        $types = LoanType::withCount(['loans', 'activeLoans'])->get();

        return view('loans.types.index', compact('types'));
    }

    /**
     * Create loan type form
     */
    public function createType()
    {
        return view('loans.types.create');
    }

    /**
     * Store loan type
     */
    public function storeType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:loan_types',
            'description' => 'nullable|string',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'market_rate' => 'required|numeric|min:0|max:100',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'min_term_months' => 'required|integer|min:1',
            'max_term_months' => 'required|integer|min:1',
            'min_employment_months' => 'required|integer|min:0',
            'requires_guarantor' => 'boolean',
        ]);

        LoanType::create($validated);

        return redirect()->route('loans.types')
            ->with('success', 'Loan type created successfully.');
    }

    /**
     * New loan application form
     */
    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        $loanTypes = LoanType::active()->get();

        return view('loans.create', compact('employees', 'loanTypes'));
    }

    /**
     * Store loan application
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'loan_type_id' => 'required|exists:loan_types,id',
            'principal_amount' => 'required|numeric|min:0',
            'term_months' => 'required|integer|min:1',
            'purpose' => 'nullable|string',
            'guarantor_id' => 'nullable|exists:employees,id',
        ]);

        $loanType = LoanType::findOrFail($validated['loan_type_id']);
        $employee = Employee::findOrFail($validated['employee_id']);

        // Validate eligibility
        $employmentMonths = $employee->hire_date->diffInMonths(now());
        if ($employmentMonths < $loanType->min_employment_months) {
            return back()->with('error', "Employee must have at least {$loanType->min_employment_months} months of employment.");
        }

        if ($loanType->max_amount && $validated['principal_amount'] > $loanType->max_amount) {
            return back()->with('error', "Loan amount exceeds maximum of JMD " . number_format($loanType->max_amount, 2));
        }

        if ($validated['term_months'] > $loanType->max_term_months) {
            return back()->with('error', "Loan term exceeds maximum of {$loanType->max_term_months} months.");
        }

        // Calculate loan details
        $monthlyPayment = $loanType->calculateMonthlyPayment($validated['principal_amount'], $validated['term_months']);
        $totalInterest = $loanType->calculateTotalInterest($validated['principal_amount'], $validated['term_months']);

        $loan = StaffLoan::create([
            'employee_id' => $validated['employee_id'],
            'loan_type_id' => $validated['loan_type_id'],
            'principal_amount' => $validated['principal_amount'],
            'interest_rate' => $loanType->interest_rate,
            'market_rate' => $loanType->market_rate,
            'term_months' => $validated['term_months'],
            'monthly_payment' => $monthlyPayment,
            'total_interest' => $totalInterest,
            'total_repayment' => $validated['principal_amount'] + $totalInterest,
            'outstanding_balance' => $validated['principal_amount'] + $totalInterest,
            'application_date' => now(),
            'status' => 'pending',
            'purpose' => $validated['purpose'],
            'guarantor_id' => $validated['guarantor_id'],
        ]);

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Loan application submitted successfully.');
    }

    /**
     * Show loan details
     */
    public function show(StaffLoan $loan)
    {
        $loan->load(['employee', 'loanType', 'repayments', 'guarantor', 'approver', 'documents']);

        return view('loans.show', compact('loan'));
    }

    /**
     * Approve loan
     */
    public function approve(Request $request, StaffLoan $loan)
    {
        $validated = $request->validate([
            'disbursement_date' => 'required|date',
            'first_payment_date' => 'required|date|after:disbursement_date',
        ]);

        $loan->update([
            'status' => 'approved',
            'approval_date' => now(),
            'approved_by' => auth()->id(),
            'disbursement_date' => $validated['disbursement_date'],
            'first_payment_date' => $validated['first_payment_date'],
            'maturity_date' => \Carbon\Carbon::parse($validated['first_payment_date'])->addMonths($loan->term_months - 1),
        ]);

        return back()->with('success', 'Loan approved successfully.');
    }

    /**
     * Reject loan
     */
    public function reject(Request $request, StaffLoan $loan)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $loan->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Loan application rejected.');
    }

    /**
     * Disburse loan (activate and generate schedule)
     */
    public function disburse(StaffLoan $loan)
    {
        if ($loan->status !== 'approved') {
            return back()->with('error', 'Loan must be approved before disbursement.');
        }

        // Generate amortization schedule
        $schedule = $loan->generateAmortizationSchedule();

        foreach ($schedule as $payment) {
            LoanRepayment::create([
                'staff_loan_id' => $loan->id,
                'payment_number' => $payment['payment_number'],
                'due_date' => $payment['due_date'],
                'scheduled_amount' => $payment['scheduled_amount'],
                'principal_amount' => $payment['principal_amount'],
                'interest_amount' => $payment['interest_amount'],
                'balance_after' => $payment['balance_after'],
                'status' => 'scheduled',
            ]);
        }

        $loan->update([
            'status' => 'active',
            'taxable_benefit' => $loan->loanType->calculateMonthlyTaxableBenefit($loan->outstanding_balance),
        ]);

        return back()->with('success', 'Loan disbursed and payment schedule generated.');
    }

    /**
     * Record repayment
     */
    public function recordPayment(Request $request, LoanRepayment $repayment)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:payroll_deduction,cash,bank_transfer,cheque',
            'notes' => 'nullable|string',
        ]);

        $repayment->update([
            'amount_paid' => $validated['amount_paid'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
            'status' => $validated['amount_paid'] >= $repayment->scheduled_amount ? 'paid' : 'partial',
        ]);

        // Update loan outstanding balance
        $loan = $repayment->staffLoan;
        $loan->outstanding_balance = $loan->repayments()
            ->whereIn('status', ['scheduled', 'partial', 'overdue'])
            ->sum('scheduled_amount') - $loan->repayments()
            ->whereIn('status', ['scheduled', 'partial', 'overdue', 'paid'])
            ->sum('amount_paid');

        // Update taxable benefit based on new balance
        $loan->updateTaxableBenefit();

        // Check if loan is fully paid
        if ($loan->outstanding_balance <= 0) {
            $loan->update(['status' => 'paid_off']);
        }

        return back()->with('success', 'Payment recorded successfully.');
    }

    /**
     * Loan calculator
     */
    public function calculator()
    {
        $loanTypes = LoanType::active()->get();

        return view('loans.calculator', compact('loanTypes'));
    }

    /**
     * Calculate loan via AJAX
     */
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'loan_type_id' => 'required|exists:loan_types,id',
            'principal_amount' => 'required|numeric|min:0',
            'term_months' => 'required|integer|min:1',
        ]);

        $loanType = LoanType::findOrFail($validated['loan_type_id']);

        $monthlyPayment = $loanType->calculateMonthlyPayment($validated['principal_amount'], $validated['term_months']);
        $totalInterest = $loanType->calculateTotalInterest($validated['principal_amount'], $validated['term_months']);
        $totalRepayment = $validated['principal_amount'] + $totalInterest;
        $monthlyTaxableBenefit = $loanType->calculateMonthlyTaxableBenefit($validated['principal_amount']);

        return response()->json([
            'monthly_payment' => round($monthlyPayment, 2),
            'total_interest' => round($totalInterest, 2),
            'total_repayment' => round($totalRepayment, 2),
            'monthly_taxable_benefit' => round($monthlyTaxableBenefit, 2),
            'annual_taxable_benefit' => round($monthlyTaxableBenefit * 12, 2),
            'interest_rate' => $loanType->interest_rate,
            'market_rate' => $loanType->market_rate,
            'taxable_benefit_rate' => $loanType->taxable_benefit_rate,
        ]);
    }

    /**
     * Employee loan history
     */
    public function employeeLoans(Employee $employee)
    {
        $loans = StaffLoan::with(['loanType'])
            ->where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $activeLoans = $loans->where('status', 'active');
        $totalOutstanding = $activeLoans->sum('outstanding_balance');
        $monthlyDeductions = $activeLoans->sum('monthly_payment');

        return view('loans.employee', compact('employee', 'loans', 'totalOutstanding', 'monthlyDeductions'));
    }
}
