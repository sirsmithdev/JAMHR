<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Payslip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1a365d;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1a365d;
        }
        h1 {
            color: #1a365d;
            font-size: 20px;
            margin: 0;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #f0fdf4;
            border: 1px solid #16a34a;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .net-pay-label {
            font-size: 14px;
            color: #166534;
            margin-bottom: 5px;
        }
        .net-pay-amount {
            font-size: 28px;
            font-weight: bold;
            color: #16a34a;
        }
        .details {
            background-color: #f8fafc;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .details-row:last-child {
            border-bottom: none;
        }
        .details-label {
            color: #64748b;
        }
        .details-value {
            font-weight: 600;
            color: #334155;
        }
        .message {
            margin: 20px 0;
            color: #475569;
        }
        .attachment-note {
            background-color: #eff6ff;
            border: 1px solid #3b82f6;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            color: #1e40af;
        }
        .attachment-note strong {
            display: block;
            margin-bottom: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 12px;
        }
        .footer a {
            color: #1a365d;
            text-decoration: none;
        }
        .confidential {
            background-color: #fef2f2;
            color: #991b1b;
            padding: 10px;
            border-radius: 4px;
            font-size: 11px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name', 'JamHR') }}</div>
            <h1>Payslip Notification</h1>
        </div>

        <div class="greeting">
            Dear {{ $employeeName }},
        </div>

        <p class="message">
            Your payslip for the pay period <strong>{{ $payPeriodStart }}</strong> to <strong>{{ $payPeriodEnd }}</strong> is now available.
        </p>

        <div class="info-box">
            <div class="net-pay-label">Net Pay</div>
            <div class="net-pay-amount">JMD {{ $netPay }}</div>
        </div>

        <div class="details">
            <div class="details-row">
                <span class="details-label">Pay Period:</span>
                <span class="details-value">{{ $payPeriodStart }} - {{ $payPeriodEnd }}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Pay Date:</span>
                <span class="details-value">{{ $payDate }}</span>
            </div>
        </div>

        <div class="attachment-note">
            <strong>Attached: Your Detailed Payslip (PDF)</strong>
            Please find your complete payslip attached to this email. The document includes a full breakdown of your earnings, statutory deductions (NIS, NHT, Education Tax, PAYE), and other deductions.
        </div>

        <p class="message">
            If you have any questions about your payslip, please contact the HR department.
        </p>

        <div class="footer">
            <p>This is an automated message from {{ config('app.name', 'JamHR') }}.</p>
            <p>Please do not reply directly to this email.</p>
            <div class="confidential">
                <strong>CONFIDENTIAL:</strong> This email and any attachments contain confidential payroll information intended solely for the named recipient. If you have received this in error, please delete it immediately and notify the sender.
            </div>
        </div>
    </div>
</body>
</html>
