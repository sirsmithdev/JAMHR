<?php

namespace App\Http\Controllers;

use App\Models\DisciplinaryAction;
use App\Models\Employee;
use Illuminate\Http\Request;

class DisciplinaryController extends Controller
{
    public function index(Request $request)
    {
        $query = DisciplinaryAction::with(['employee', 'issuer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $disciplinaryActions = $query->latest('action_date')->paginate(10);

        $stats = [
            'total' => DisciplinaryAction::count(),
            'open' => DisciplinaryAction::whereIn('status', ['Open', 'Under Review'])->count(),
            'this_month' => DisciplinaryAction::whereMonth('action_date', now()->month)->count(),
            'warnings' => DisciplinaryAction::whereIn('type', ['Verbal Warning', 'Written Warning', 'Final Written Warning'])->count(),
            'pips_active' => DisciplinaryAction::where('type', 'Performance Improvement Plan')
                ->where('pip_outcome', 'Pending')->count(),
            'suspensions' => DisciplinaryAction::where('type', 'Suspension')
                ->whereIn('status', ['Open', 'Under Review'])->count(),
        ];

        $employees = Employee::where('status', 'Active')->orderBy('first_name')->get();

        return view('disciplinary.index', compact('disciplinaryActions', 'stats', 'employees'));
    }

    public function create(Employee $employee = null)
    {
        $employees = Employee::where('status', 'Active')
            ->orderBy('first_name')
            ->get();

        return view('disciplinary.create', compact('employees', 'employee'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'incident_date' => 'required|date|before_or_equal:today',
            'action_date' => 'required|date',
            'type' => 'required|in:Verbal Warning,Written Warning,Final Written Warning,Suspension,Demotion,Termination,Performance Improvement Plan',
            'category' => 'required|in:Attendance,Performance,Conduct,Policy Violation,Insubordination,Harassment,Safety Violation,Theft,Substance Abuse,Other',
            'description' => 'required|string',
            'evidence' => 'nullable|string',
            'corrective_action' => 'nullable|string',
            'follow_up_date' => 'nullable|date|after:today',
            'witnesses' => 'nullable|string',
            'union_representative_present' => 'boolean',
            'union_representative_name' => 'nullable|string|max:255',
            // Suspension fields
            'suspension_start' => 'nullable|required_if:type,Suspension|date',
            'suspension_end' => 'nullable|required_if:type,Suspension|date|after_or_equal:suspension_start',
            'with_pay' => 'nullable|boolean',
            // PIP fields
            'pip_start_date' => 'nullable|required_if:type,Performance Improvement Plan|date',
            'pip_end_date' => 'nullable|required_if:type,Performance Improvement Plan|date|after:pip_start_date',
            'pip_goals' => 'nullable|required_if:type,Performance Improvement Plan|string',
        ]);

        $validated['issued_by'] = auth()->id();
        $validated['status'] = 'Open';

        if ($validated['type'] === 'Performance Improvement Plan') {
            $validated['pip_outcome'] = 'Pending';
        }

        $disciplinaryAction = DisciplinaryAction::create($validated);

        // If termination, create termination record
        if ($validated['type'] === 'Termination') {
            return redirect()->route('terminations.create', ['employee_id' => $validated['employee_id']])
                ->with('info', 'Disciplinary action recorded. Please complete the termination process.');
        }

        return redirect()->route('disciplinary.show', $disciplinaryAction)
            ->with('success', 'Disciplinary action recorded successfully.');
    }

    public function show(DisciplinaryAction $disciplinary)
    {
        $disciplinary->load(['employee', 'issuer', 'approver']);

        // Get employee's disciplinary history
        $history = DisciplinaryAction::where('employee_id', $disciplinary->employee_id)
            ->where('id', '!=', $disciplinary->id)
            ->orderBy('action_date', 'desc')
            ->take(5)
            ->get();

        return view('disciplinary.show', compact('disciplinary', 'history'));
    }

    public function edit(DisciplinaryAction $disciplinary)
    {
        $disciplinary->load('employee');
        $employees = Employee::orderBy('first_name')->get();

        return view('disciplinary.edit', compact('disciplinary', 'employees'));
    }

    public function update(Request $request, DisciplinaryAction $disciplinary)
    {
        $validated = $request->validate([
            'incident_date' => 'required|date',
            'action_date' => 'required|date',
            'type' => 'required|in:Verbal Warning,Written Warning,Final Written Warning,Suspension,Demotion,Termination,Performance Improvement Plan',
            'category' => 'required|in:Attendance,Performance,Conduct,Policy Violation,Insubordination,Harassment,Safety Violation,Theft,Substance Abuse,Other',
            'description' => 'required|string',
            'evidence' => 'nullable|string',
            'employee_response' => 'nullable|string',
            'corrective_action' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'follow_up_notes' => 'nullable|string',
            'witnesses' => 'nullable|string',
            'union_representative_present' => 'boolean',
            'union_representative_name' => 'nullable|string|max:255',
            'suspension_start' => 'nullable|date',
            'suspension_end' => 'nullable|date|after_or_equal:suspension_start',
            'with_pay' => 'nullable|boolean',
            'pip_start_date' => 'nullable|date',
            'pip_end_date' => 'nullable|date|after:pip_start_date',
            'pip_goals' => 'nullable|string',
            'pip_outcome' => 'nullable|in:Pending,Successful,Failed,Extended',
            'status' => 'required|in:Open,Under Review,Resolved,Appealed,Overturned',
            'appeal_notes' => 'nullable|string',
        ]);

        $disciplinary->update($validated);

        return redirect()->route('disciplinary.show', $disciplinary)
            ->with('success', 'Disciplinary action updated successfully.');
    }

    public function acknowledge(Request $request, DisciplinaryAction $disciplinary)
    {
        $disciplinary->update([
            'employee_acknowledged' => true,
            'acknowledged_at' => now(),
        ]);

        return back()->with('success', 'Employee acknowledgment recorded.');
    }

    public function addResponse(Request $request, DisciplinaryAction $disciplinary)
    {
        $validated = $request->validate([
            'employee_response' => 'required|string',
        ]);

        $disciplinary->update($validated);

        return back()->with('success', 'Employee response recorded.');
    }

    public function updatePipOutcome(Request $request, DisciplinaryAction $disciplinary)
    {
        $validated = $request->validate([
            'pip_outcome' => 'required|in:Pending,Successful,Failed,Extended',
            'follow_up_notes' => 'nullable|string',
            'pip_end_date' => 'nullable|date',
        ]);

        if ($validated['pip_outcome'] === 'Extended' && isset($validated['pip_end_date'])) {
            $disciplinary->pip_end_date = $validated['pip_end_date'];
        }

        if ($validated['pip_outcome'] === 'Successful') {
            $validated['status'] = 'Resolved';
        }

        $disciplinary->update($validated);

        // If PIP failed, suggest next steps
        if ($validated['pip_outcome'] === 'Failed') {
            return back()->with('warning', 'PIP marked as failed. Consider escalating to Final Written Warning or Termination.');
        }

        return back()->with('success', 'PIP outcome updated successfully.');
    }

    public function employeeHistory(Employee $employee)
    {
        $actions = DisciplinaryAction::where('employee_id', $employee->id)
            ->with('issuer')
            ->orderBy('action_date', 'desc')
            ->paginate(10);

        $summary = [
            'total' => $actions->total(),
            'verbal_warnings' => DisciplinaryAction::forEmployee($employee->id)->where('type', 'Verbal Warning')->count(),
            'written_warnings' => DisciplinaryAction::forEmployee($employee->id)->where('type', 'Written Warning')->count(),
            'final_warnings' => DisciplinaryAction::forEmployee($employee->id)->where('type', 'Final Written Warning')->count(),
            'suspensions' => DisciplinaryAction::forEmployee($employee->id)->where('type', 'Suspension')->count(),
            'pips' => DisciplinaryAction::forEmployee($employee->id)->where('type', 'Performance Improvement Plan')->count(),
        ];

        return view('disciplinary.employee-history', compact('employee', 'actions', 'summary'));
    }

    public function destroy(DisciplinaryAction $disciplinary)
    {
        $disciplinary->delete();

        return redirect()->route('disciplinary.index')
            ->with('success', 'Disciplinary action deleted.');
    }
}
