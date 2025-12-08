<x-app-layout>
    @section('title', 'Payslip - ' . $payroll->employee->full_name)

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('payroll.show', $payroll) }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Payroll Details
                </a>
                <h1 class="text-3xl font-serif text-foreground">Payslip</h1>
                <p class="text-muted-foreground mt-1">
                    {{ $payroll->employee->full_name }} - {{ $payroll->pay_period_start->format('M d') }} to {{ $payroll->pay_period_end->format('M d, Y') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('payslips.view', $payroll) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.64 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.64 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    View PDF
                </a>
                <a href="{{ route('payslips.download', $payroll) }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download PDF
                </a>
                @if($payroll->employee->email)
                <form action="{{ route('payslips.email', $payroll) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition-colors">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        Email Payslip
                    </button>
                </form>
                @endif
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Payslip Preview -->
        <div class="bg-white rounded-lg shadow-md p-8 max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center border-b-2 border-primary pb-4 mb-6">
                <h2 class="text-2xl font-bold text-primary">{{ config('app.name', 'JamHR') }}</h2>
                <p class="text-sm text-muted-foreground">Kingston, Jamaica</p>
                <p class="text-lg font-semibold text-primary mt-2">PAYSLIP</p>
                <p class="text-sm text-muted-foreground">
                    Pay Period: {{ $payroll->pay_period_start->format('M d') }} - {{ $payroll->pay_period_end->format('M d, Y') }}
                </p>
            </div>

            <!-- Employee & Payment Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-semibold text-primary uppercase border-b border-border pb-2 mb-3">Employee Information</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex">
                            <dt class="w-28 text-muted-foreground">Name:</dt>
                            <dd class="font-medium">{{ $payroll->employee->full_name }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-28 text-muted-foreground">Employee ID:</dt>
                            <dd class="font-medium">{{ $payroll->employee->employee_id }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-28 text-muted-foreground">Department:</dt>
                            <dd class="font-medium">{{ $payroll->employee->department }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-28 text-muted-foreground">Position:</dt>
                            <dd class="font-medium">{{ $payroll->employee->job_title }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-28 text-muted-foreground">TRN:</dt>
                            <dd class="font-medium">{{ $payroll->employee->trn ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-28 text-muted-foreground">NIS Number:</dt>
                            <dd class="font-medium">{{ $payroll->employee->nis_number ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-primary uppercase border-b border-border pb-2 mb-3">Payment Information</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex">
                            <dt class="w-28 text-muted-foreground">Pay Date:</dt>
                            <dd class="font-medium">{{ $payroll->pay_date?->format('M d, Y') ?? 'Pending' }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-28 text-muted-foreground">Method:</dt>
                            <dd class="font-medium">{{ $payroll->employee->payment_method ?? 'Bank Transfer' }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-28 text-muted-foreground">Bank:</dt>
                            <dd class="font-medium">{{ $payroll->employee->bank_name ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-28 text-muted-foreground">Account:</dt>
                            <dd class="font-medium">{{ $payroll->employee->bank_account ? '****' . substr($payroll->employee->bank_account, -4) : 'N/A' }}</dd>
                        </div>
                        <div class="flex">
                            <dt class="w-28 text-muted-foreground">Status:</dt>
                            <dd><span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $payroll->status === 'Paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">{{ $payroll->status }}</span></dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Earnings & Deductions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Earnings -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase bg-primary px-3 py-2 rounded-t-md">Earnings</h3>
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-border">
                            <tr>
                                <td class="py-2">Basic Salary</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->basic_salary, 2) }}</td>
                            </tr>
                            @if($payroll->overtime_pay > 0)
                            <tr>
                                <td class="py-2">Overtime ({{ $payroll->overtime_hours ?? 0 }} hrs)</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->overtime_pay, 2) }}</td>
                            </tr>
                            @endif
                            @if($payroll->allowances > 0)
                            <tr>
                                <td class="py-2">Allowances</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->allowances, 2) }}</td>
                            </tr>
                            @endif
                            @if($payroll->bonus > 0)
                            <tr>
                                <td class="py-2">Bonus</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->bonus, 2) }}</td>
                            </tr>
                            @endif
                            @if($payroll->commission > 0)
                            <tr>
                                <td class="py-2">Commission</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->commission, 2) }}</td>
                            </tr>
                            @endif
                            @if($payroll->other_earnings > 0)
                            <tr>
                                <td class="py-2">Other Earnings</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->other_earnings, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="border-t-2 border-border font-semibold">
                                <td class="py-2">Gross Pay</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->gross_pay, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Deductions -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase bg-red-600 px-3 py-2 rounded-t-md">Deductions</h3>
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-border">
                            <tr>
                                <td class="py-2">PAYE (Income Tax)</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->paye, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2">NIS (3%)</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->nis, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2">NHT (2%)</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->nht, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2">Education Tax</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->education_tax, 2) }}</td>
                            </tr>
                            @if($payroll->loan_deduction > 0)
                            <tr>
                                <td class="py-2">Loan Repayment</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->loan_deduction, 2) }}</td>
                            </tr>
                            @endif
                            @if($payroll->other_deductions > 0)
                            <tr>
                                <td class="py-2">Other Deductions</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->other_deductions, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="border-t-2 border-border font-semibold">
                                <td class="py-2">Total Deductions</td>
                                <td class="py-2 text-right font-mono">{{ number_format($payroll->total_deductions, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Net Pay -->
            <div class="bg-emerald-50 border-2 border-emerald-500 rounded-lg p-6 text-center mb-6">
                <p class="text-sm font-semibold text-emerald-700 uppercase">Net Pay</p>
                <p class="text-3xl font-bold text-emerald-600 font-mono">JMD {{ number_format($payroll->net_pay, 2) }}</p>
            </div>

            <!-- Statutory Summary -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-semibold text-amber-800 mb-3">Jamaican Statutory Contributions Summary</h4>
                <div class="grid grid-cols-5 gap-4 text-center text-sm">
                    <div>
                        <p class="text-amber-700">NIS</p>
                        <p class="font-semibold text-amber-900">{{ number_format($payroll->nis, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-amber-700">NHT</p>
                        <p class="font-semibold text-amber-900">{{ number_format($payroll->nht, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-amber-700">Ed. Tax</p>
                        <p class="font-semibold text-amber-900">{{ number_format($payroll->education_tax, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-amber-700">PAYE</p>
                        <p class="font-semibold text-amber-900">{{ number_format($payroll->paye, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-amber-700">Total</p>
                        <p class="font-semibold text-amber-900">{{ number_format($payroll->nis + $payroll->nht + $payroll->education_tax + $payroll->paye, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-border pt-4 text-xs text-muted-foreground">
                <p class="text-center mb-2">This is a computer-generated payslip and does not require a signature.</p>
                <div class="flex justify-between">
                    <div>
                        <strong>Employer Contributions:</strong>
                        NIS: {{ number_format($payroll->employer_nis ?? ($payroll->nis * 1), 2) }} |
                        NHT: {{ number_format($payroll->employer_nht ?? ($payroll->gross_pay * 0.03), 2) }} |
                        HEART: {{ number_format($payroll->heart ?? ($payroll->gross_pay * 0.03), 2) }}
                    </div>
                    <div class="text-right">
                        Generated: {{ now()->format('M d, Y h:i A') }}<br>
                        Reference: {{ $payroll->id }}-{{ $payroll->pay_period_end->format('Ymd') }}
                    </div>
                </div>
                <p class="text-center mt-4 text-[10px] text-gray-400 uppercase tracking-wider">Confidential - For Employee Use Only</p>
            </div>
        </div>
    </div>
</x-app-layout>
