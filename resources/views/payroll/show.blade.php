<x-app-layout>
    @section('title', 'Payroll Details')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('payroll.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Payroll
                </a>
                <h1 class="text-3xl font-serif text-foreground">Payroll Details</h1>
                <p class="text-muted-foreground mt-1">
                    {{ $payroll->employee->full_name }} - {{ $payroll->period_start->format('M d') }} to {{ $payroll->period_end->format('M d, Y') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $payroll->status_badge_class }}">
                    {{ ucfirst($payroll->status) }}
                </span>
                @if(in_array($payroll->status, ['finalized', 'paid']))
                <a href="{{ route('payslips.show', $payroll) }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    View Payslip
                </a>
                <a href="{{ route('payslips.download', $payroll) }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90 transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download PDF
                </a>
                @endif
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Earnings -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-primary text-white">
                        <h3 class="font-serif font-semibold">Earnings</h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full">
                            <tbody class="divide-y divide-border">
                                <tr>
                                    <td class="py-3 text-muted-foreground">Gross Pay</td>
                                    <td class="py-3 text-right font-mono font-semibold">JMD {{ number_format($payroll->gross_pay, 2) }}</td>
                                </tr>
                                @if($payroll->overtime_pay > 0)
                                <tr>
                                    <td class="py-3 text-muted-foreground">Overtime ({{ $payroll->overtime_hours ?? 0 }} hrs)</td>
                                    <td class="py-3 text-right font-mono">JMD {{ number_format($payroll->overtime_pay, 2) }}</td>
                                </tr>
                                @endif
                                @if($payroll->allowances > 0)
                                <tr>
                                    <td class="py-3 text-muted-foreground">Allowances</td>
                                    <td class="py-3 text-right font-mono">JMD {{ number_format($payroll->allowances, 2) }}</td>
                                </tr>
                                @endif
                                @if($payroll->bonus > 0)
                                <tr>
                                    <td class="py-3 text-muted-foreground">Bonus</td>
                                    <td class="py-3 text-right font-mono">JMD {{ number_format($payroll->bonus, 2) }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Deductions -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-red-600 text-white">
                        <h3 class="font-serif font-semibold">Statutory Deductions (Employee)</h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full">
                            <tbody class="divide-y divide-border">
                                <tr>
                                    <td class="py-3 text-muted-foreground">PAYE (Income Tax)</td>
                                    <td class="py-3 text-right font-mono text-red-600">-JMD {{ number_format($payroll->income_tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-muted-foreground">NIS (3%)</td>
                                    <td class="py-3 text-right font-mono text-red-600">-JMD {{ number_format($payroll->nis_employee, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-muted-foreground">NHT (2%)</td>
                                    <td class="py-3 text-right font-mono text-red-600">-JMD {{ number_format($payroll->nht_employee, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-muted-foreground">Education Tax (2.25%)</td>
                                    <td class="py-3 text-right font-mono text-red-600">-JMD {{ number_format($payroll->ed_tax_employee, 2) }}</td>
                                </tr>
                                @if($payroll->loan_deduction > 0)
                                <tr>
                                    <td class="py-3 text-muted-foreground">Loan Deduction</td>
                                    <td class="py-3 text-right font-mono text-red-600">-JMD {{ number_format($payroll->loan_deduction, 2) }}</td>
                                </tr>
                                @endif
                                @if($payroll->other_deductions > 0)
                                <tr>
                                    <td class="py-3 text-muted-foreground">Other Deductions</td>
                                    <td class="py-3 text-right font-mono text-red-600">-JMD {{ number_format($payroll->other_deductions, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="border-t-2 border-border">
                                    <td class="py-3 font-semibold">Total Deductions</td>
                                    <td class="py-3 text-right font-mono font-semibold text-red-600">-JMD {{ number_format($payroll->total_employee_deductions, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Employer Contributions -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-blue-600 text-white">
                        <h3 class="font-serif font-semibold">Employer Contributions</h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full">
                            <tbody class="divide-y divide-border">
                                <tr>
                                    <td class="py-3 text-muted-foreground">NIS (3%)</td>
                                    <td class="py-3 text-right font-mono">JMD {{ number_format($payroll->nis_employer, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-muted-foreground">NHT (3%)</td>
                                    <td class="py-3 text-right font-mono">JMD {{ number_format($payroll->nht_employer, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-muted-foreground">Education Tax (3.5%)</td>
                                    <td class="py-3 text-right font-mono">JMD {{ number_format($payroll->ed_tax_employer, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-muted-foreground">HEART Trust (3%)</td>
                                    <td class="py-3 text-right font-mono">JMD {{ number_format($payroll->heart_employer, 2) }}</td>
                                </tr>
                                <tr class="border-t-2 border-border">
                                    <td class="py-3 font-semibold">Total Employer Contributions</td>
                                    <td class="py-3 text-right font-mono font-semibold">JMD {{ number_format($payroll->total_employer_contributions, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Net Pay Card -->
                <div class="bg-emerald-50 border-2 border-emerald-500 rounded-lg p-6 text-center">
                    <p class="text-sm font-semibold text-emerald-700 uppercase">Net Pay</p>
                    <p class="text-3xl font-bold text-emerald-600 font-mono mt-2">JMD {{ number_format($payroll->net_pay, 2) }}</p>
                </div>

                <!-- Employee Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Employee</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium">
                            {{ $payroll->employee->initials }}
                        </div>
                        <div>
                            <div class="font-medium">{{ $payroll->employee->full_name }}</div>
                            <div class="text-sm text-muted-foreground">{{ $payroll->employee->job_title }}</div>
                        </div>
                    </div>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Department</dt>
                            <dd class="font-medium">{{ $payroll->employee->department }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Employee ID</dt>
                            <dd class="font-medium">{{ $payroll->employee->employee_id }}</dd>
                        </div>
                        @if($payroll->employee->trn)
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">TRN</dt>
                            <dd class="font-medium">{{ $payroll->employee->trn }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Pay Period Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Pay Period</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Start Date</dt>
                            <dd class="font-medium">{{ $payroll->period_start->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">End Date</dt>
                            <dd class="font-medium">{{ $payroll->period_end->format('M d, Y') }}</dd>
                        </div>
                        @if($payroll->pay_date)
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Pay Date</dt>
                            <dd class="font-medium">{{ $payroll->pay_date->format('M d, Y') }}</dd>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Status</dt>
                            <dd><span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $payroll->status_badge_class }}">{{ ucfirst($payroll->status) }}</span></dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 space-y-3">
                    @if($payroll->status === 'draft')
                    <form action="{{ route('payroll.finalize', $payroll) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Finalize Payroll
                        </button>
                    </form>
                    @endif

                    @if($payroll->status === 'finalized')
                    <form action="{{ route('payroll.paid', $payroll) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition-colors">
                            Mark as Paid
                        </button>
                    </form>
                    @endif

                    @if(in_array($payroll->status, ['finalized', 'paid']))
                    @if($payroll->employee->email)
                    <form action="{{ route('payslips.email', $payroll) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 border border-emerald-300 text-emerald-700 rounded-md hover:bg-emerald-50 transition-colors flex items-center justify-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            Email Payslip
                        </button>
                    </form>
                    @if($payroll->payslip_sent)
                    <p class="text-xs text-emerald-600 text-center">
                        Payslip sent on {{ $payroll->payslip_sent_at->format('M d, Y \a\t h:i A') }}
                    </p>
                    @endif
                    @else
                    <p class="text-xs text-amber-600 text-center">No email address on file</p>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
