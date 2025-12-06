<?php

namespace App\Http\Controllers;

use App\Models\Appraisal;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    public function index()
    {
        $appraisals = Appraisal::with(['employee', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate company average rating
        $avgRating = Appraisal::where('status', 'completed')->avg('rating_overall') ?? 0;

        // Count pending reviews
        $reviewsDue = Appraisal::whereIn('status', ['draft', 'needs_review'])->count();

        // Get top performer
        $topPerformer = Appraisal::with('employee')
            ->where('status', 'completed')
            ->orderBy('rating_overall', 'desc')
            ->first();

        return view('performance.index', compact(
            'appraisals',
            'avgRating',
            'reviewsDue',
            'topPerformer'
        ));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('performance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'cycle' => 'required|string|max:20',
            'job_knowledge' => 'required|numeric|min:1|max:5',
            'quality_of_work' => 'required|numeric|min:1|max:5',
            'communication' => 'required|numeric|min:1|max:5',
            'goals_met_percentage' => 'nullable|integer|min:0|max:100',
            'manager_comments' => 'nullable|string',
            'status' => 'required|in:draft,needs_review,completed',
        ]);

        $scoreCompetency = [
            'job_knowledge' => $validated['job_knowledge'],
            'quality_of_work' => $validated['quality_of_work'],
            'communication' => $validated['communication'],
        ];

        $ratingOverall = round(array_sum($scoreCompetency) / count($scoreCompetency), 1);

        Appraisal::create([
            'employee_id' => $validated['employee_id'],
            'reviewer_id' => Auth::id(),
            'cycle' => $validated['cycle'],
            'score_competency' => $scoreCompetency,
            'rating_overall' => $ratingOverall,
            'goals_met_percentage' => $validated['goals_met_percentage'],
            'manager_comments' => $validated['manager_comments'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('performance.index')
            ->with('success', 'Appraisal created successfully.');
    }

    public function show(Appraisal $appraisal)
    {
        $appraisal->load(['employee', 'reviewer']);
        return view('performance.show', compact('appraisal'));
    }

    public function edit(Appraisal $appraisal)
    {
        $appraisal->load('employee');
        return view('performance.edit', compact('appraisal'));
    }

    public function update(Request $request, Appraisal $appraisal)
    {
        $validated = $request->validate([
            'job_knowledge' => 'required|numeric|min:1|max:5',
            'quality_of_work' => 'required|numeric|min:1|max:5',
            'communication' => 'required|numeric|min:1|max:5',
            'goals_met_percentage' => 'nullable|integer|min:0|max:100',
            'manager_comments' => 'nullable|string',
            'status' => 'required|in:draft,needs_review,completed',
        ]);

        $scoreCompetency = [
            'job_knowledge' => $validated['job_knowledge'],
            'quality_of_work' => $validated['quality_of_work'],
            'communication' => $validated['communication'],
        ];

        $ratingOverall = round(array_sum($scoreCompetency) / count($scoreCompetency), 1);

        $appraisal->update([
            'score_competency' => $scoreCompetency,
            'rating_overall' => $ratingOverall,
            'goals_met_percentage' => $validated['goals_met_percentage'],
            'manager_comments' => $validated['manager_comments'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('performance.index')
            ->with('success', 'Appraisal updated successfully.');
    }

    public function destroy(Appraisal $appraisal)
    {
        $appraisal->delete();
        return redirect()->route('performance.index')
            ->with('success', 'Appraisal deleted.');
    }
}
