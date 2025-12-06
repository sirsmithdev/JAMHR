<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payroll->employee->full_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        .container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1a365d;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a365d;
            margin-bottom: 5px;
        }
        .company-address {
            font-size: 10px;
            color: #666;
        }
        .payslip-title {
            font-size: 18px;
            font-weight: bold;
            color: #1a365d;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .pay-period {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }
        .info-box h3 {
            font-size: 12px;
            font-weight: bold;
            color: #1a365d;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .earnings-deductions {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .earnings-column, .deductions-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            background: #1a365d;
            padding: 8px 10px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .deductions-column .section-title {
            background: #dc2626;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 6px 8px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        table th {
            font-weight: bold;
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
        }
        table td.amount {
            text-align: right;
            font-family: 'DejaVu Sans Mono', monospace;
        }
        .subtotal-row td {
            border-top: 2px solid #ddd;
            font-weight: bold;
            padding-top: 10px;
        }
        .net-pay-section {
            background: #f0fdf4;
            border: 2px solid #16a34a;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
        }
        .net-pay-label {
            font-size: 14px;
            font-weight: bold;
            color: #166534;
            text-transform: uppercase;
        }
        .net-pay-amount {
            font-size: 28px;
            font-weight: bold;
            color: #16a34a;
            font-family: 'DejaVu Sans Mono', monospace;
        }
        .statutory-section {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
        }
        .statutory-title {
            font-size: 11px;
            font-weight: bold;
            color: #92400e;
            margin-bottom: 8px;
        }
        .statutory-grid {
            display: table;
            width: 100%;
        }
        .statutory-item {
            display: table-cell;
            text-align: center;
            padding: 5px;
            width: 20%;
        }
        .statutory-name {
            font-size: 9px;
            color: #92400e;
            margin-bottom: 3px;
        }
        .statutory-value {
            font-size: 11px;
            font-weight: bold;
            color: #78350f;
        }
        .ytd-section {
            background: #eff6ff;
            border: 1px solid #3b82f6;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
        }
        .ytd-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
        }
        .ytd-grid {
            display: table;
            width: 100%;
        }
        .ytd-item {
            display: table-cell;
            text-align: center;
            padding: 5px;
            width: 25%;
        }
        .ytd-name {
            font-size: 9px;
            color: #1e40af;
            margin-bottom: 3px;
        }
        .ytd-value {
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .footer {
            border-top: 1px solid #ddd;
            padding-top: 15px;
            margin-top: 20px;
            font-size: 9px;
            color: #666;
        }
        .footer-note {
            text-align: center;
            margin-bottom: 10px;
        }
        .footer-details {
            display: table;
            width: 100%;
        }
        .footer-left, .footer-right {
            display: table-cell;
            width: 50%;
        }
        .footer-right {
            text-align: right;
        }
        .confidential {
            text-align: center;
            font-size: 8px;
            color: #999;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ config('app.name', 'JamHR') }}</div>
            <div class="company-address">Kingston, Jamaica</div>
            <div class="payslip-title">Payslip</div>
            <div class="pay-period">
                Pay Period: {{ $payroll->pay_period_start->format('M d') }} - {{ $payroll->pay_period_end->format('M d, Y') }}
            </div>
        </div>

        <!-- Employee & Payment Info -->
        <div class="info-section">
            <div class="info-box">
                <h3>Employee Information</h3>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $payroll->employee->full_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Employee ID:</span>
                    <span class="info-value">{{ $payroll->employee->employee_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Department:</span>
                    <span class="info-value">{{ $payroll->employee->department }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Position:</span>
                    <span class="info-value">{{ $payroll->employee->job_title }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">TRN:</span>
                    <span class="info-value">{{ $payroll->employee->trn ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIS Number:</span>
                    <span class="info-value">{{ $payroll->employee->nis_number ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="info-box">
                <h3>Payment Information</h3>
                <div class="info-row">
                    <span class="info-label">Pay Date:</span>
                    <span class="info-value">{{ $payroll->pay_date?->format('M d, Y') ?? 'Pending' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value">{{ $payroll->employee->payment_method ?? 'Bank Transfer' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Bank:</span>
                    <span class="info-value">{{ $payroll->employee->bank_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Account:</span>
                    <span class="info-value">{{ $payroll->employee->bank_account ? '****' . substr($payroll->employee->bank_account, -4) : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">{{ $payroll->status }}</span>
                </div>
            </div>
        </div>

        <!-- Earnings & Deductions -->
        <div class="earnings-deductions">
            <div class="earnings-column">
                <div class="section-title">Earnings</div>
                <table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th style="text-align: right;">Amount (JMD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Basic Salary</td>
                            <td class="amount">{{ number_format($payroll->basic_salary, 2) }}</td>
                        </tr>
                        @if($payroll->overtime_pay > 0)
                        <tr>
                            <td>Overtime ({{ $payroll->overtime_hours ?? 0 }} hrs)</td>
                            <td class="amount">{{ number_format($payroll->overtime_pay, 2) }}</td>
                        </tr>
                        @endif
                        @if($payroll->allowances > 0)
                        <tr>
                            <td>Allowances</td>
                            <td class="amount">{{ number_format($payroll->allowances, 2) }}</td>
                        </tr>
                        @endif
                        @if($payroll->bonus > 0)
                        <tr>
                            <td>Bonus</td>
                            <td class="amount">{{ number_format($payroll->bonus, 2) }}</td>
                        </tr>
                        @endif
                        @if($payroll->commission > 0)
                        <tr>
                            <td>Commission</td>
                            <td class="amount">{{ number_format($payroll->commission, 2) }}</td>
                        </tr>
                        @endif
                        @if($payroll->other_earnings > 0)
                        <tr>
                            <td>Other Earnings</td>
                            <td class="amount">{{ number_format($payroll->other_earnings, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="subtotal-row">
                            <td>Gross Pay</td>
                            <td class="amount">{{ number_format($payroll->gross_pay, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="deductions-column">
                <div class="section-title">Deductions</div>
                <table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th style="text-align: right;">Amount (JMD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>PAYE (Income Tax)</td>
                            <td class="amount">{{ number_format($payroll->paye, 2) }}</td>
                        </tr>
                        <tr>
                            <td>NIS ({{ number_format(($payroll->nis / $payroll->gross_pay) * 100, 2) }}%)</td>
                            <td class="amount">{{ number_format($payroll->nis, 2) }}</td>
                        </tr>
                        <tr>
                            <td>NHT ({{ number_format(($payroll->nht / $payroll->gross_pay) * 100, 2) }}%)</td>
                            <td class="amount">{{ number_format($payroll->nht, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Education Tax</td>
                            <td class="amount">{{ number_format($payroll->education_tax, 2) }}</td>
                        </tr>
                        @if($payroll->loan_deduction > 0)
                        <tr>
                            <td>Loan Repayment</td>
                            <td class="amount">{{ number_format($payroll->loan_deduction, 2) }}</td>
                        </tr>
                        @endif
                        @if($payroll->other_deductions > 0)
                        <tr>
                            <td>Other Deductions</td>
                            <td class="amount">{{ number_format($payroll->other_deductions, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="subtotal-row">
                            <td>Total Deductions</td>
                            <td class="amount">{{ number_format($payroll->total_deductions, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Net Pay -->
        <div class="net-pay-section">
            <div class="net-pay-label">Net Pay</div>
            <div class="net-pay-amount">JMD {{ number_format($payroll->net_pay, 2) }}</div>
        </div>

        <!-- Jamaican Statutory Deductions Summary -->
        <div class="statutory-section">
            <div class="statutory-title">Jamaican Statutory Contributions Summary</div>
            <div class="statutory-grid">
                <div class="statutory-item">
                    <div class="statutory-name">NIS</div>
                    <div class="statutory-value">{{ number_format($payroll->nis, 2) }}</div>
                </div>
                <div class="statutory-item">
                    <div class="statutory-name">NHT</div>
                    <div class="statutory-value">{{ number_format($payroll->nht, 2) }}</div>
                </div>
                <div class="statutory-item">
                    <div class="statutory-name">Education Tax</div>
                    <div class="statutory-value">{{ number_format($payroll->education_tax, 2) }}</div>
                </div>
                <div class="statutory-item">
                    <div class="statutory-name">PAYE</div>
                    <div class="statutory-value">{{ number_format($payroll->paye, 2) }}</div>
                </div>
                <div class="statutory-item">
                    <div class="statutory-name">Total Statutory</div>
                    <div class="statutory-value">{{ number_format($payroll->nis + $payroll->nht + $payroll->education_tax + $payroll->paye, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Year-to-Date Summary -->
        @if($payroll->ytd_gross > 0 || $payroll->ytd_tax > 0)
        <div class="ytd-section">
            <div class="ytd-title">Year-to-Date Summary ({{ $payroll->pay_period_end->format('Y') }})</div>
            <div class="ytd-grid">
                <div class="ytd-item">
                    <div class="ytd-name">Gross Earnings</div>
                    <div class="ytd-value">{{ number_format($payroll->ytd_gross ?? 0, 2) }}</div>
                </div>
                <div class="ytd-item">
                    <div class="ytd-name">PAYE Paid</div>
                    <div class="ytd-value">{{ number_format($payroll->ytd_tax ?? 0, 2) }}</div>
                </div>
                <div class="ytd-item">
                    <div class="ytd-name">NIS Paid</div>
                    <div class="ytd-value">{{ number_format($payroll->ytd_nis ?? 0, 2) }}</div>
                </div>
                <div class="ytd-item">
                    <div class="ytd-name">NHT Paid</div>
                    <div class="ytd-value">{{ number_format($payroll->ytd_nht ?? 0, 2) }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-note">
                This is a computer-generated payslip and does not require a signature.
            </div>
            <div class="footer-details">
                <div class="footer-left">
                    <strong>Employer Contributions:</strong><br>
                    NIS (Employer): {{ number_format($payroll->employer_nis ?? ($payroll->nis * 1), 2) }} |
                    NHT (Employer): {{ number_format($payroll->employer_nht ?? ($payroll->gross_pay * 0.03), 2) }} |
                    HEART: {{ number_format($payroll->heart ?? ($payroll->gross_pay * 0.03), 2) }}
                </div>
                <div class="footer-right">
                    Generated: {{ now()->format('M d, Y h:i A') }}<br>
                    Reference: {{ $payroll->id }}-{{ $payroll->pay_period_end->format('Ymd') }}
                </div>
            </div>
            <div class="confidential">
                Confidential - For Employee Use Only
            </div>
        </div>
    </div>
</body>
</html>
