<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public LeaveRequest $leaveRequest,
        public string $action // 'submitted', 'approved', 'rejected'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $employee = $this->leaveRequest->employee;
        $dates = $this->leaveRequest->start_date->format('M d, Y') . ' - ' . $this->leaveRequest->end_date->format('M d, Y');

        return match($this->action) {
            'submitted' => (new MailMessage)
                ->subject('New Leave Request Pending Approval')
                ->greeting('Hello!')
                ->line("{$employee->full_name} has submitted a leave request.")
                ->line("**Type:** {$this->leaveRequest->type}")
                ->line("**Dates:** {$dates}")
                ->line("**Days:** {$this->leaveRequest->days_requested}")
                ->action('Review Request', url('/leave'))
                ->line('Please review and approve or reject this request.'),

            'approved' => (new MailMessage)
                ->subject('Leave Request Approved')
                ->greeting("Hi {$employee->first_name}!")
                ->line('Great news! Your leave request has been approved.')
                ->line("**Type:** {$this->leaveRequest->type}")
                ->line("**Dates:** {$dates}")
                ->line("**Days:** {$this->leaveRequest->days_requested}")
                ->action('View Details', url('/leave'))
                ->line('Enjoy your time off!'),

            'rejected' => (new MailMessage)
                ->subject('Leave Request Not Approved')
                ->greeting("Hi {$employee->first_name},")
                ->line('Unfortunately, your leave request was not approved.')
                ->line("**Type:** {$this->leaveRequest->type}")
                ->line("**Dates:** {$dates}")
                ->line("**Reason:** " . ($this->leaveRequest->rejection_reason ?? 'No reason provided'))
                ->action('View Details', url('/leave'))
                ->line('Please contact HR if you have questions.'),

            default => (new MailMessage)->line('Leave request update.'),
        };
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'leave_request',
            'action' => $this->action,
            'leave_request_id' => $this->leaveRequest->id,
            'employee_name' => $this->leaveRequest->employee->full_name,
            'leave_type' => $this->leaveRequest->type,
            'start_date' => $this->leaveRequest->start_date->toDateString(),
            'end_date' => $this->leaveRequest->end_date->toDateString(),
            'message' => $this->getMessage(),
        ];
    }

    private function getMessage(): string
    {
        $name = $this->leaveRequest->employee->full_name;
        return match($this->action) {
            'submitted' => "{$name} submitted a leave request",
            'approved' => "Your leave request has been approved",
            'rejected' => "Your leave request was not approved",
            default => "Leave request update",
        };
    }
}
