<?php

namespace App\Http\Controllers;

use App\Models\BenefitPlan;
use App\Models\BenefitEnrollment;
use App\Models\BenefitDependent;
use App\Models\EnrollmentPeriod;
use App\Models\Employee;
use Illuminate\Http\Request;

class BenefitsController extends Controller
{
    /**
     * Benefits dashboard overview
     */
    public function index()
    {
        $stats = [
            'total_plans' => BenefitPlan::active()->count(),
            'active_enrollments' => BenefitEnrollment::active()->count(),
            'pending_enrollments' => BenefitEnrollment::pending()->count(),
            'open_periods' => EnrollmentPeriod::open()->count(),
        ];

        $enrollmentsByType = BenefitEnrollment::active()
            ->join('benefit_plans', 'benefit_enrollments.benefit_plan_id', '=', 'benefit_plans.id')
            ->selectRaw('benefit_plans.type, count(*) as count')
            ->groupBy('benefit_plans.type')
            ->pluck('count', 'type');

        $recentEnrollments = BenefitEnrollment::with(['employee', 'benefitPlan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $openPeriods = EnrollmentPeriod::open()->get();

        return view('benefits.index', compact('stats', 'enrollmentsByType', 'recentEnrollments', 'openPeriods'));
    }

    /**
     * List all benefit plans
     */
    public function plans()
    {
        $plans = BenefitPlan::withCount(['enrollments', 'activeEnrollments'])
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(15);

        return view('benefits.plans.index', compact('plans'));
    }

    /**
     * Show create benefit plan form
     */
    public function createPlan()
    {
        return view('benefits.plans.create');
    }

    /**
     * Store a new benefit plan
     */
    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:health,pension,life_insurance,other',
            'provider' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'employee_contribution' => 'required|numeric|min:0',
            'employer_contribution' => 'required|numeric|min:0',
            'contribution_frequency' => 'required|in:monthly,bi-weekly,annual',
            'coverage_amount' => 'nullable|numeric|min:0',
            'effective_date' => 'required|date',
            'termination_date' => 'nullable|date|after:effective_date',
            'requires_enrollment' => 'boolean',
            'waiting_period_days' => 'nullable|integer|min:0',
        ]);

        $plan = BenefitPlan::create($validated);

