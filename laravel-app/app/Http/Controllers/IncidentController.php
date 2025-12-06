<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::with('reporter')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $openCases = Incident::whereIn('status', ['open', 'investigating'])->count();
        $resolvedThisMonth = Incident::where('status', 'resolved')
            ->whereMonth('updated_at', now()->month)
            ->count();

        // Calculate days since last incident
        $lastIncident = Incident::orderBy('occurred_at', 'desc')->first();
        $daysIncidentFree = $lastIncident
            ? now()->diffInDays($lastIncident->occurred_at)
            : 0;

        return view('incidents.index', compact(
            'incidents',
            'openCases',
            'resolvedThisMonth',
            'daysIncidentFree'
        ));
    }

    public function create()
    {
        return view('incidents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'severity' => 'required|in:low,medium,high',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'witnesses' => 'nullable|string',
            'occurred_at' => 'required|date',
        ]);

        Incident::create([
            'reporter_id' => Auth::id(),
            'type' => $validated['type'],
            'severity' => $validated['severity'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'witnesses' => $validated['witnesses'],
            'occurred_at' => $validated['occurred_at'],
            'status' => 'open',
        ]);

        return redirect()->route('incidents.index')
            ->with('success', 'Incident reported successfully.');
    }

    public function show(Incident $incident)
    {
        $incident->load('reporter');
        return view('incidents.show', compact('incident'));
    }

    public function update(Request $request, Incident $incident)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,investigating,resolved',
        ]);

        $incident->update($validated);

        return redirect()->route('incidents.index')
            ->with('success', 'Incident status updated.');
    }

    public function destroy(Incident $incident)
    {
        $incident->delete();
        return redirect()->route('incidents.index')
            ->with('success', 'Incident deleted.');
    }
}
