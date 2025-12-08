<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $employee->full_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #333; }
        .container { padding: 30px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #059669; padding-bottom: 20px; margin-bottom: 20px; }
        .company-name { font-size: 24px; font-weight: bold; color: #059669; }
        .payslip-title { font-size: 18px; color: #666; margin-top: 5px; }
        .period { font-size: 14px; color: #333; margin-top: 10px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 14px; font-weight: bold; color: #059669; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; font-weight: 600; }
        .amount { text-align: right; }
        .total-row { font-weight: bold; background-color: #f0fdf4; }
        .total-row td { border-top: 2px solid #059669; }
        .two-column { display: flex; gap: 30px; }
        .column { flex: 1; }
        .employee-info p { margin-bottom: 5px; }
        .employee-info strong { display: inline-block; width: 100px; }
        .net-pay { font-size: 20px; font-weight: bold; color: #059669; text-align: right; padding: 15px; background: #f0fdf4; border-radius: 8px; margin-top: 20px; }
        .footer { margin-top: 40px; text-align: center; color: #999; font-size: 10px; border-top: 1px solid #ddd; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <div class="company-name">{{ $company['name'] }}</div>
                <div class="payslip-title">PAYSLIP</div>
                <div class="period">
                    Period: {{ $payroll->period_start->format('M d, Y') }} - {{ $payroll->period_end->format('M d, Y') }}
                </div>
            </div>
            <div style="text-align: right;">
                <p>{{ $company['address'] }}</p>
                <p>{{ $company['phone'] }}</p>
                <p>{{ $company['email'] }}</p>
            </div>
        </div>

        <div class="section">
            <div class="section-title">EMPLOYEE INFORMATION</div>
            <div class="two-column">
                <div class="column employee-info">
                    <p><strong>Name:</strong> {{ $employee->full_name }}</p>
                    <p><strong>Employee ID:</strong> {{ $employee->id }}</p>
                    <p><strong>Department:</strong> {{ $employee->department }}</p>
                </div>
                <div class="column employee-info">
                    <p><strong>Job Title:</strong> {{ $employee->job_title }}</p>
                    <p><strong>TRN:</strong> {{ $employee->trn_number }}</p>
                    <p><strong>NIS:</strong> {{ $employee->nis_number }}</p>
                </div>
            </div>
        </div>

        <div class="two-column">
            <div class="column">
                <div class="section">
                    <div class="section-title">EARNINGS</div>
                    <table>
                        <tr>
                            <td>Basic Salary</td>
                            <td class="amount">${{ number_format($payroll->gross_pay, 2) }}</td>
                        </tr>
                        @if($payroll->overtime_pay ?? 0 > 0)
                        <tr>
                            <td>Overtime</td>
                            <td class="amount">${{ number_format($payroll->overtime_pay, 2) }}</td>
                        </tr>
                        @endif
                        @if($payroll->allowances ?? 0 > 0)
                        <tr>
                            <td>Allowances</td>
                            <td class="amount">${{ number_format($payroll->allowances, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td>Gross Pay</td>
                            <td class="amount">${{ number_format($payroll->gross_pay, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="column">
                <div class="section">
                    <div class="section-title">DEDUCTIONS</div>
                    <table>
                        <tr>
                            <td>NIS ({{ config('payroll.nis_employee_rate', 3) }}%)</td>
                            <td class="amount">${{ number_format($payroll->nis_employee, 2) }}</td>
                        </tr>
                        <tr>
                            <td>NHT ({{ config('payroll.nht_employee_rate', 2) }}%)</td>
                            <td class="amount">${{ number_format($payroll->nht_employee, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Education Tax ({{ config('payroll.ed_tax_rate', 2.25) }}%)</td>
                            <td class="amount">${{ number_format($payroll->education_tax, 2) }}</td>
                        </tr>
                        <tr>
                            <td>PAYE</td>
                            <td class="amount">${{ number_format($payroll->paye, 2) }}</td>
                        </tr>
                        @if(($payroll->loan_deduction ?? 0) > 0)
                        <tr>
                            <td>Loan Repayment</td>
                            <td class="amount">${{ number_format($payroll->loan_deduction, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td>Total Deductions</td>
                            <td class="amount">${{ number_format($payroll->nis_employee + $payroll->nht_employee + $payroll->education_tax + $payroll->paye + ($payroll->loan_deduction ?? 0), 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">EMPLOYER CONTRIBUTIONS</div>
            <table>
                <tr>
                    <td>NIS Employer ({{ config('payroll.nis_employer_rate', 3) }}%)</td>
                    <td class="amount">${{ number_format($payroll->nis_employer, 2) }}</td>
                </tr>
                <tr>
                    <td>NHT Employer ({{ config('payroll.nht_employer_rate', 3) }}%)</td>
                    <td class="amount">${{ number_format($payroll->nht_employer, 2) }}</td>
                </tr>
                <tr>
                    <td>Education Tax Employer ({{ config('payroll.ed_tax_employer_rate', 3.5) }}%)</td>
                    <td class="amount">${{ number_format($payroll->education_tax_employer ?? 0, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="net-pay">
            NET PAY: ${{ number_format($payroll->net_pay, 2) }} JMD
        </div>

        <div class="footer">
            <p>This is a computer-generated payslip. No signature required.</p>
            <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        </div>
    </div>
</body>
</html>
