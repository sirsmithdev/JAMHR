<?php

namespace App\Notifications;

use App\Models\Appraisal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppraisalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appraisal $appraisal,
        public string $action // 'scheduled', 'completed', 'reminder'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $employee = $this->appraisal->employee;

        return match($this->action) {
            'scheduled' => (new MailMessage)
                ->subject('Performance Review Scheduled')
                ->greeting("Hi {$employee->first_name}!")
                ->line('A performance review has been scheduled for you.')
                ->line("**Review Period:** {$this->appraisal->cycle}")
                ->line("**Reviewer:** {$this->appraisal->reviewer->name}")
                ->action('View Details', url("/performance/{$this->appraisal->id}"))
                ->line('Please prepare for your review.'),

            'completed' => (new MailMessage)
                ->subject('Performance Review Completed')
                ->greeting("Hi {$employee->first_name}!")
                ->line('Your performance review has been completed.')
                ->line("**Review Period:** {$this->appraisal->cycle}")
                ->line("**Overall Rating:** {$this->appraisal->rating_overall}/5")
                ->action('View Results', url("/performance/{$this->appraisal->id}"))
                ->line('Please review the feedback and goals.'),

            'reminder' => (new MailMessage)
                ->subject('Performance Review Reminder')
                ->greeting('Hello!')
                ->line("Reminder: A performance review for {$employee->full_name} is pending.")
                ->line("**Review Period:** {$this->appraisal->cycle}")
                ->action('Complete Review', url("/performance/{$this->appraisal->id}/edit"))
                ->line('Please complete this review soon.'),

            default => (new MailMessage)->line('Performance review update.'),
        };
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'appraisal',
            'action' => $this->action,
            'appraisal_id' => $this->appraisal->id,
            'employee_name' => $this->appraisal->employee->full_name,
            'cycle' => $this->appraisal->cycle,
            'message' => $this->getMessage(),
        ];
    }

    private function getMessage(): string
    {
        return match($this->action) {
            'scheduled' => "Performance review scheduled",
            'completed' => "Performance review completed",
            'reminder' => "Performance review reminder",
            default => "Performance review update",
        };
    }
}
