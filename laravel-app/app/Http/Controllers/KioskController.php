<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KioskController extends Controller
{
    public function index()
    {
        return view('kiosk.index');
    }

    public function clockIn(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:4',
        ]);

        $employee = Employee::where('pin', $validated['pin'])->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN. Please try again.',
            ], 401);
        }

        $today = now()->toDateString();

        // Check if already clocked in today
        $existingEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingEntry && $existingEntry->clock_in) {
            return response()->json([
                'success' => false,
                'message' => 'Already clocked in today.',
            ], 400);
        }

        // Create or update time entry
        $now = now();
        $status = $now->hour >= 9 ? 'late' : 'on_time'; // Consider late after 9 AM

        if ($existingEntry) {
            $existingEntry->update([
                'clock_in' => $now,
                'status' => $status,
            ]);
        } else {
            TimeEntry::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'clock_in' => $now,
                'status' => $status,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Clock In Successful for {$employee->full_name} at " . $now->format('h:i A'),
            'employee' => $employee->full_name,
            'time' => $now->format('h:i A'),
        ]);
    }

    public function clockOut(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:4',
        ]);

        $employee = Employee::where('pin', $validated['pin'])->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN. Please try again.',
            ], 401);
        }

        $today = now()->toDateString();

        $timeEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->first();

        if (!$timeEntry) {
            return response()->json([
                'success' => false,
                'message' => 'No active clock-in found for today.',
            ], 400);
        }

        $now = now();
        $timeEntry->update([
            'clock_out' => $now,
        ]);

        // Calculate total hours
        $timeEntry->total_hours = $timeEntry->calculateTotalHours();

        // Check for overtime (over 8 hours)
        if ($timeEntry->total_hours > 8) {
            $timeEntry->status = 'overtime';
        }

        $timeEntry->save();

        return response()->json([
            'success' => true,
            'message' => "Clock Out Successful for {$employee->full_name} at " . $now->format('h:i A'),
            'employee' => $employee->full_name,
            'time' => $now->format('h:i A'),
            'total_hours' => $timeEntry->total_hours,
        ]);
    }

    public function startBreak(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:4',
        ]);

        $employee = Employee::where('pin', $validated['pin'])->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN. Please try again.',
            ], 401);
        }

        $today = now()->toDateString();

        $timeEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->first();

        if (!$timeEntry) {
            return response()->json([
                'success' => false,
                'message' => 'Must be clocked in to start a break.',
            ], 400);
        }

        $now = now();
        $timeEntry->update(['break_start' => $now]);

        return response()->json([
            'success' => true,
            'message' => "Break Started for {$employee->full_name} at " . $now->format('h:i A'),
            'employee' => $employee->full_name,
            'time' => $now->format('h:i A'),
        ]);
    }

    public function endBreak(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:4',
        ]);

        $employee = Employee::where('pin', $validated['pin'])->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN. Please try again.',
            ], 401);
        }

        $today = now()->toDateString();

        $timeEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->first();

        if (!$timeEntry) {
            return response()->json([
                'success' => false,
                'message' => 'No active break found.',
            ], 400);
        }

        $now = now();
        $timeEntry->update(['break_end' => $now]);

        return response()->json([
            'success' => true,
            'message' => "Break Ended for {$employee->full_name} at " . $now->format('h:i A'),
            'employee' => $employee->full_name,
            'time' => $now->format('h:i A'),
        ]);
    }
}
