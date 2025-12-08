<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SchedulingController extends Controller
{
    public function index(Request $request)
    {
        $weekStart = $request->week_start
            ? Carbon::parse($request->week_start)->startOfWeek()
            : now()->startOfWeek();

        $weekEnd = $weekStart->copy()->endOfWeek();

        $employees = Employee::orderBy('first_name')->get();

        $shifts = Shift::with('employee')
            ->whereBetween('date', [$weekStart, $weekEnd])
            ->get()
            ->groupBy(function ($shift) {
                return $shift->employee_id . '-' . $shift->date->format('Y-m-d');
            });

        $days = [];
        for ($date = $weekStart->copy(); $date <= $weekEnd; $date->addDay()) {
            $days[] = [
                'date' => $date->copy(),
                'label' => $date->format('D d'),
            ];
        }

        return view('scheduling.index', compact('employees', 'shifts', 'days', 'weekStart', 'weekEnd'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'nullable|string|max:50',
        ]);

        Shift::create([
            'employee_id' => $validated['employee_id'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'type' => $validated['type'],
            'is_published' => false,
        ]);

        return redirect()->route('scheduling.index')
            ->with('success', 'Shift created successfully.');
    }

    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'nullable|string|max:50',
        ]);

        $shift->update($validated);

        return redirect()->route('scheduling.index')
            ->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('scheduling.index')
            ->with('success', 'Shift deleted.');
    }

    public function publish(Request $request)
    {
        $validated = $request->validate([
            'week_start' => 'required|date',
        ]);

        $weekStart = Carbon::parse($validated['week_start'])->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        Shift::whereBetween('date', [$weekStart, $weekEnd])
            ->update(['is_published' => true]);

        return redirect()->route('scheduling.index')
            ->with('success', 'Schedule published successfully.');
    }
}
