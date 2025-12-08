<x-app-layout>
    @section('title', 'Benefits & Compliance Reports')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Reports & Compliance</h1>
                <p class="text-muted-foreground mt-1">Generate reports for benefits, statutory contributions, and compliance.</p>
            </div>
        </div>

        <!-- Quick Export -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <div>
                        <p class="font-medium text-amber-900">SO 2 Form - Employer's Annual Return</p>
                        <p class="text-sm text-amber-700">Export statutory contributions data for Tax Administration Jamaica (TAJ)</p>
                    </div>
                </div>
                <a href="{{ route('reports.export-so2', ['year' => now()->year]) }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export {{ now()->year }} SO 2
                </a>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Benefits Cost Analysis -->
            <a href="{{ route('reports.benefits-cost') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-12 rounded-lg bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">Benefits Cost Analysis</h3>
                        <p class="text-sm text-muted-foreground">Employee & employer benefit costs by type and department</p>
                    </div>
                </div>
            </a>

            <!-- Statutory Contributions -->
            <a href="{{ route('reports.statutory-contributions') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">Statutory Contributions</h3>
                        <p class="text-sm text-muted-foreground">NIS, NHT, Education Tax, HEART, PAYE quarterly reports</p>
                    </div>
                </div>
            </a>

            <!-- Staff Loans Report -->
            <a href="{{ route('reports.staff-loans') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-12 rounded-lg bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                        <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">Staff Loans Report</h3>
                        <p class="text-sm text-muted-foreground">Active loans, balances, and taxable benefit summary</p>
                    </div>
                </div>
            </a>

            <!-- Allowances Report -->
            <a href="{{ route('reports.allowances') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">Allowances Report</h3>
                        <p class="text-sm text-muted-foreground">Employee allowances by category and taxable amounts</p>
                    </div>
                </div>
            </a>

            <!-- Health Insurance Utilization -->
            <a href="{{ route('reports.health-insurance') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-12 rounded-lg bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition-colors">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">Health Insurance</h3>
                        <p class="text-sm text-muted-foreground">Claims utilization, approval rates, and costs by type</p>
                    </div>
                </div>
            </a>

            <!-- Pension Report -->
            <a href="{{ route('reports.pension') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-12 rounded-lg bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">Pension Report</h3>
                        <p class="text-sm text-muted-foreground">Account balances, contributions, and vesting status</p>
                    </div>
                </div>
            </a>

            <!-- Leave Utilization -->
            <a href="{{ route('reports.leave-utilization') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-12 rounded-lg bg-cyan-100 flex items-center justify-center group-hover:bg-cyan-200 transition-colors">
                        <svg class="h-6 w-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">Leave Utilization</h3>
                        <p class="text-sm text-muted-foreground">Leave balances, usage rates by type and department</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Year Selection for Reports -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="font-semibold mb-4">Generate Historical Reports</h3>
            <form action="" method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Report Type</label>
                    <select name="report" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <option value="statutory-contributions">Statutory Contributions</option>
                        <option value="benefits-cost">Benefits Cost Analysis</option>
                        <option value="staff-loans">Staff Loans</option>
                        <option value="health-insurance">Health Insurance</option>
                        <option value="pension">Pension</option>
                        <option value="leave-utilization">Leave Utilization</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-muted-foreground mb-1">Year</label>
                    <select name="year" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                    Generate Report
                </button>
            </form>
        </div>

        <!-- Jamaica Compliance Reminders -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h2 class="font-serif font-semibold">Jamaica Compliance Calendar</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border border-border rounded-lg p-4">
                        <h4 class="font-medium mb-3 flex items-center gap-2">
                            <span class="h-2 w-2 bg-blue-500 rounded-full"></span>
                            Monthly Submissions
                        </h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li>NIS/NHT contributions - Due by 14th of following month</li>
                            <li>PAYE remittance - Due by 14th of following month</li>
                            <li>Education Tax - Due by 14th of following month</li>
                        </ul>
                    </div>
                    <div class="border border-border rounded-lg p-4">
                        <h4 class="font-medium mb-3 flex items-center gap-2">
                            <span class="h-2 w-2 bg-amber-500 rounded-full"></span>
                            Quarterly Submissions
                        </h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li>HEART/NTA contributions - Due quarterly</li>
                            <li>NIS Form C1 - Quarterly employer returns</li>
                        </ul>
                    </div>
                    <div class="border border-border rounded-lg p-4">
                        <h4 class="font-medium mb-3 flex items-center gap-2">
                            <span class="h-2 w-2 bg-red-500 rounded-full"></span>
                            Annual Submissions
                        </h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li>SO 2 Form (Employer's Annual Return) - Due by March 15th</li>
                            <li>Annual NIS Certificate of Compliance</li>
                            <li>NHT Annual Employer's Declaration</li>
                        </ul>
                    </div>
                    <div class="border border-border rounded-lg p-4">
                        <h4 class="font-medium mb-3 flex items-center gap-2">
                            <span class="h-2 w-2 bg-emerald-500 rounded-full"></span>
                            Records Retention
                        </h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li>Payroll records - Minimum 7 years</li>
                            <li>Employee contracts - Duration of employment + 7 years</li>
                            <li>Tax records - Minimum 7 years</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
