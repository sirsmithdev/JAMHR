<x-app-layout>
    @section('title', 'Payroll')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Payroll & Tax</h1>
                <p class="text-muted-foreground mt-1">Process payroll and manage statutory deductions for Jamaica.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('payroll.calculator') }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    Tax Calculator
                </a>
                <a href="{{ route('payslips.bulk-email') }}" class="inline-flex items-center px-4 py-2 border border-emerald-300 rounded-md text-sm font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    Bulk Send Payslips
                </a>
                <a href="{{ route('payroll.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    New Payroll
                </a>
            </div>
        </div>

        <!-- Tax Rates Reference -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-primary">
                <h3 class="font-medium text-sm text-muted-foreground">NHT</h3>
                <p class="text-lg font-bold text-foreground">{{ $taxRates['nht']['employee'] }}% / {{ $taxRates['nht']['employer'] }}%</p>
                <p class="text-xs text-muted-foreground">Employee / Employer</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-secondary">
                <h3 class="font-medium text-sm text-muted-foreground">NIS</h3>
                <p class="text-lg font-bold text-foreground">{{ $taxRates['nis']['employee'] }}% / {{ $taxRates['nis']['employer'] }}%</p>
                <p class="text-xs text-muted-foreground">Cap: JMD ${{ number_format($taxRates['nis']['annual_cap']) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-emerald-500">
                <h3 class="font-medium text-sm text-muted-foreground">Ed Tax</h3>
                <p class="text-lg font-bold text-foreground">{{ $taxRates['ed_tax']['employee'] }}% / {{ $taxRates['ed_tax']['employer'] }}%</p>
                <p class="text-xs text-muted-foreground">Employee / Employer</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-amber-500">
                <h3 class="font-medium text-sm text-muted-foreground">HEART</h3>
                <p class="text-lg font-bold text-foreground">{{ $taxRates['heart']['employer'] }}%</p>
                <p class="text-xs text-muted-foreground">Employer Only</p>
            </div>
        </div>

        <!-- Payroll List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="font-serif font-semibold">Recent Payrolls</h3>
            </div>
            <table class="w-full">
                <thead class="bg-muted/30">
                    <tr>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Employee</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Period</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-muted-foreground">Gross Pay</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-muted-foreground">Deductions</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-muted-foreground">Net Pay</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Status</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-muted-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($payrolls as $payroll)
                    <tr class="hover:bg-muted/5">
                        <td class="px-6 py-4 font-medium">{{ $payroll->employee->full_name }}</td>
                        <td class="px-6 py-4 text-sm text-muted-foreground">{{ $payroll->period_start->format('M d') }} - {{ $payroll->period_end->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right font-medium">JMD {{ number_format($payroll->gross_pay, 2) }}</td>
                        <td class="px-6 py-4 text-right text-sm text-red-600">-{{ number_format($payroll->total_employee_deductions, 2) }}</td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600">JMD {{ number_format($payroll->net_pay, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $payroll->status_badge_class }}">
                                {{ ucfirst($payroll->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('payroll.show', $payroll) }}" class="text-primary hover:text-primary/80" title="View Details">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.64 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.64 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                                @if(in_array($payroll->status, ['Finalized', 'Paid']))
                                <a href="{{ route('payslips.download', $payroll) }}" class="text-primary hover:text-primary/80" title="Download Payslip">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-muted-foreground">
                            No payroll records found. <a href="{{ route('payroll.create') }}" class="text-primary hover:underline">Create your first payroll</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $payrolls->links() }}
    </div>
</x-app-layout>
