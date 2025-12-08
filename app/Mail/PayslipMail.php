<?php

namespace App\Mail;

use App\Models\Payroll;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class PayslipMail extends Mailable
{
    use Queueable, SerializesModels;

    public Payroll $payroll;
    public string $pdfContent;
    public string $pdfFilename;

    /**
     * Create a new message instance.
     */
    public function __construct(Payroll $payroll, string $pdfContent, string $pdfFilename)
    {
        $this->payroll = $payroll;
        $this->pdfContent = $pdfContent;
        $this->pdfFilename = $pdfFilename;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Payslip for ' . $this->payroll->pay_period_end->format('F Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payslip',
            with: [
                'employeeName' => $this->payroll->employee->first_name,
                'payPeriodStart' => $this->payroll->pay_period_start->format('M d, Y'),
                'payPeriodEnd' => $this->payroll->pay_period_end->format('M d, Y'),
                'netPay' => number_format($this->payroll->net_pay, 2),
                'payDate' => $this->payroll->pay_date?->format('M d, Y') ?? 'Pending',
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, $this->pdfFilename)
                ->withMime('application/pdf'),
        ];
    }
}
