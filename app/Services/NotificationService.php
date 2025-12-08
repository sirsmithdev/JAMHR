<?php

namespace App\Services;

use App\Models\User;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\StaffLoan;
use App\Models\Appraisal;
use App\Notifications\LeaveRequestNotification;
use App\Notifications\PayrollProcessedNotification;
use App\Notifications\LoanApplicationNotification;
use App\Notifications\AppraisalNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Notify managers about a new leave request
     */
    public function notifyLeaveRequestSubmitted(LeaveRequest $leaveRequest): void
    {
        $managers = User::whereIn('role', ['super_admin', 'admin', 'hr', 'manager'])->get();

        Notification::send($managers, new LeaveRequestNotification($leaveRequest, 'submitted'));
    }

    /**
     * Notify employee about leave request approval
     */
    public function notifyLeaveRequestApproved(LeaveRequest $leaveRequest): void
    {
        $user = $leaveRequest->employee->user;

        if ($user) {
            $user->notify(new LeaveRequestNotification($leaveRequest, 'approved'));
        }
    }

    /**
     * Notify employee about leave request rejection
     */
    public function notifyLeaveRequestRejected(LeaveRequest $leaveRequest): void
    {
        $user = $leaveRequest->employee->user;

        if ($user) {
            $user->notify(new LeaveRequestNotification($leaveRequest, 'rejected'));
        }
    }

    /**
     * Notify employee about payroll processing
     */
    public function notifyPayrollProcessed(Payroll $payroll): void
    {
        $user = $payroll->employee->user;

        if ($user) {
            $user->notify(new PayrollProcessedNotification($payroll));
        }
    }

    /**
     * Notify managers about a new loan application
     */
    public function notifyLoanApplicationSubmitted(StaffLoan $loan): void
    {
        $managers = User::whereIn('role', ['super_admin', 'admin', 'hr'])->get();

        Notification::send($managers, new LoanApplicationNotification($loan, 'submitted'));
    }

    /**
     * Notify employee about loan approval
     */
    public function notifyLoanApproved(StaffLoan $loan): void
    {
        $user = $loan->employee->user;

        if ($user) {
            $user->notify(new LoanApplicationNotification($loan, 'approved'));
        }
    }

    /**
     * Notify employee about loan rejection
     */
    public function notifyLoanRejected(StaffLoan $loan): void
    {
        $user = $loan->employee->user;

        if ($user) {
            $user->notify(new LoanApplicationNotification($loan, 'rejected'));
        }
    }

    /**
     * Notify employee about loan disbursement
     */
    public function notifyLoanDisbursed(StaffLoan $loan): void
    {
        $user = $loan->employee->user;

        if ($user) {
            $user->notify(new LoanApplicationNotification($loan, 'disbursed'));
        }
    }

    /**
     * Notify employee about scheduled appraisal
     */
    public function notifyAppraisalScheduled(Appraisal $appraisal): void
    {
        $user = $appraisal->employee->user;

        if ($user) {
            $user->notify(new AppraisalNotification($appraisal, 'scheduled'));
        }
    }

    /**
     * Notify employee about completed appraisal
     */
    public function notifyAppraisalCompleted(Appraisal $appraisal): void
    {
        $user = $appraisal->employee->user;

        if ($user) {
            $user->notify(new AppraisalNotification($appraisal, 'completed'));
        }
    }

    /**
     * Send appraisal reminder to reviewer
     */
    public function sendAppraisalReminder(Appraisal $appraisal): void
    {
        $reviewer = $appraisal->reviewer;

        if ($reviewer) {
            $reviewer->notify(new AppraisalNotification($appraisal, 'reminder'));
        }
    }
}