        return redirect()->route('benefits.plans.show', $plan)
            ->with('success', 'Benefit plan created successfully.');
    }

    /**
     * Show a specific benefit plan
     */
    public function showPlan(BenefitPlan $plan)
    {
        $plan->load(['enrollments.employee', 'healthPlan', 'pensionPlan']);
        $enrollments = $plan->enrollments()->with('employee')->paginate(15);

        return view('benefits.plans.show', compact('plan', 'enrollments'));
    }

    /**
     * Edit benefit plan form
     */
    public function editPlan(BenefitPlan $plan)
    {
        return view('benefits.plans.edit', compact('plan'));
    }

    /**
     * Update a benefit plan
     */
    public function updatePlan(Request $request, BenefitPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:health,pension,life_insurance,other',
            'provider' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'employee_contribution' => 'required|numeric|min:0',
            'employer_contribution' => 'required|numeric|min:0',
            'contribution_frequency' => 'required|in:monthly,bi-weekly,annual',
            'coverage_amount' => 'nullable|numeric|min:0',
            'effective_date' => 'required|date',
            'termination_date' => 'nullable|date|after:effective_date',
            'is_active' => 'boolean',
            'requires_enrollment' => 'boolean',
            'waiting_period_days' => 'nullable|integer|min:0',
        ]);

        $plan->update($validated);

        return redirect()->route('benefits.plans.show', $plan)
            ->with('success', 'Benefit plan updated successfully.');
    }

    /**
     * List enrollment periods
     */
    public function periods()
    {
        $periods = EnrollmentPeriod::withCount('enrollments')
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        return view('benefits.periods.index', compact('periods'));
    }

    /**
     * Create enrollment period form
     */
    public function createPeriod()
    {
        return view('benefits.periods.create');
    }

    /**
     * Store new enrollment period
     */
    public function storePeriod(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:open,special,new_hire',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'effective_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $period = EnrollmentPeriod::create($validated);

        return redirect()->route('benefits.periods')
            ->with('success', 'Enrollment period created successfully.');
    }

    /**
     * Employee enrollment form
     */
    public function enroll(Employee $employee = null)
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        $plans = BenefitPlan::active()->get();
        $periods = EnrollmentPeriod::open()->get();

        return view('benefits.enroll', compact('employees', 'plans', 'periods', 'employee'));
    }

    /**
     * Process enrollment
     */
    public function processEnrollment(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'benefit_plan_id' => 'required|exists:benefit_plans,id',
            'enrollment_period_id' => 'nullable|exists:enrollment_periods,id',
            'coverage_level' => 'required|in:employee_only,employee_spouse,employee_children,family',
            'effective_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $plan = BenefitPlan::findOrFail($validated['benefit_plan_id']);

        // Check if already enrolled
        $existingEnrollment = BenefitEnrollment::where('employee_id', $validated['employee_id'])
            ->where('benefit_plan_id', $validated['benefit_plan_id'])
            ->where('status', 'active')
            ->first();

        if ($existingEnrollment) {
            return back()->with('error', 'Employee is already enrolled in this plan.');
        }

        $enrollment = BenefitEnrollment::create([
            'employee_id' => $validated['employee_id'],
            'benefit_plan_id' => $validated['benefit_plan_id'],
            'enrollment_period_id' => $validated['enrollment_period_id'],
            'status' => 'pending',
            'enrollment_date' => now(),
            'effective_date' => $validated['effective_date'],
            'employee_contribution' => $plan->employee_contribution,
            'employer_contribution' => $plan->employer_contribution,
            'coverage_level' => $validated['coverage_level'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('benefits.enrollments.show', $enrollment)
            ->with('success', 'Enrollment submitted successfully.');
    }

    /**
     * List all enrollments
     */
    public function enrollments(Request $request)
    {
        $query = BenefitEnrollment::with(['employee', 'benefitPlan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan_type')) {
            $query->whereHas('benefitPlan', function ($q) use ($request) {
                $q->where('type', $request->plan_type);
            });
        }

        $enrollments = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('benefits.enrollments.index', compact('enrollments'));
    }

    /**
     * Show enrollment details
     */
    public function showEnrollment(BenefitEnrollment $enrollment)
    {
        $enrollment->load(['employee', 'benefitPlan', 'dependents', 'approver']);

        return view('benefits.enrollments.show', compact('enrollment'));
    }

    /**
     * Approve enrollment
     */
    public function approveEnrollment(BenefitEnrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'active',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Enrollment approved successfully.');
    }

    /**
     * Cancel enrollment
     */
    public function cancelEnrollment(Request $request, BenefitEnrollment $enrollment)
    {
        $validated = $request->validate([
            'termination_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $enrollment->update([
            'status' => 'cancelled',
            'termination_date' => $validated['termination_date'],
            'notes' => $enrollment->notes . "\n\nCancellation: " . ($validated['notes'] ?? 'No reason provided'),
        ]);

        return back()->with('success', 'Enrollment cancelled.');
    }

    /**
     * Manage dependents
     */
    public function dependents(BenefitEnrollment $enrollment)
    {
        $enrollment->load(['employee', 'benefitPlan', 'dependents']);

        return view('benefits.dependents.index', compact('enrollment'));
    }

    /**
     * Add dependent form
     */
    public function addDependent(BenefitEnrollment $enrollment)
    {
        return view('benefits.dependents.create', compact('enrollment'));
    }

    /**
     * Store dependent
     */
    public function storeDependent(Request $request, BenefitEnrollment $enrollment)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'relationship' => 'required|in:spouse,child,domestic_partner,parent',
            'date_of_birth' => 'required|date',
            'gender' => 'nullable|in:male,female,other',
            'trn' => 'nullable|string|max:20',
            'is_student' => 'boolean',
            'is_disabled' => 'boolean',
        ]);

        $validated['employee_id'] = $enrollment->employee_id;
        $validated['benefit_enrollment_id'] = $enrollment->id;

        BenefitDependent::create($validated);

        return redirect()->route('benefits.dependents', $enrollment)
            ->with('success', 'Dependent added successfully.');
    }

    /**
     * Remove dependent
     */
    public function removeDependent(BenefitDependent $dependent)
    {
        $enrollment = $dependent->enrollment;
        $dependent->update(['is_active' => false]);

        return redirect()->route('benefits.dependents', $enrollment)
            ->with('success', 'Dependent removed.');
    }

    /**
     * Employee benefits summary
     */
    public function employeeSummary(Employee $employee)
    {
        $enrollments = BenefitEnrollment::with(['benefitPlan', 'dependents'])
            ->where('employee_id', $employee->id)
            ->where('status', 'active')
            ->get();

        $totalEmployeeContribution = $enrollments->sum('employee_contribution');
        $totalEmployerContribution = $enrollments->sum('employer_contribution');

        return view('benefits.employee-summary', compact('employee', 'enrollments', 'totalEmployeeContribution', 'totalEmployerContribution'));
    }
}
