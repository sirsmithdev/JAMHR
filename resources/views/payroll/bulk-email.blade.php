<x-app-layout>
    @section('title', 'Bulk Send Payslips')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('payroll.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Payroll
                </a>
                <h1 class="text-3xl font-serif text-foreground">Bulk Send Payslips</h1>
                <p class="text-muted-foreground mt-1">Send payslips to multiple employees at once via email.</p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('warning'))
        <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-lg">
            {{ session('warning') }}
            @if(session('errors'))
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach(session('errors') as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Pay Period Selection -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-serif font-semibold mb-4">Select Pay Period</h3>
            <form action="{{ route('payslips.bulk-email') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label for="pay_period_end" class="block text-sm font-medium text-foreground mb-1">Pay Period End Date</label>
                    <select name="pay_period_end" id="pay_period_end" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        @foreach($payPeriods as $period)
                        <option value="{{ $period }}" {{ $payPeriodEnd == $period ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($period)->format('M d, Y') }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                    Load Payroll
                </button>
            </form>
        </div>

        <!-- Payslips Table -->
        @if($payrolls->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('payslips.bulk-email.send') }}" method="POST" id="bulk-email-form">
                @csrf
                <input type="hidden" name="pay_period_end" value="{{ $payPeriodEnd }}">

                <div class="p-4 border-b border-border bg-muted/30 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" id="select-all" class="h-4 w-4 rounded border-border text-primary focus:ring-primary/20">
                            <span class="text-sm font-medium">Select All</span>
                        </label>
                        <span class="text-sm text-muted-foreground">
                            {{ $payrolls->count() }} payroll record(s) for {{ \Carbon\Carbon::parse($payPeriodEnd)->format('M d, Y') }}
                        </span>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition-colors">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        Send Selected Payslips
                    </button>
                </div>

                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider w-12"></th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Employee</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Department</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-muted-foreground uppercase tracking-wider">Net Pay</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-muted-foreground uppercase tracking-wider">Sent</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($payrolls as $payroll)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3">
                                @if($payroll->employee->email)
                                <input type="checkbox" name="selected_employees[]" value="{{ $payroll->employee_id }}" class="employee-checkbox h-4 w-4 rounded border-border text-primary focus:ring-primary/20">
                                @else
                                <span class="text-muted-foreground" title="No email address">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-medium">
                                        {{ $payroll->employee->initials }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-foreground">{{ $payroll->employee->full_name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $payroll->employee->employee_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-muted-foreground">
                                {{ $payroll->employee->department }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($payroll->employee->email)
                                <span class="text-foreground">{{ $payroll->employee->email }}</span>
                                @else
                                <span class="text-red-500 text-xs">No email</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-sm">
                                JMD {{ number_format($payroll->net_pay, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $payroll->status === 'Paid' ? 'bg-emerald-100 text-emerald-800' : ($payroll->status === 'Finalized' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $payroll->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($payroll->payslip_sent)
                                <div class="flex items-center justify-center gap-1">
                                    <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-xs text-muted-foreground">{{ $payroll->payslip_sent_at?->format('M d') }}</span>
                                </div>
                                @else
                                <span class="text-muted-foreground">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('payslips.view', $payroll) }}" target="_blank" class="text-primary hover:text-primary/80" title="View PDF">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.64 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.64 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('payslips.download', $payroll) }}" class="text-primary hover:text-primary/80" title="Download PDF">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </a>
                                    @if($payroll->employee->email)
                                    <form action="{{ route('payslips.email', $payroll) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-700" title="Send Email">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-2xl font-bold text-foreground">{{ $payrolls->count() }}</div>
                <div class="text-sm text-muted-foreground">Total Records</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-2xl font-bold text-emerald-600">{{ $payrolls->where('payslip_sent', true)->count() }}</div>
                <div class="text-sm text-muted-foreground">Already Sent</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $payrolls->filter(fn($p) => $p->employee->email && !$p->payslip_sent)->count() }}</div>
                <div class="text-sm text-muted-foreground">Ready to Send</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="text-2xl font-bold text-red-600">{{ $payrolls->filter(fn($p) => !$p->employee->email)->count() }}</div>
                <div class="text-sm text-muted-foreground">Missing Email</div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="h-12 w-12 text-muted-foreground mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" />
            </svg>
            <p class="text-muted-foreground">No finalized or paid payroll records found for the selected period.</p>
            <p class="text-sm text-muted-foreground mt-2">Select a different pay period or process payroll first.</p>
        </div>
        @endif
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.employee-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        document.querySelectorAll('.employee-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const allCheckboxes = document.querySelectorAll('.employee-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.employee-checkbox:checked');
                document.getElementById('select-all').checked = allCheckboxes.length === checkedCheckboxes.length;
            });
        });
    </script>
</x-app-layout>
