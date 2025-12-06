<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : now();

        $timeEntries = TimeEntry::with('employee')
            ->whereDate('date', $date)
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate summary stats
        $totalEmployees = Employee::count();
        $presentToday = TimeEntry::whereDate('date', $date)
            ->whereNotNull('clock_in')
            ->distinct('employee_id')
            ->count('employee_id');
        $lateArrivals = TimeEntry::whereDate('date', $date)
            ->where('status', 'late')
            ->count();
        $overtimeHours = TimeEntry::whereDate('date', $date)
            ->where('status', 'overtime')
            ->sum('total_hours');

        return view('time.index', compact(
            'timeEntries',
            'date',
            'totalEmployees',
            'presentToday',
            'lateArrivals',
            'overtimeHours'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:on_time,late,absent,overtime',
        ]);

        $timeEntry = TimeEntry::create([
            'employee_id' => $validated['employee_id'],
            'date' => $validated['date'],
            'clock_in' => $validated['clock_in'] ? Carbon::parse($validated['date'] . ' ' . $validated['clock_in']) : null,
            'clock_out' => $validated['clock_out'] ? Carbon::parse($validated['date'] . ' ' . $validated['clock_out']) : null,
            'status' => $validated['status'],
        ]);

        // Calculate total hours if both times are present
        if ($timeEntry->clock_in && $timeEntry->clock_out) {
            $timeEntry->total_hours = $timeEntry->calculateTotalHours();
            $timeEntry->save();
        }

        return redirect()->route('time.index')
            ->with('success', 'Time entry recorded successfully.');
    }

    public function update(Request $request, TimeEntry $timeEntry)
    {
        $validated = $request->validate([
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:on_time,late,absent,overtime',
        ]);

        $timeEntry->update([
            'clock_in' => $validated['clock_in'] ? Carbon::parse($timeEntry->date->format('Y-m-d') . ' ' . $validated['clock_in']) : null,
            'clock_out' => $validated['clock_out'] ? Carbon::parse($timeEntry->date->format('Y-m-d') . ' ' . $validated['clock_out']) : null,
            'status' => $validated['status'],
        ]);

        // Recalculate total hours
        if ($timeEntry->clock_in && $timeEntry->clock_out) {
            $timeEntry->total_hours = $timeEntry->calculateTotalHours();
            $timeEntry->save();
        }

        return redirect()->route('time.index')
            ->with('success', 'Time entry updated successfully.');
    }

    public function destroy(TimeEntry $timeEntry)
    {
        $timeEntry->delete();
        return redirect()->route('time.index')
            ->with('success', 'Time entry deleted successfully.');
    }
}
