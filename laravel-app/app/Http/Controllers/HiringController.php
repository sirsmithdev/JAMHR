<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Models\Interview;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HiringController extends Controller
{
    // ==================== JOB POSTINGS ====================

    public function index(Request $request)
    {
        $query = JobPosting::with('creator')
            ->withCount('applications');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $jobPostings = $query->latest()->paginate(10);

        $stats = [
            'total_postings' => JobPosting::count(),
            'open_positions' => JobPosting::where('status', 'Open')->count(),
            'total_applications' => JobApplication::count(),
            'new_applications' => JobApplication::where('status', 'New')->count(),
            'interviews_today' => Interview::today()->count(),
            'pending_offers' => JobApplication::where('status', 'Offer Extended')->count(),
        ];

        return view('hiring.index', compact('jobPostings', 'stats'));
    }

    public function create()
    {
        return view('hiring.postings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'required|in:Full-time,Part-time,Contract,Temporary,Internship',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'status' => 'required|in:Draft,Open,On Hold,Closed,Filled',
            'closing_date' => 'nullable|date|after:today',
            'positions_available' => 'required|integer|min:1',
        ]);

        $validated['created_by'] = auth()->id();

        if ($validated['status'] === 'Open') {
            $validated['posted_date'] = now();
        }

        JobPosting::create($validated);

        return redirect()->route('hiring.index')
            ->with('success', 'Job posting created successfully.');
    }

    public function show(JobPosting $posting)
    {
        $posting->load(['applications.interviews', 'creator']);

        $applicationStats = [
            'total' => $posting->applications->count(),
            'new' => $posting->applications->where('status', 'New')->count(),
            'in_progress' => $posting->applications->whereIn('status', ['Reviewing', 'Phone Screen', 'Interview Scheduled', 'Interviewed', 'Under Consideration'])->count(),
            'hired' => $posting->applications->where('status', 'Hired')->count(),
        ];

        return view('hiring.postings.show', compact('posting', 'applicationStats'));
    }

    public function edit(JobPosting $posting)
    {
        return view('hiring.postings.edit', compact('posting'));
    }

    public function update(Request $request, JobPosting $posting)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'required|in:Full-time,Part-time,Contract,Temporary,Internship',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'status' => 'required|in:Draft,Open,On Hold,Closed,Filled',
            'closing_date' => 'nullable|date',
            'positions_available' => 'required|integer|min:1',
        ]);

        if ($validated['status'] === 'Open' && !$posting->posted_date) {
            $validated['posted_date'] = now();
        }

        $posting->update($validated);

        return redirect()->route('hiring.postings.show', $posting)
            ->with('success', 'Job posting updated successfully.');
    }

    public function destroy(JobPosting $posting)
    {
        $posting->delete();

        return redirect()->route('hiring.index')
            ->with('success', 'Job posting deleted successfully.');
    }

    // ==================== APPLICATIONS ====================

    public function applications(Request $request)
    {
        $query = JobApplication::with(['jobPosting', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('job_posting_id')) {
            $query->where('job_posting_id', $request->job_posting_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(15);
        $jobPostings = JobPosting::active()->get();

        return view('hiring.applications.index', compact('applications', 'jobPostings'));
    }

    public function applicationCreate(JobPosting $posting = null)
    {
        $jobPostings = JobPosting::open()->get();
        return view('hiring.applications.create', compact('jobPostings', 'posting'));
    }

    public function applicationStore(Request $request)
    {
        $validated = $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter_text' => 'nullable|string',
            'expected_salary' => 'nullable|numeric|min:0',
            'available_start_date' => 'nullable|date',
            'experience_summary' => 'nullable|string',
            'education' => 'nullable|string',
            'skills' => 'nullable|string',
            'references' => 'nullable|string',
            'source' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('resume')) {
            $validated['resume_path'] = $request->file('resume')->store('resumes', 'public');
        }

        if ($request->hasFile('cover_letter')) {
            $validated['cover_letter_path'] = $request->file('cover_letter')->store('cover_letters', 'public');
        }

        unset($validated['resume'], $validated['cover_letter']);

        JobApplication::create($validated);

        return redirect()->route('hiring.applications')
            ->with('success', 'Application submitted successfully.');
    }

    public function applicationShow(JobApplication $application)
    {
        $application->load(['jobPosting', 'interviews.creator', 'reviewer']);

        return view('hiring.applications.show', compact('application'));
    }

    public function applicationUpdate(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:New,Reviewing,Phone Screen,Interview Scheduled,Interviewed,Under Consideration,Offer Extended,Offer Accepted,Offer Declined,Hired,Rejected,Withdrawn',
            'rating' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        $validated['reviewed_by'] = auth()->id();
        $validated['reviewed_at'] = now();

        $application->update($validated);

        // If hired, increment positions filled
        if ($validated['status'] === 'Hired') {
            $application->jobPosting->increment('positions_filled');
        }

        return back()->with('success', 'Application updated successfully.');
    }

    public function hireApplication(JobApplication $application)
    {
        // Create employee from application
        $employee = Employee::create([
            'first_name' => $application->first_name,
            'last_name' => $application->last_name,
            'email' => $application->email,
            'phone' => $application->phone,
            'department' => $application->jobPosting->department,
            'job_title' => $application->jobPosting->title,
            'employment_type' => $application->jobPosting->employment_type,
            'hire_date' => $application->available_start_date ?? now(),
            'status' => 'Active',
        ]);

        $application->update([
            'status' => 'Hired',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $application->jobPosting->increment('positions_filled');

        return redirect()->route('employees.edit', $employee)
            ->with('success', 'Applicant hired! Please complete their employee profile.');
    }

    // ==================== INTERVIEWS ====================

    public function interviews(Request $request)
    {
        $query = Interview::with(['application.jobPosting', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $interviews = $query->orderBy('scheduled_at')->paginate(15);

        $upcomingInterviews = Interview::upcoming()->with(['application.jobPosting'])->take(5)->get();

        return view('hiring.interviews.index', compact('interviews', 'upcomingInterviews'));
    }

    public function interviewCreate(JobApplication $application = null)
    {
        $applications = JobApplication::active()
            ->with('jobPosting')
            ->get();

        return view('hiring.interviews.create', compact('applications', 'application'));
    }

    public function interviewStore(Request $request)
    {
        $validated = $request->validate([
            'job_application_id' => 'required|exists:job_applications,id',
            'type' => 'required|in:Phone Screen,Video Call,In-Person,Panel,Technical,Final',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'Scheduled';

        Interview::create($validated);

        // Update application status
        JobApplication::find($validated['job_application_id'])
            ->update(['status' => 'Interview Scheduled']);

        return redirect()->route('hiring.interviews')
            ->with('success', 'Interview scheduled successfully.');
    }

    public function interviewShow(Interview $interview)
    {
        $interview->load(['application.jobPosting', 'creator']);

        return view('hiring.interviews.show', compact('interview'));
    }

    public function interviewUpdate(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'status' => 'required|in:Scheduled,Completed,Cancelled,No Show,Rescheduled',
            'outcome' => 'nullable|in:Pending,Pass,Fail,On Hold',
            'feedback' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'questions_asked' => 'nullable|string',
            'candidate_questions' => 'nullable|string',
        ]);

        $interview->update($validated);

        // Update application status based on outcome
        if ($validated['status'] === 'Completed') {
            $interview->application->update(['status' => 'Interviewed']);
        }

        return back()->with('success', 'Interview updated successfully.');
    }
}
