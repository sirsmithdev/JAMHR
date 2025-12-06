<?php

namespace App\Http\Controllers;

use App\Models\EducationProgram;
use App\Models\EducationRequest;
use App\Models\EducationReimbursement;
use App\Models\EducationGrade;
use App\Models\EmployeeQualification;
use App\Models\Employee;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * Education assistance dashboard
     */
    public function index()
    {
        $stats = [
            'active_programs' => EducationProgram::active()->count(),
            'pending_requests' => EducationRequest::pending()->count(),
            'active_requests' => EducationRequest::active()->count(),
            'total_approved' => EducationRequest::whereNotNull('approved_amount')->sum('approved_amount'),
            'total_paid' => EducationRequest::sum('amount_paid'),
        ];

        $pendingRequests = EducationRequest::with(['employee'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $pendingReimbursements = EducationReimbursement::with(['educationRequest.employee'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('education.index', compact('stats', 'pendingRequests', 'pendingReimbursements'));
    }

    /**
     * List approved programs
     */
    public function programs()
    {
        $programs = EducationProgram::withCount('educationRequests')
            ->orderBy('institution')
            ->orderBy('name')
            ->paginate(15);

        return view('education.programs.index', compact('programs'));
    }

    /**
     * Create program form
     */
    public function createProgram()
    {
        return view('education.programs.create');
    }

    /**
     * Store program
     */
    public function storeProgram(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'program_type' => 'required|in:degree,certificate,diploma,professional,workshop',
            'description' => 'nullable|string',
            'max_reimbursement' => 'nullable|numeric|min:0',
            'duration_months' => 'nullable|integer|min:1',
            'requires_grade_minimum' => 'boolean',
            'minimum_grade' => 'nullable|string|max:10',
        ]);

        EducationProgram::create($validated);

        return redirect()->route('education.programs')
            ->with('success', 'Program added successfully.');
    }

    /**
     * Edit program
     */
    public function editProgram(EducationProgram $program)
    {
        return view('education.programs.edit', compact('program'));
    }

    /**
     * Update program
     */
    public function updateProgram(Request $request, EducationProgram $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'program_type' => 'required|in:degree,certificate,diploma,professional,workshop',
            'description' => 'nullable|string',
            'max_reimbursement' => 'nullable|numeric|min:0',
            'duration_months' => 'nullable|integer|min:1',
            'requires_grade_minimum' => 'boolean',
            'minimum_grade' => 'nullable|string|max:10',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $program->update($validated);

        return redirect()->route('education.programs')
            ->with('success', 'Program updated successfully.');
    }

    /**
     * List education requests
     */
    public function requests(Request $request)
    {
        $query = EducationRequest::with(['employee', 'educationProgram']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('education.requests.index', compact('requests'));
    }

    /**
     * Create request form
     */
    public function createRequest(Employee $employee = null)
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        $programs = EducationProgram::active()->approved()->get();

        return view('education.requests.create', compact('employees', 'programs', 'employee'));
    }

    /**
     * Store education request
     */
    public function storeRequest(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'education_program_id' => 'nullable|exists:education_programs,id',
            'institution' => 'required|string|max:255',
            'program_name' => 'required|string|max:255',
            'program_type' => 'required|in:degree,certificate,diploma,professional,workshop',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'total_cost' => 'required|numeric|min:0',
            'requested_amount' => 'required|numeric|min:0',
            'justification' => 'required|string',
        ]);

        EducationRequest::create($validated);

        return redirect()->route('education.requests')
            ->with('success', 'Education assistance request submitted.');
    }

    /**
     * Show request details
     */
    public function showRequest(EducationRequest $educationRequest)
    {
        $educationRequest->load(['employee', 'educationProgram', 'reimbursements', 'grades', 'approver']);

        return view('education.requests.show', compact('educationRequest'));
    }

    /**
     * Approve request
     */
    public function approveRequest(Request $request, EducationRequest $educationRequest)
    {
        $validated = $request->validate([
            'approved_amount' => 'required|numeric|min:0',
            'repayment_required' => 'boolean',
            'service_commitment_months' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $educationRequest->update([
            'status' => 'approved',
            'approved_amount' => $validated['approved_amount'],
            'repayment_required' => $validated['repayment_required'] ?? false,
            'service_commitment_months' => $validated['service_commitment_months'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Request approved successfully.');
    }

    /**
     * Reject request
     */
    public function rejectRequest(Request $request, EducationRequest $educationRequest)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $educationRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Request rejected.');
    }

    /**
     * Submit reimbursement
     */
    public function submitReimbursement(Request $request, EducationRequest $educationRequest)
    {
        $validated = $request->validate([
            'expense_type' => 'required|in:tuition,books,exam_fees,materials,registration',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('education/receipts', 'public');
        }

        EducationReimbursement::create([
            'education_request_id' => $educationRequest->id,
            'submission_date' => now(),
            'expense_type' => $validated['expense_type'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'receipt_path' => $receiptPath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Reimbursement request submitted.');
    }

    /**
     * Process reimbursement
     */
    public function processReimbursement(Request $request, EducationReimbursement $reimbursement)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,paid',
            'approved_amount' => 'required_if:status,approved,paid|nullable|numeric|min:0',
            'payment_date' => 'required_if:status,paid|nullable|date',
            'payment_method' => 'required_if:status,paid|nullable|string',
            'notes' => 'nullable|string',
        ]);

        $reimbursement->update([
            'status' => $validated['status'],
            'approved_amount' => $validated['approved_amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'notes' => $validated['notes'],
            'processed_by' => auth()->id(),
        ]);

        // Update education request amount paid
        if ($validated['status'] === 'paid') {
            $educationRequest = $reimbursement->educationRequest;
            $educationRequest->increment('amount_paid', $validated['approved_amount']);
        }

        return back()->with('success', 'Reimbursement processed.');
    }

    /**
     * Record grade
     */
    public function recordGrade(Request $request, EducationRequest $educationRequest)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255',
            'semester' => 'nullable|string|max:50',
            'grade' => 'required|string|max:10',
            'grade_points' => 'nullable|numeric|min:0|max:4',
            'passed' => 'boolean',
            'completion_date' => 'nullable|date',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $certificatePath = null;
        if ($request->hasFile('certificate')) {
            $certificatePath = $request->file('certificate')->store('education/certificates', 'public');
        }

        EducationGrade::create([
            'education_request_id' => $educationRequest->id,
            'course_name' => $validated['course_name'],
            'semester' => $validated['semester'],
            'grade' => $validated['grade'],
            'grade_points' => $validated['grade_points'],
            'passed' => $validated['passed'] ?? true,
            'completion_date' => $validated['completion_date'],
            'certificate_path' => $certificatePath,
        ]);

        return back()->with('success', 'Grade recorded successfully.');
    }

    /**
     * Mark program as completed
     */
    public function completeRequest(Request $request, EducationRequest $educationRequest)
    {
        $validated = $request->validate([
            'qualification_title' => 'required|string|max:255',
            'completion_date' => 'required|date',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $certificatePath = null;
        if ($request->hasFile('certificate')) {
            $certificatePath = $request->file('certificate')->store('qualifications', 'public');
        }

        // Update request status
        $educationRequest->update([
            'status' => 'completed',
            'end_date' => $validated['completion_date'],
        ]);

        // Create qualification record
        EmployeeQualification::create([
            'employee_id' => $educationRequest->employee_id,
            'education_request_id' => $educationRequest->id,
            'qualification_type' => $educationRequest->program_type,
            'title' => $validated['qualification_title'],
            'institution' => $educationRequest->institution,
            'field_of_study' => $educationRequest->program_name,
            'start_date' => $educationRequest->start_date,
            'completion_date' => $validated['completion_date'],
            'document_path' => $certificatePath,
            'company_sponsored' => true,
            'verified' => true,
        ]);

        return back()->with('success', 'Program marked as completed and qualification added.');
    }

    /**
     * Employee qualifications
     */
    public function qualifications(Employee $employee = null)
    {
        $query = EmployeeQualification::with('employee');

        if ($employee) {
            $query->where('employee_id', $employee->id);
        }

        $qualifications = $query->orderBy('completion_date', 'desc')->paginate(15);

        $expiringCertifications = EmployeeQualification::with('employee')
            ->expiring()
            ->get();

        return view('education.qualifications.index', compact('qualifications', 'expiringCertifications', 'employee'));
    }

    /**
     * Add qualification (non-sponsored)
     */
    public function addQualification(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'qualification_type' => 'required|in:degree,certificate,diploma,license,certification',
            'title' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'grade' => 'nullable|string|max:20',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('qualifications', 'public');
        }

        EmployeeQualification::create([
            'employee_id' => $validated['employee_id'],
            'qualification_type' => $validated['qualification_type'],
            'title' => $validated['title'],
            'institution' => $validated['institution'],
            'field_of_study' => $validated['field_of_study'],
            'start_date' => $validated['start_date'],
            'completion_date' => $validated['completion_date'],
            'expiry_date' => $validated['expiry_date'],
            'grade' => $validated['grade'],
            'document_path' => $documentPath,
            'company_sponsored' => false,
            'verified' => false,
        ]);

        return back()->with('success', 'Qualification added successfully.');
    }

    /**
     * Employee education summary
     */
    public function employeeSummary(Employee $employee)
    {
        $requests = EducationRequest::with(['educationProgram', 'reimbursements', 'grades'])
            ->where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $qualifications = EmployeeQualification::where('employee_id', $employee->id)
            ->orderBy('completion_date', 'desc')
            ->get();

        $totalSponsored = $requests->sum('amount_paid');

        return view('education.employee', compact('employee', 'requests', 'qualifications', 'totalSponsored'));
    }
}
