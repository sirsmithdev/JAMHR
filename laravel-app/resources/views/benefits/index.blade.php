<x-app-layout>
    @section('title', 'Benefits Management')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Benefits Management</h1>
                <p class="text-muted-foreground mt-1">Manage employee benefit plans, enrollments, and dependents.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('benefits.enrollment-periods.create') }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    Enrollment Period
                </a>
                <a href="{{ route('benefits.plans.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Plan
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-foreground">{{ $stats['total_plans'] ?? 0 }}</div>
                <div class="text-xs text-muted-foreground">Benefit Plans</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-emerald-600">{{ $stats['active_enrollments'] ?? 0 }}</div>
                <div class="text-xs text-muted-foreground">Active Enrollments</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['pending_enrollments'] ?? 0 }}</div>
                <div class="text-xs text-muted-foreground">Pending Enrollments</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-amber-600">{{ $stats['open_enrollment'] ?? 'No' }}</div>
                <div class="text-xs text-muted-foreground">Open Enrollment</div>
            </div>
        </div>

        <!-- Active Enrollment Period -->
        @if(isset($activeEnrollmentPeriod))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <div>
                    <p class="font-medium text-blue-900">Open Enrollment: {{ $activeEnrollmentPeriod->name }}</p>
                    <p class="text-sm text-blue-700">{{ $activeEnrollmentPeriod->start_date->format('M d') }} - {{ $activeEnrollmentPeriod->end_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Benefit Plans by Type -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach(['health' => 'Health Insurance', 'pension' => 'Pension/Retirement', 'life_insurance' => 'Life Insurance'] as $type => $label)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-border bg-muted/20">
                    <h2 class="font-serif font-semibold">{{ $label }}</h2>
                </div>
                <div class="divide-y divide-border">
                    @forelse($plans->where('type', $type) as $plan)
                    <div class="p-4 hover:bg-muted/5">
                        <div class="flex items-start justify-between">
                            <div>
                                <a href="{{ route('benefits.plans.show', $plan) }}" class="font-medium text-primary hover:underline">{{ $plan->name }}</a>
                                <p class="text-sm text-muted-foreground mt-1">{{ $plan->provider }}</p>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $plan->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="mt-3 flex items-center gap-4 text-sm text-muted-foreground">
                            <span>{{ $plan->enrollments_count ?? 0 }} enrolled</span>
                            <span>JMD {{ number_format($plan->employee_cost_monthly, 2) }}/mo</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-muted-foreground">
                        No {{ strtolower($label) }} plans available.
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>

        <!-- Recent Enrollments -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h2 class="font-serif font-semibold">Recent Enrollments</h2>
                <a href="{{ route('benefits.enrollments.index') }}" class="text-sm text-primary hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-muted/30">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Employee</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Plan</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Coverage</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Enrolled</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($recentEnrollments ?? [] as $enrollment)
                        <tr class="hover:bg-muted/5">
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $enrollment->employee->full_name }}</div>
                                <div class="text-sm text-muted-foreground">{{ $enrollment->employee->department }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $enrollment->benefitPlan->name }}</td>
                            <td class="px-6 py-4">
                                <span class="capitalize">{{ str_replace('_', ' ', $enrollment->coverage_level) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $enrollment->status_badge_class }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-muted-foreground">
                                {{ $enrollment->enrollment_date->format('M d, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-muted-foreground">
                                No recent enrollments.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
