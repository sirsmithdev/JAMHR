<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::with(['employee', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get leave balance for current user if they're an employee
        $currentEmployee = Auth::user()->employee;
        $vacationBalance = $currentEmployee ? $currentEmployee->vacation_days_remaining : 0;
        $sickBalance = $currentEmployee ? $currentEmployee->sick_days_remaining : 0;
        $pendingRequests = LeaveRequest::where('status', 'pending')->count();

        return view('leave.index', compact(
            'leaveRequests',
            'vacationBalance',
            'sickBalance',
            'pendingRequests'
        ));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('leave.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:vacation,sick,personal,maternity,unpaid',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        // Calculate days count
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $daysCount = $startDate->diffInWeekdays($endDate) + 1;

        LeaveRequest::create([
            'employee_id' => $validated['employee_id'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_count' => $daysCount,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('leave.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        // Update employee leave balance
        $employee = $leaveRequest->employee;
        if ($leaveRequest->type === 'vacation') {
            $employee->vacation_days_used += $leaveRequest->days_count;
            $employee->save();
        } elseif ($leaveRequest->type === 'sick') {
            $employee->sick_days_used += $leaveRequest->days_count;
            $employee->save();
        }

        return redirect()->route('leave.index')
            ->with('success', 'Leave request approved.');
    }

    public function reject(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('leave.index')
            ->with('success', 'Leave request rejected.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('leave.index')
                ->with('error', 'Only pending requests can be deleted.');
        }

        $leaveRequest->delete();
        return redirect()->route('leave.index')
            ->with('success', 'Leave request deleted.');
    }
}
