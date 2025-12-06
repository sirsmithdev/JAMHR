<x-app-layout>
    @section('title', 'Dashboard')

    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                @php
                    $hour = now()->hour;
                    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
                @endphp
                <h1 class="text-3xl md:text-4xl text-foreground font-serif">{{ $greeting }}, {{ Auth::user()->name }}</h1>
                <p class="text-muted-foreground mt-2">Here's your HR overview for <span class="font-semibold text-primary">{{ now()->format('F Y') }}</span>.</p>
            </div>
            @can('payroll.view')
            <div class="flex gap-3">
                <a href="{{ route('export.payroll.csv') }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Reports
                </a>
                @can('payroll.create')
                <a href="{{ route('payroll.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                    Run Payroll
                </a>
                @endcan
            </div>
            @endcan
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
                <p class="text-xs text-emerald-600 flex items-center mt-1">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1"></span>
                    {{ $activeEmployees ?? $totalEmployees }} active
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-muted-foreground">Pending Leave</span>
                    <svg class="h-4 w-4 text-secondary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ $pendingLeaveRequests }}</div>
                <a href="{{ route('leave.index') }}" class="text-xs text-primary hover:underline mt-1 inline-block">Review requests &rarr;</a>
            </div>

            @if(isset($attendanceRate))
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-muted-foreground">Today's Attendance</span>
                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ $attendanceRate }}%</div>
                <p class="text-xs text-muted-foreground mt-1">{{ $todayAttendance }}/{{ $expectedAttendance }} checked in</p>
            </div>
            @else
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-muted-foreground">Next Payroll</span>
                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ now()->endOfMonth()->format('M d') }}</div>
                <p class="text-xs text-muted-foreground mt-1">End of month</p>
            </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-muted-foreground">Open Incidents</span>
                    <svg class="h-4 w-4 {{ $openIncidents > 0 ? 'text-amber-500' : 'text-emerald-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ $openIncidents }}</div>
                <a href="{{ route('incidents.index') }}" class="text-xs text-primary hover:underline mt-1 inline-block">View incidents &rarr;</a>
            </div>
        </div>

        @if(Auth::user()->isAdmin() || Auth::user()->isHR())
        <!-- Admin/HR Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Action Items -->
            @if(isset($actionItems) && $actionItems->count() > 0)
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <h3 class="font-serif text-lg font-semibold mb-4 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    Action Items
                </h3>
                <div class="space-y-3">
                    @foreach($actionItems as $item)
                    <a href="{{ $item['url'] }}" class="flex items-center justify-between p-4 rounded-lg border border-{{ $item['priority'] === 'high' ? 'red' : 'amber' }}-200 bg-{{ $item['priority'] === 'high' ? 'red' : 'amber' }}-50 hover:bg-{{ $item['priority'] === 'high' ? 'red' : 'amber' }}-100 transition-colors">
                        <div class="flex items-center gap-3">
                            @if($item['type'] === 'leave')
                            <svg class="h-5 w-5 text-{{ $item['priority'] === 'high' ? 'red' : 'amber' }}-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            @elseif($item['type'] === 'loan')
                            <svg class="h-5 w-5 text-{{ $item['priority'] === 'high' ? 'red' : 'amber' }}-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @else
                            <svg class="h-5 w-5 text-{{ $item['priority'] === 'high' ? 'red' : 'amber' }}-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ $item['title'] }}</p>
                                <p class="text-sm text-gray-600">{{ $item['count'] }} item(s) need attention</p>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            <!-- Payroll Stats -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <h3 class="font-serif text-lg font-semibold mb-2">Payroll Overview</h3>
                <p class="text-sm text-muted-foreground mb-6">Monthly payroll expenditure</p>

                @if(isset($payrollThisMonth))
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="p-4 bg-emerald-50 rounded-lg">
                        <p class="text-sm text-emerald-600 font-medium">This Month</p>
                        <p class="text-2xl font-bold text-emerald-700">JMD {{ number_format($payrollThisMonth, 2) }}</p>
                        @if(isset($payrollChange) && $payrollChange != 0)
                        <p class="text-xs {{ $payrollChange > 0 ? 'text-red-600' : 'text-emerald-600' }} mt-1">
                            {{ $payrollChange > 0 ? '+' : '' }}{{ $payrollChange }}% vs last month
                        </p>
                        @endif
                    </div>
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-600 font-medium">Pending Items</p>
                        <div class="flex items-baseline gap-4">
                            <div>
                                <p class="text-2xl font-bold text-blue-700">{{ $pendingLoans ?? 0 }}</p>
                                <p class="text-xs text-blue-600">Loan Apps</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-blue-700">{{ $pendingAppraisals ?? 0 }}</p>
                                <p class="text-xs text-blue-600">Reviews</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Department Stats -->
                @if(isset($departmentStats) && $departmentStats->count() > 0)
                <h4 class="font-medium text-sm text-gray-700 mb-3">Employees by Department</h4>
                <div class="space-y-2">
                    @foreach($departmentStats as $dept)
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 w-32 truncate">{{ $dept->department ?? 'Unassigned' }}</span>
                        <div class="flex-1 bg-gray-100 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: {{ ($dept->count / $totalEmployees) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-700 w-8 text-right">{{ $dept->count }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Compliance Alerts -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif text-lg font-semibold mb-4">Upcoming Deadlines</h3>
                    <div class="space-y-4">
                        @forelse($complianceAlerts as $alert)
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-{{ $alert['type'] === 'urgent' ? 'red' : ($alert['type'] === 'warning' ? 'amber' : 'blue') }}-50 border border-{{ $alert['type'] === 'urgent' ? 'red' : ($alert['type'] === 'warning' ? 'amber' : 'blue') }}-200">
                            <svg class="h-5 w-5 mt-0.5 text-{{ $alert['type'] === 'urgent' ? 'red' : ($alert['type'] === 'warning' ? 'amber' : 'blue') }}-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <div>
                                <p class="font-medium text-sm">{{ $alert['title'] }}</p>
                                <p class="text-xs text-muted-foreground">Due: {{ $alert['date'] }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-muted-foreground text-center py-4">No upcoming deadlines</p>
                        @endforelse
                        <a href="{{ route('compliance.index') }}" class="block w-full text-center text-primary text-sm py-2 hover:underline">
                            View Compliance Calendar
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-primary to-sidebar rounded-lg shadow-md p-6 text-white">
                    <h3 class="font-serif text-lg font-semibold mb-2">Quick Actions</h3>
                    <div class="space-y-2 mt-4">
                        <a href="{{ route('employees.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-md bg-white/10 hover:bg-white/20 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                            </svg>
                            <span class="text-sm">Add Employee</span>
                        </a>
                        <a href="{{ route('payroll.calculator') }}" class="flex items-center gap-2 px-3 py-2 rounded-md bg-white/10 hover:bg-white/20 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z" />
                            </svg>
                            <span class="text-sm">Tax Calculator</span>
                        </a>
                        <a href="{{ route('hiring.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md bg-white/10 hover:bg-white/20 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                            </svg>
                            <span class="text-sm">Hiring Pipeline</span>
                        </a>
                    </div>
                </div>

                <!-- Hiring Stats -->
                @if(isset($openPositions))
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif text-lg font-semibold mb-4">Hiring Pipeline</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-purple-50 rounded-lg">
                            <p class="text-2xl font-bold text-purple-700">{{ $openPositions }}</p>
                            <p class="text-xs text-purple-600">Open Positions</p>
                        </div>
                        <div class="text-center p-3 bg-indigo-50 rounded-lg">
                            <p class="text-2xl font-bold text-indigo-700">{{ $newApplications ?? 0 }}</p>
                            <p class="text-xs text-indigo-600">New This Week</p>
                        </div>
                    </div>
                    <a href="{{ route('hiring.applications') }}" class="block w-full text-center text-primary text-sm py-2 mt-4 hover:underline">
                        View Applications &rarr;
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Activities -->
        @if(isset($recentActivities) && $recentActivities->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-serif text-lg font-semibold mb-4">Recent Activity</h3>
            <div class="space-y-4">
                @foreach($recentActivities as $activity)
                <div class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                        @if($activity['type'] === 'leave') bg-blue-100 text-blue-600
                        @elseif($activity['type'] === 'incident') bg-red-100 text-red-600
                        @else bg-emerald-100 text-emerald-600
                        @endif">
                        @if($activity['icon'] === 'calendar')
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        @elseif($activity['icon'] === 'alert')
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        @else
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                        </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">{{ $activity['message'] }}</p>
                        <p class="text-xs text-muted-foreground">{{ $activity['time'] }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($activity['status'] === 'approved') bg-emerald-100 text-emerald-700
                        @elseif($activity['status'] === 'pending') bg-amber-100 text-amber-700
                        @elseif($activity['status'] === 'rejected') bg-red-100 text-red-700
                        @else bg-gray-100 text-gray-700
                        @endif">
                        {{ ucfirst($activity['status']) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @elseif(Auth::user()->isManager())
        <!-- Manager Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Team Overview -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <h3 class="font-serif text-lg font-semibold mb-4">My Team</h3>
                @if(isset($teamMembers) && $teamMembers->count() > 0)
                <div class="grid gap-4">
                    @foreach($teamMembers->take(6) as $member)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-primary font-semibold">{{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium">{{ $member->full_name }}</p>
                                <p class="text-sm text-muted-foreground">{{ $member->job_title }}</p>
                            </div>
                        </div>
                        <a href="{{ route('employees.show', $member) }}" class="text-primary text-sm hover:underline">View</a>
                    </div>
                    @endforeach
                </div>
                @if($teamMembers->count() > 6)
                <a href="{{ route('employees.index') }}" class="block text-center text-primary text-sm py-3 hover:underline">
                    View all {{ $teamMembers->count() }} team members &rarr;
                </a>
                @endif
                @else
                <p class="text-muted-foreground text-center py-8">No team members assigned</p>
                @endif
            </div>

            <!-- Team Tasks -->
            <div class="space-y-6">
                <!-- Pending Leave -->
                @if(isset($teamLeaveRequests) && $teamLeaveRequests->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif text-lg font-semibold mb-4">Team Leave Requests</h3>
                    <div class="space-y-3">
                        @foreach($teamLeaveRequests->take(5) as $leave)
                        <div class="p-3 bg-amber-50 rounded-lg border border-amber-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-sm">{{ $leave->employee->full_name }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $leave->type }} - {{ $leave->start_date->format('M d') }} to {{ $leave->end_date->format('M d') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs bg-amber-200 text-amber-800 rounded">Pending</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('leave.index') }}" class="block text-center text-primary text-sm py-3 hover:underline">
                        Review all requests &rarr;
                    </a>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-primary to-sidebar rounded-lg shadow-md p-6 text-white">
                    <h3 class="font-serif text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('time.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md bg-white/10 hover:bg-white/20 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm">Team Timesheets</span>
                        </a>
                        <a href="{{ route('performance.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-md bg-white/10 hover:bg-white/20 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                            <span class="text-sm">Start Review</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @else
        <!-- Employee Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Leave Balance -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-serif text-lg font-semibold mb-4">My Leave Balance</h3>
                @if(isset($myLeaveBalance) && $myLeaveBalance)
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-blue-700">Vacation Days</span>
                            <span class="text-lg font-bold text-blue-800">{{ $myLeaveBalance['vacation']['remaining'] }}</span>
                        </div>
                        <div class="w-full bg-blue-200 rounded-full h-2">
                            @php
                                $vacationPercent = $myLeaveBalance['vacation']['total'] > 0
                                    ? (($myLeaveBalance['vacation']['total'] - $myLeaveBalance['vacation']['used']) / $myLeaveBalance['vacation']['total']) * 100
                                    : 0;
                            @endphp
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $vacationPercent }}%"></div>
                        </div>
                        <p class="text-xs text-blue-600 mt-1">{{ $myLeaveBalance['vacation']['used'] }} of {{ $myLeaveBalance['vacation']['total'] }} used</p>
                    </div>
                    <div class="p-4 bg-emerald-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-emerald-700">Sick Days</span>
                            <span class="text-lg font-bold text-emerald-800">{{ $myLeaveBalance['sick']['remaining'] }}</span>
                        </div>
                        <div class="w-full bg-emerald-200 rounded-full h-2">
                            @php
                                $sickPercent = $myLeaveBalance['sick']['total'] > 0
                                    ? (($myLeaveBalance['sick']['total'] - $myLeaveBalance['sick']['used']) / $myLeaveBalance['sick']['total']) * 100
                                    : 0;
                            @endphp
                            <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $sickPercent }}%"></div>
                        </div>
                        <p class="text-xs text-emerald-600 mt-1">{{ $myLeaveBalance['sick']['used'] }} of {{ $myLeaveBalance['sick']['total'] }} used</p>
                    </div>
                </div>
                <a href="{{ route('leave.create') }}" class="block w-full text-center bg-primary text-white font-medium py-2 rounded-md mt-4 hover:bg-primary/90 transition-colors">
                    Request Leave
                </a>
                @else
                <p class="text-muted-foreground text-center py-4">No leave balance data available</p>
                @endif
            </div>

            <!-- Recent Payslips -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-serif text-lg font-semibold mb-4">Recent Payslips</h3>
                @if(isset($myPayslips) && $myPayslips->count() > 0)
                <div class="space-y-3">
                    @foreach($myPayslips as $payslip)
                    <a href="{{ route('payslips.view', $payslip) }}" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium">{{ $payslip->period_end->format('F Y') }}</p>
                                <p class="text-xs text-muted-foreground">Period: {{ $payslip->period_start->format('M d') }} - {{ $payslip->period_end->format('M d') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-emerald-600">JMD {{ number_format($payslip->net_pay, 2) }}</p>
                                <p class="text-xs text-muted-foreground">Net Pay</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-muted-foreground text-center py-8">No payslips available</p>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="space-y-6">
                <!-- Pending Leave -->
                @if(isset($myPendingLeaves) && $myPendingLeaves->count() > 0)
                <div class="bg-amber-50 rounded-lg p-6 border border-amber-200">
                    <h3 class="font-serif text-lg font-semibold mb-2 text-amber-800">Pending Requests</h3>
                    <p class="text-sm text-amber-700 mb-4">You have {{ $myPendingLeaves->count() }} leave request(s) pending approval.</p>
                    <a href="{{ route('leave.index') }}" class="text-amber-700 text-sm font-medium hover:underline">View status &rarr;</a>
                </div>
                @endif

                <div class="bg-gradient-to-br from-primary to-sidebar rounded-lg shadow-md p-6 text-white">
                    <h3 class="font-serif text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('time.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md bg-white/10 hover:bg-white/20 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm">Log Time</span>
                        </a>
                        <a href="{{ route('leave.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-md bg-white/10 hover:bg-white/20 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <span class="text-sm">Request Leave</span>
                        </a>
                        <a href="{{ route('documents.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md bg-white/10 hover:bg-white/20 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span class="text-sm">My Documents</span>
                        </a>
                    </div>
                </div>

                <!-- My Appraisals -->
                @if(isset($myAppraisals) && $myAppraisals->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif text-lg font-semibold mb-4">Performance Reviews</h3>
                    <div class="space-y-3">
                        @foreach($myAppraisals as $appraisal)
                        <a href="{{ route('performance.show', $appraisal) }}" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex justify-between items-center">
                                <p class="font-medium">{{ $appraisal->review_period }}</p>
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($appraisal->status === 'completed') bg-emerald-100 text-emerald-700
                                    @elseif($appraisal->status === 'pending') bg-amber-100 text-amber-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst($appraisal->status) }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
