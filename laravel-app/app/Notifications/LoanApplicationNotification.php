<?php

namespace App\Notifications;

use App\Models\StaffLoan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanApplicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public StaffLoan $loan,
        public string $action // 'submitted', 'approved', 'rejected', 'disbursed'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $employee = $this->loan->employee;
        $amount = number_format($this->loan->amount, 2);

        return match($this->action) {
            'submitted' => (new MailMessage)
                ->subject('New Loan Application Pending Review')
                ->greeting('Hello!')
                ->line("{$employee->full_name} has submitted a loan application.")
                ->line("**Amount:** \${$amount}")
                ->line("**Purpose:** {$this->loan->purpose}")
                ->action('Review Application', url("/loans/{$this->loan->id}"))
                ->line('Please review this application at your earliest convenience.'),

            'approved' => (new MailMessage)
                ->subject('Loan Application Approved')
                ->greeting("Hi {$employee->first_name}!")
                ->line('Great news! Your loan application has been approved.')
                ->line("**Amount:** \${$amount}")
                ->line("**Monthly Payment:** \$" . number_format($this->loan->monthly_payment, 2))
                ->action('View Details', url("/loans/{$this->loan->id}"))
                ->line('The funds will be disbursed soon.'),

            'rejected' => (new MailMessage)
                ->subject('Loan Application Not Approved')
                ->greeting("Hi {$employee->first_name},")
                ->line('We regret to inform you that your loan application was not approved.')
                ->line("**Amount Requested:** \${$amount}")
                ->line("**Reason:** " . ($this->loan->rejection_reason ?? 'Please contact HR for details'))
                ->action('View Details', url("/loans/{$this->loan->id}"))
                ->line('Please contact HR if you have questions.'),

            'disbursed' => (new MailMessage)
                ->subject('Loan Disbursed')
                ->greeting("Hi {$employee->first_name}!")
                ->line('Your loan has been disbursed.')
                ->line("**Amount:** \${$amount}")
                ->line("**First Payment Due:** " . $this->loan->first_payment_date?->format('M d, Y'))
                ->action('View Payment Schedule', url("/loans/{$this->loan->id}"))
                ->line('Payments will be automatically deducted from your salary.'),

            default => (new MailMessage)->line('Loan application update.'),
        };
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'loan_application',
            'action' => $this->action,
            'loan_id' => $this->loan->id,
            'employee_name' => $this->loan->employee->full_name,
            'amount' => $this->loan->amount,
            'message' => $this->getMessage(),
        ];
    }

    private function getMessage(): string
    {
        $name = $this->loan->employee->full_name;
        return match($this->action) {
            'submitted' => "{$name} submitted a loan application",
            'approved' => "Your loan application has been approved",
            'rejected' => "Your loan application was not approved",
            'disbursed' => "Your loan has been disbursed",
            default => "Loan application update",
        };
    }
}
