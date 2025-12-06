<?php

namespace App\Notifications;

use App\Models\Payroll;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayrollProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Payroll $payroll
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $employee = $this->payroll->employee;
        $period = $this->payroll->period_start->format('M d') . ' - ' . $this->payroll->period_end->format('M d, Y');

        return (new MailMessage)
            ->subject('Your Payslip is Ready - ' . $period)
            ->greeting("Hi {$employee->first_name}!")
            ->line('Your payslip for the following period is now available:')
            ->line("**Period:** {$period}")
            ->line("**Gross Pay:** $" . number_format($this->payroll->gross_pay, 2))
            ->line("**Net Pay:** $" . number_format($this->payroll->net_pay, 2))
            ->action('View Payslip', url("/payroll/{$this->payroll->id}"))
            ->line('If you have any questions about your payslip, please contact HR.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payroll_processed',
            'payroll_id' => $this->payroll->id,
            'period_start' => $this->payroll->period_start->toDateString(),
            'period_end' => $this->payroll->period_end->toDateString(),
            'net_pay' => $this->payroll->net_pay,
            'message' => 'Your payslip is ready to view',
        ];
    }
}
