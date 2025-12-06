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
                            <a href="{{ route('payroll.show', $payroll) }}" class="text-primary hover:underline text-sm">View</a>
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
