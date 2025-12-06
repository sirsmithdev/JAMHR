<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Mail\PayslipMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipController extends Controller
{
    /**
     * View a single payslip
     */
    public function show(Payroll $payroll)
    {
        $payroll->load('employee');

        return view('payroll.payslip', compact('payroll'));
    }

    /**
     * Download a single payslip as PDF
     */
    public function download(Payroll $payroll)
    {
        $payroll->load('employee');

        $pdf = Pdf::loadView('payroll.payslip-pdf', compact('payroll'));

        $filename = 'payslip-' . $payroll->employee->full_name . '-' . $payroll->pay_period_end->format('Y-m') . '.pdf';
        $filename = str_replace(' ', '-', $filename);

        return $pdf->download($filename);
    }

    /**
     * Stream/view a single payslip as PDF in browser
     */
    public function view(Payroll $payroll)
    {
        $payroll->load('employee');

        $pdf = Pdf::loadView('payroll.payslip-pdf', compact('payroll'));

        return $pdf->stream('payslip.pdf');
    }

    /**
     * Send a single payslip via email
     */
    public function email(Payroll $payroll)
    {
        $payroll->load('employee');

        if (!$payroll->employee->email) {
            return back()->with('error', 'Employee does not have an email address.');
        }

        // Generate PDF
        $pdf = Pdf::loadView('payroll.payslip-pdf', compact('payroll'));

        $filename = 'payslip-' . $payroll->pay_period_end->format('Y-m') . '.pdf';

        // Send email with PDF attachment
        Mail::to($payroll->employee->email)
            ->send(new PayslipMail($payroll, $pdf->output(), $filename));

        // Update payroll record
        $payroll->update([
            'payslip_sent' => true,
            'payslip_sent_at' => now(),
        ]);

        return back()->with('success', 'Payslip sent to ' . $payroll->employee->email);
    }

    /**
     * Bulk send payslips for a specific pay period
     */
    public function bulkEmail(Request $request)
    {
        $validated = $request->validate([
            'pay_period_end' => 'required|date',
            'selected_employees' => 'nullable|array',
            'selected_employees.*' => 'exists:employees,id',
        ]);

        $query = Payroll::with('employee')
            ->whereDate('pay_period_end', $validated['pay_period_end'])
            ->whereIn('status', ['Finalized', 'Paid']);

        // If specific employees selected, filter by them
        if (!empty($validated['selected_employees'])) {
            $query->whereIn('employee_id', $validated['selected_employees']);
        }

        $payrolls = $query->get();

        if ($payrolls->isEmpty()) {
            return back()->with('error', 'No payroll records found for the selected period.');
        }

        $sent = 0;
        $failed = 0;
        $skipped = 0;
        $errors = [];

        foreach ($payrolls as $payroll) {
            // Skip if no email
            if (!$payroll->employee->email) {
                $skipped++;
                continue;
            }

            try {
                // Generate PDF
                $pdf = Pdf::loadView('payroll.payslip-pdf', compact('payroll'));
                $filename = 'payslip-' . $payroll->pay_period_end->format('Y-m') . '.pdf';

                // Send email
                Mail::to($payroll->employee->email)
                    ->send(new PayslipMail($payroll, $pdf->output(), $filename));

                // Update payroll record
                $payroll->update([
                    'payslip_sent' => true,
                    'payslip_sent_at' => now(),
                ]);

                $sent++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = $payroll->employee->full_name . ': ' . $e->getMessage();
            }
        }

        $message = "Payslips sent: {$sent}";
        if ($skipped > 0) {
            $message .= ", Skipped (no email): {$skipped}";
        }
        if ($failed > 0) {
            $message .= ", Failed: {$failed}";
        }

        if ($failed > 0) {
            return back()
                ->with('warning', $message)
                ->with('errors', $errors);
        }

        return back()->with('success', $message);
    }

    /**
     * Show bulk email form
     */
    public function bulkEmailForm(Request $request)
    {
        $payPeriodEnd = $request->input('pay_period_end', now()->endOfMonth()->format('Y-m-d'));

        $payrolls = Payroll::with('employee')
            ->whereDate('pay_period_end', $payPeriodEnd)
            ->whereIn('status', ['Finalized', 'Paid'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get unique pay periods for dropdown
        $payPeriods = Payroll::selectRaw('DISTINCT DATE(pay_period_end) as period')
            ->whereIn('status', ['Finalized', 'Paid'])
            ->orderBy('period', 'desc')
            ->limit(12)
            ->pluck('period');

        return view('payroll.bulk-email', compact('payrolls', 'payPeriodEnd', 'payPeriods'));
    }

    /**
     * Preview payslip in HTML (for testing)
     */
    public function preview(Payroll $payroll)
    {
        $payroll->load('employee');

        return view('payroll.payslip-pdf', compact('payroll'));
    }

    /**
     * Generate payslips for all employees in a pay period
     */
    public function generateBatch(Request $request)
    {
        $validated = $request->validate([
            'pay_period_end' => 'required|date',
        ]);

        $payrolls = Payroll::with('employee')
            ->whereDate('pay_period_end', $validated['pay_period_end'])
            ->whereIn('status', ['Finalized', 'Paid'])
            ->get();

        if ($payrolls->isEmpty()) {
            return back()->with('error', 'No finalized payroll records found for this period.');
        }

        // Mark all as having payslips generated
        foreach ($payrolls as $payroll) {
            $payroll->update(['payslip_generated' => true]);
        }

        return back()->with('success', count($payrolls) . ' payslips generated successfully.');
    }
}
