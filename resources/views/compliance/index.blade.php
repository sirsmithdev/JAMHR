<x-app-layout>
    @section('title', 'Labor Laws & Compliance')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Labor Laws & Compliance</h1>
                <p class="text-muted-foreground mt-1">Stay compliant with Jamaican Labor Laws and Statutory Regulations.</p>
            </div>
            <div class="flex gap-2">
                <a href="https://www.mlss.gov.jm/" target="_blank" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                    Ministry of Labour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">

                <!-- Minimum Wage Alert -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <div>
                            <h4 class="font-semibold text-amber-800">Minimum Wage Update</h4>
                            <p class="text-sm text-amber-700 mt-1">{{ $laborLaws['minimum_wage'] }}. Please update payroll settings.</p>
                        </div>
                    </div>
                </div>

                <!-- Statutory Contributions Guide -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                        </svg>
                        <h3 class="font-serif font-semibold">Statutory Contributions Guide (2025)</h3>
                    </div>
                    <p class="text-sm text-muted-foreground mb-4">Current rates and caps for payroll processing</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 rounded-lg border bg-slate-50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-foreground">NHT</h4>
                                <span class="text-xs px-2 py-1 bg-primary/10 text-primary rounded">Mandatory</span>
                            </div>
                            <p class="text-sm text-muted-foreground mb-3">National Housing Trust</p>
                            <ul class="text-sm space-y-1">
                                <li class="flex justify-between"><span>Employee:</span> <span class="font-medium">{{ $taxRates['nht']['employee'] }}%</span></li>
                                <li class="flex justify-between"><span>Employer:</span> <span class="font-medium">{{ $taxRates['nht']['employer'] }}%</span></li>
                            </ul>
                        </div>

                        <div class="p-4 rounded-lg border bg-slate-50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-foreground">NIS</h4>
                                <span class="text-xs px-2 py-1 bg-amber-100 text-amber-800 rounded">Capped</span>
                            </div>
                            <p class="text-sm text-muted-foreground mb-3">National Insurance Scheme</p>
                            <ul class="text-sm space-y-1">
                                <li class="flex justify-between"><span>Employee:</span> <span class="font-medium">{{ $taxRates['nis']['employee'] }}%</span></li>
                                <li class="flex justify-between"><span>Employer:</span> <span class="font-medium">{{ $taxRates['nis']['employer'] }}%</span></li>
                                <li class="text-xs text-muted-foreground mt-2 pt-2 border-t">Cap: JMD ${{ number_format($taxRates['nis']['annual_cap']) }} / year</li>
                            </ul>
                        </div>

                        <div class="p-4 rounded-lg border bg-slate-50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-foreground">Ed Tax</h4>
                                <span class="text-xs px-2 py-1 bg-primary/10 text-primary rounded">Mandatory</span>
                            </div>
                            <p class="text-sm text-muted-foreground mb-3">Education Tax</p>
                            <ul class="text-sm space-y-1">
                                <li class="flex justify-between"><span>Employee:</span> <span class="font-medium">{{ $taxRates['ed_tax']['employee'] }}%</span></li>
                                <li class="flex justify-between"><span>Employer:</span> <span class="font-medium">{{ $taxRates['ed_tax']['employer'] }}%</span></li>
                            </ul>
                        </div>

                        <div class="p-4 rounded-lg border bg-slate-50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-foreground">HEART</h4>
                                <span class="text-xs px-2 py-1 bg-slate-200 text-slate-700 rounded">Employer Only</span>
                            </div>
                            <p class="text-sm text-muted-foreground mb-3">HEART Trust / NTA</p>
                            <ul class="text-sm space-y-1">
                                <li class="flex justify-between"><span>Employee:</span> <span class="font-medium">{{ $taxRates['heart']['employee'] }}%</span></li>
                                <li class="flex justify-between"><span>Employer:</span> <span class="font-medium">{{ $taxRates['heart']['employer'] }}%</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Labor Laws Accordion -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-2">Quick Reference: Employment Rights</h3>
                    <p class="text-sm text-muted-foreground mb-4">Key provisions from the Employment (Termination and Redundancy Payments) Act</p>

                    <div class="space-y-4">
                        <details class="group">
                            <summary class="flex justify-between items-center cursor-pointer py-3 border-b border-border">
                                <span class="font-medium">Notice Period Requirements</span>
                                <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </summary>
                            <div class="text-sm text-muted-foreground py-4 space-y-2">
                                <p>Employees are entitled to notice based on length of service:</p>
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($laborLaws['notice_periods'] as $period)
                                    <li>{{ $period['tenure'] }}: {{ $period['notice'] }} notice</li>
                                    @endforeach
                                </ul>
                            </div>
                        </details>

                        <details class="group">
                            <summary class="flex justify-between items-center cursor-pointer py-3 border-b border-border">
                                <span class="font-medium">Sick Leave Entitlement</span>
                                <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </summary>
                            <div class="text-sm text-muted-foreground py-4">
                                {{ $laborLaws['sick_leave'] }}
                            </div>
                        </details>

                        <details class="group">
                            <summary class="flex justify-between items-center cursor-pointer py-3 border-b border-border">
                                <span class="font-medium">Vacation Leave</span>
                                <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </summary>
                            <div class="text-sm text-muted-foreground py-4">
                                {{ $laborLaws['vacation_leave'] }}
                            </div>
                        </details>

                        <details class="group">
                            <summary class="flex justify-between items-center cursor-pointer py-3 border-b border-border">
                                <span class="font-medium">Maternity Leave</span>
                                <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </summary>
                            <div class="text-sm text-muted-foreground py-4">
                                {{ $laborLaws['maternity_leave'] }}
                            </div>
                        </details>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-serif font-semibold mb-4">Downloadable Forms</h3>
                    <div class="space-y-2">
                        @foreach($forms as $form)
                        <button class="w-full flex items-start gap-3 p-3 text-left border border-transparent hover:border-slate-200 hover:bg-slate-50 rounded-lg transition-colors">
                            <svg class="h-5 w-5 text-primary mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <div>
                                <div class="font-medium text-foreground">{{ $form['name'] }}</div>
                                <div class="text-xs text-muted-foreground">{{ $form['description'] }}</div>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-6">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-emerald-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="font-semibold text-emerald-900">Compliance Status</h4>
                            <p class="text-sm text-emerald-800 mt-1">
                                Your organization is currently meeting all statutory filing requirements for the current fiscal year.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
