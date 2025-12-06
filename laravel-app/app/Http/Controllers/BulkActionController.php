<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\TimeEntry;
use App\Models\Payroll;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkActionController extends Controller
{
    /**
     * Bulk update employee departments
     */
    public function updateEmployeeDepartments(Request $request)
    {
        $this->authorize('employees.edit');

        $validated = $request->validate([
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id',
            'department' => 'required|string|max:100',
        ]);

        $count = Employee::whereIn('id', $validated['employee_ids'])
            ->update(['department' => $validated['department']]);

        AuditLog::log('bulk_update', null, null, [
            'action' => 'update_departments',
            'count' => $count,
            'department' => $validated['department'],
        ]);

        return back()->with('success', "Updated department for {$count} employees.");
    }

    /**
     * Bulk approve leave requests
     */
    public function approveLeaveRequests(Request $request)
    {
        $this->authorize('leave.approve');

        $validated = $request->validate([
            'leave_ids' => 'required|array|min:1',
            'leave_ids.*' => 'exists:leave_requests,id',
        ]);

        $count = LeaveRequest::whereIn('id', $validated['leave_ids'])
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

        AuditLog::log('bulk_approve', null, null, [
            'action' => 'approve_leaves',
            'count' => $count,
        ]);

        return back()->with('success', "Approved {$count} leave requests.");
    }

    /**
     * Bulk reject leave requests
     */
    public function rejectLeaveRequests(Request $request)
    {
        $this->authorize('leave.approve');

        $validated = $request->validate([
            'leave_ids' => 'required|array|min:1',
            'leave_ids.*' => 'exists:leave_requests,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $count = LeaveRequest::whereIn('id', $validated['leave_ids'])
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => $validated['reason'] ?? 'Bulk rejection',
            ]);

        AuditLog::log('bulk_reject', null, null, [
            'action' => 'reject_leaves',
            'count' => $count,
        ]);

        return back()->with('success', "Rejected {$count} leave requests.");
    }

    /**
     * Bulk approve time entries
     */
    public function approveTimeEntries(Request $request)
    {
        $this->authorize('time.approve');

        $validated = $request->validate([
            'entry_ids' => 'required|array|min:1',
            'entry_ids.*' => 'exists:time_entries,id',
        ]);

        $count = TimeEntry::whereIn('id', $validated['entry_ids'])
            ->where('status', 'pending')
            ->update(['status' => 'approved']);

        AuditLog::log('bulk_approve', null, null, [
            'action' => 'approve_time_entries',
            'count' => $count,
        ]);

        return back()->with('success', "Approved {$count} time entries.");
    }

    /**
     * Bulk process payroll
     */
    public function processPayroll(Request $request)
    {
        $this->authorize('payroll.create');

        $validated = $request->validate([
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
        ]);

        $employees = Employee::whereIn('id', $validated['employee_ids'])->get();
        $created = 0;

        DB::transaction(function () use ($employees, $validated, &$created) {
            foreach ($employees as $employee) {
                // Check if payroll already exists for this period
                $exists = Payroll::where('employee_id', $employee->id)
                    ->where('period_start', $validated['period_start'])
                    ->exists();

                if (!$exists) {
                    // Use PayrollCalculator service
                    $calculator = new \App\Services\PayrollCalculator();
                    $calculations = $calculator->calculate($employee->salary_annual / 12);

                    Payroll::create([
                        'employee_id' => $employee->id,
                        'period_start' => $validated['period_start'],
                        'period_end' => $validated['period_end'],
                        'gross_pay' => $calculations['gross'],
                        'nis_employee' => $calculations['nis_employee'],
                        'nis_employer' => $calculations['nis_employer'],
                        'nht_employee' => $calculations['nht_employee'],
                        'nht_employer' => $calculations['nht_employer'],
                        'education_tax' => $calculations['education_tax'],
                        'paye' => $calculations['paye'],
                        'net_pay' => $calculations['net'],
                        'status' => 'draft',
                    ]);

                    $created++;
                }
            }
        });

        AuditLog::log('bulk_create', null, null, [
            'action' => 'process_payroll',
            'count' => $created,
            'period' => $validated['period_start'] . ' - ' . $validated['period_end'],
        ]);

        return back()->with('success', "Created payroll for {$created} employees.");
    }

    /**
     * Bulk finalize payroll
     */
    public function finalizePayroll(Request $request)
    {
        $this->authorize('payroll.approve');

        $validated = $request->validate([
            'payroll_ids' => 'required|array|min:1',
            'payroll_ids.*' => 'exists:payrolls,id',
        ]);

        $count = Payroll::whereIn('id', $validated['payroll_ids'])
            ->where('status', 'draft')
            ->update(['status' => 'finalized']);

        AuditLog::log('bulk_finalize', null, null, [
            'action' => 'finalize_payroll',
            'count' => $count,
        ]);

        return back()->with('success', "Finalized {$count} payroll records.");
    }

    /**
     * Import employees from CSV
     */
    public function importEmployees(Request $request)
    {
        $this->authorize('employees.create');

        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');

        // Skip header row
        $headers = fgetcsv($handle);

        $imported = 0;
        $errors = [];

        DB::transaction(function () use ($handle, &$imported, &$errors) {
            $row = 1;
            while (($data = fgetcsv($handle)) !== false) {
                $row++;
                try {
                    // Map CSV columns to employee fields
                    if (count($data) >= 6) {
                        Employee::create([
                            'first_name' => $data[0],
                            'last_name' => $data[1],
                            'job_title' => $data[2] ?? null,
                            'department' => $data[3] ?? null,
                            'start_date' => $data[4] ?? now(),
                            'salary_annual' => (float) str_replace(',', '', $data[5] ?? 0),
                        ]);
                        $imported++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row {$row}: " . $e->getMessage();
                }
            }
        });

        fclose($handle);

        AuditLog::log('bulk_import', null, null, [
            'action' => 'import_employees',
            'count' => $imported,
            'errors' => count($errors),
        ]);

        if (count($errors) > 0) {
            return back()->with('warning', "Imported {$imported} employees with " . count($errors) . " errors.");
        }

        return back()->with('success', "Successfully imported {$imported} employees.");
    }

    /**
     * Bulk delete records
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'model' => 'required|in:employees,leave_requests,time_entries,documents',
            'ids' => 'required|array|min:1',
        ]);

        $modelClass = match($validated['model']) {
            'employees' => Employee::class,
            'leave_requests' => LeaveRequest::class,
            'time_entries' => TimeEntry::class,
            'documents' => \App\Models\Document::class,
        };

        // Check permission
        $permission = str_replace('_', '', $validated['model']) . '.delete';
        $this->authorize($permission);

        $count = $modelClass::whereIn('id', $validated['ids'])->delete();

        AuditLog::log('bulk_delete', null, null, [
            'action' => 'bulk_delete',
            'model' => $validated['model'],
            'count' => $count,
        ]);

        return back()->with('success', "Deleted {$count} records.");
    }
}
