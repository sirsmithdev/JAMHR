<x-app-layout>
    @section('title', 'Dashboard')

    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl text-foreground font-serif">Good Morning, {{ Auth::user()->name }}</h1>
                <p class="text-muted-foreground mt-2">Here's your HR overview for <span class="font-semibold text-primary">{{ now()->format('F Y') }}</span>.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('payroll.index') }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    Download Reports
                </a>
                <a href="{{ route('payroll.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                    Run Payroll
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-muted-foreground">Total Employees</span>
                    <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ $totalEmployees }}</div>
                <p class="text-xs text-emerald-600 flex items-center mt-1">Active workforce</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-muted-foreground">Pending Leave</span>
                    <svg class="h-4 w-4 text-secondary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ $pendingLeaveRequests }}</div>
                <p class="text-xs text-muted-foreground mt-1">Requests to review</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-muted-foreground">Next Payroll</span>
                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ now()->addDays(10)->format('M d') }}</div>
                <p class="text-xs text-muted-foreground mt-1">Est: JMD $1.2M</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-muted-foreground">Open Incidents</span>
                    <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ $openIncidents }}</div>
                <p class="text-xs text-muted-foreground mt-1">Requires attention</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payroll Chart Placeholder -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <h3 class="font-serif text-lg font-semibold mb-2">Payroll History (JMD)</h3>
                <p class="text-sm text-muted-foreground mb-6">Gross payroll expenditure for the last 6 months</p>
                <div class="h-[250px] flex items-center justify-center bg-muted/20 rounded-lg">
                    <p class="text-muted-foreground">Chart will be displayed here with actual payroll data</p>
                </div>
            </div>

            <!-- Compliance / Quick Actions -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif text-lg font-semibold mb-4">Upcoming Deadlines</h3>
                    <div class="space-y-4">
                        @foreach($complianceAlerts as $alert)
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-muted/30 border border-muted">
                            <svg class="h-5 w-5 mt-0.5 {{ $alert['type'] === 'urgent' ? 'text-red-500' : 'text-primary' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <div>
                                <p class="font-medium text-sm">{{ $alert['title'] }}</p>
                                <p class="text-xs text-muted-foreground">Due: {{ $alert['date'] }}</p>
                            </div>
                        </div>
                        @endforeach
                        <a href="{{ route('compliance.index') }}" class="block w-full text-center text-primary text-sm py-2 hover:underline">
                            View Compliance Calendar
                        </a>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-primary to-sidebar rounded-lg shadow-md p-6 text-white">
                    <h3 class="font-serif text-lg font-semibold mb-2">Quick Tax Calc</h3>
                    <p class="text-white/80 text-sm mb-4">Estimate deductions for new hires</p>
                    <a href="{{ route('payroll.calculator') }}" class="block w-full text-center bg-secondary text-secondary-foreground font-medium py-2 rounded-md hover:bg-secondary/90 transition-colors">
                        Open Calculator
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
