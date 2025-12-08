<?php

namespace App\Http\Controllers;

use App\Models\Termination;
use App\Models\Employee;
use Illuminate\Http\Request;

class TerminationController extends Controller
{
    public function index(Request $request)
    {
        $query = Termination::with(['employee', 'processor']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $terminations = $query->latest()->paginate(10);

        $stats = [
            'total' => Termination::count(),
            'pending' => Termination::where('status', 'Pending')->count(),
            'in_progress' => Termination::where('status', 'In Progress')->count(),
            'completed_this_month' => Termination::where('status', 'Completed')
                ->whereMonth('last_working_day', now()->month)
                ->count(),
            'resignations' => Termination::where('type', 'Resignation')->count(),
            'terminations' => Termination::where('type', 'Termination')->count(),
        ];

        return view('terminations.index', compact('terminations', 'stats'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'Active')
            ->orderBy('first_name')
            ->get();

        return view('terminations.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:Resignation,Termination,Redundancy,End of Contract,Retirement,Mutual Agreement,Dismissal,Death',
            'notice_date' => 'required|date',
            'last_working_day' => 'required|date|after_or_equal:notice_date',
            'reason' => 'nullable|string',
            'eligible_for_rehire' => 'boolean',
            'rehire_notes' => 'nullable|string',
        ]);

        $validated['processed_by'] = auth()->id();
        $validated['status'] = 'Pending';

        $termination = Termination::create($validated);

        // Update employee status
        Employee::find($validated['employee_id'])->update(['status' => 'Notice Period']);

        return redirect()->route('terminations.show', $termination)
            ->with('success', 'Termination record created. Please complete the offboarding checklist.');
    }

    public function show(Termination $termination)
    {
        $termination->load(['employee', 'processor']);

        return view('terminations.show', compact('termination'));
    }

    public function edit(Termination $termination)
    {
        $termination->load('employee');

        return view('terminations.edit', compact('termination'));
    }

    public function update(Request $request, Termination $termination)
    {
        $validated = $request->validate([
            'type' => 'required|in:Resignation,Termination,Redundancy,End of Contract,Retirement,Mutual Agreement,Dismissal,Death',
            'notice_date' => 'required|date',
            'last_working_day' => 'required|date|after_or_equal:notice_date',
            'reason' => 'nullable|string',
            'exit_interview_notes' => 'nullable|string',
            'exit_interview_completed' => 'boolean',
            'exit_interview_date' => 'nullable|date',
            'company_property_returned' => 'boolean',
            'access_revoked' => 'boolean',
            'final_pay_processed' => 'boolean',
            'benefits_terminated' => 'boolean',
            'knowledge_transfer_complete' => 'boolean',
            'final_salary' => 'nullable|numeric|min:0',
            'unused_leave_payout' => 'nullable|numeric|min:0',
            'severance_pay' => 'nullable|numeric|min:0',
            'other_payments' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'nht_clearance' => 'boolean',
            'nis_updated' => 'boolean',
            'tax_forms_issued' => 'boolean',
            'eligible_for_rehire' => 'boolean',
            'rehire_notes' => 'nullable|string',
            'status' => 'required|in:Pending,In Progress,Completed,Cancelled',
        ]);

        // Calculate total final pay
        $validated['total_final_pay'] =
            ($validated['final_salary'] ?? 0) +
            ($validated['unused_leave_payout'] ?? 0) +
            ($validated['severance_pay'] ?? 0) +
            ($validated['other_payments'] ?? 0) -
            ($validated['deductions'] ?? 0);

        $termination->update($validated);

        // Update employee status based on termination status
        if ($validated['status'] === 'Completed') {
            $termination->employee->update(['status' => 'Terminated']);
        } elseif ($validated['status'] === 'Cancelled') {
            $termination->employee->update(['status' => 'Active']);
        } elseif ($validated['status'] === 'In Progress') {
            $termination->employee->update(['status' => 'Notice Period']);
        }

        return redirect()->route('terminations.show', $termination)
            ->with('success', 'Termination record updated successfully.');
    }

    public function updateChecklist(Request $request, Termination $termination)
    {
        $field = $request->input('field');
        $value = $request->input('value', true);

        $allowedFields = [
            'company_property_returned',
            'access_revoked',
            'final_pay_processed',
            'benefits_terminated',
            'knowledge_transfer_complete',
            'exit_interview_completed',
            'nht_clearance',
            'nis_updated',
            'tax_forms_issued',
        ];

        if (in_array($field, $allowedFields)) {
            $termination->update([$field => $value]);

            // Check if all items are complete
            $allComplete = $termination->company_property_returned &&
                $termination->access_revoked &&
                $termination->final_pay_processed &&
                $termination->benefits_terminated &&
                $termination->knowledge_transfer_complete &&
                $termination->exit_interview_completed &&
                $termination->nht_clearance &&
                $termination->nis_updated &&
                $termination->tax_forms_issued;

            if ($allComplete && $termination->status !== 'Completed') {
                $termination->update(['status' => 'Completed']);
                $termination->employee->update(['status' => 'Terminated']);
            }
        }

        return back()->with('success', 'Checklist updated.');
    }

    public function exitInterview(Termination $termination)
    {
        return view('terminations.exit-interview', compact('termination'));
    }

    public function storeExitInterview(Request $request, Termination $termination)
    {
        $validated = $request->validate([
            'exit_interview_notes' => 'required|string',
            'exit_interview_date' => 'required|date',
        ]);

        $validated['exit_interview_completed'] = true;

        $termination->update($validated);

        return redirect()->route('terminations.show', $termination)
            ->with('success', 'Exit interview recorded successfully.');
    }

    public function calculateFinalPay(Termination $termination)
    {
        $employee = $termination->employee;

        // Get unused leave days
        $unusedLeaveDays = $employee->leave_balance ?? 0;

        // Calculate daily rate
        $monthlyRate = $employee->salary ?? 0;
        $dailyRate = $monthlyRate / 22; // Assuming 22 working days

        // Calculate unused leave payout
        $unusedLeavePayout = $unusedLeaveDays * $dailyRate;

        // Calculate severance if applicable (Jamaican law)
        // Severance: 2 weeks pay for each year of service after 2 years
        $yearsOfService = $employee->hire_date
            ? $employee->hire_date->diffInYears($termination->last_working_day)
            : 0;

        $severancePay = 0;
        if (in_array($termination->type, ['Redundancy', 'Termination']) && $yearsOfService >= 2) {
            $weeklyRate = $monthlyRate / 4;
            $severancePay = $weeklyRate * 2 * ($yearsOfService - 2 + 1);
        }

        // Pro-rated salary
        $daysWorkedThisMonth = $termination->last_working_day->day;
        $finalSalary = ($monthlyRate / 30) * $daysWorkedThisMonth;

        return response()->json([
            'final_salary' => round($finalSalary, 2),
            'unused_leave_payout' => round($unusedLeavePayout, 2),
            'severance_pay' => round($severancePay, 2),
            'years_of_service' => $yearsOfService,
            'unused_leave_days' => $unusedLeaveDays,
        ]);
    }

    public function destroy(Termination $termination)
    {
        // Restore employee status if needed
        if ($termination->employee->status !== 'Active') {
            $termination->employee->update(['status' => 'Active']);
        }

        $termination->delete();

        return redirect()->route('terminations.index')
            ->with('success', 'Termination record deleted.');
    }
}
