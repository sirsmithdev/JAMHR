<x-app-layout>
    @section('title', 'Leave Administration')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Leave Administration</h1>
                <p class="text-muted-foreground mt-1">Manage leave types, balances, holidays, and parental leave records.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('leave-management.holidays.index') }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    Public Holidays
                </a>
                <a href="{{ route('leave-management.parental.index') }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                    Parental Leave
                </a>
                <a href="{{ route('leave-management.types.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Leave Type
                </a>
            </div>
        </div>

        <!-- Jamaica Leave Rules Info -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="h-6 w-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <div>
                    <p class="font-medium text-amber-900">Jamaica Leave Entitlements (Labour Relations and Industrial Disputes Act)</p>
                    <ul class="mt-2 text-sm text-amber-800 space-y-1">
                        <li>Vacation: 10 days (0-10 years service) / 15 days (10+ years)</li>
                        <li>Sick Leave: 10 days per year (with medical certificate)</li>
                        <li>Maternity: 12 weeks (8 weeks paid at full salary)</li>
                        <li>Paternity: 20 working days</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-foreground">{{ $stats['leave_types'] ?? 0 }}</div>
                <div class="text-xs text-muted-foreground">Leave Types</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['pending_requests'] ?? 0 }}</div>
                <div class="text-xs text-muted-foreground">Pending Requests</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-emerald-600">{{ $stats['on_leave_today'] ?? 0 }}</div>
                <div class="text-xs text-muted-foreground">On Leave Today</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['upcoming_holidays'] ?? 0 }}</div>
                <div class="text-xs text-muted-foreground">Upcoming Holidays</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-pink-600">{{ $stats['active_maternity'] ?? 0 }}</div>
                <div class="text-xs text-muted-foreground">Active Maternity</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-indigo-600">{{ $stats['active_paternity'] ?? 0 }}</div>
                <div class="text-xs text-muted-foreground">Active Paternity</div>
            </div>
        </div>

        <!-- Leave Types -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h2 class="font-serif font-semibold">Leave Types</h2>
                <a href="{{ route('leave-management.types.seed-jamaica') }}" class="text-sm text-primary hover:underline">Seed Jamaica Defaults</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-muted/30">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Leave Type</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Default Days</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Paid</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Carry Over</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Doc Required</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($leaveTypes ?? [] as $type)
                        <tr class="hover:bg-muted/5">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-3 w-3 rounded-full" style="background-color: {{ $type->color }}"></div>
                                    <div>
                                        <div class="font-medium">{{ $type->name }}</div>
                                        <div class="text-sm text-muted-foreground">{{ $type->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">{{ $type->default_days }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($type->is_paid)
                                <span class="text-emerald-600">Yes</span>
                                @else
                                <span class="text-muted-foreground">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($type->allow_carry_over)
                                <span>{{ $type->max_carry_over_days }} days</span>
                                @else
                                <span class="text-muted-foreground">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($type->requires_documentation)
                                <span class="text-amber-600">Yes</span>
                                @else
                                <span class="text-muted-foreground">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $type->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $type->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('leave-management.types.edit', $type) }}" class="text-muted-foreground hover:text-foreground">
                                    <svg class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-muted-foreground">
                                No leave types configured. <a href="{{ route('leave-management.types.seed-jamaica') }}" class="text-primary hover:underline">Seed Jamaica defaults</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Leave Balances Summary -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h2 class="font-serif font-semibold">Leave Balances Overview ({{ now()->year }})</h2>
                <a href="{{ route('leave-management.balances.index') }}" class="text-sm text-primary hover:underline">Manage Balances</a>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($balanceSummary ?? [] as $leaveTypeId => $summary)
                    <div class="border border-border rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="h-3 w-3 rounded-full" style="background-color: {{ $summary['color'] ?? '#6b7280' }}"></div>
                            <h3 class="font-medium">{{ $summary['leave_type'] }}</h3>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Total Entitled:</span>
                                <span class="font-medium">{{ $summary['total_entitled'] }} days</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Used:</span>
                                <span class="font-medium text-amber-600">{{ $summary['total_used'] }} days</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Available:</span>
                                <span class="font-medium text-emerald-600">{{ $summary['total_available'] }} days</span>
                            </div>
                            <div class="pt-2 border-t border-border">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary rounded-full h-2" style="width: {{ $summary['utilization_rate'] ?? 0 }}%"></div>
                                </div>
                                <p class="text-xs text-muted-foreground mt-1">{{ number_format($summary['utilization_rate'] ?? 0, 1) }}% utilized</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Upcoming Public Holidays -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h2 class="font-serif font-semibold">Upcoming Public Holidays</h2>
                <a href="{{ route('leave-management.holidays.seed-jamaica', now()->year) }}" class="text-sm text-primary hover:underline">Seed {{ now()->year }} Holidays</a>
            </div>
            <div class="divide-y divide-border">
                @forelse($upcomingHolidays ?? [] as $holiday)
                <div class="p-4 hover:bg-muted/5 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="text-center min-w-[50px]">
                            <div class="text-2xl font-bold text-primary">{{ $holiday->date->format('d') }}</div>
                            <div class="text-xs text-muted-foreground uppercase">{{ $holiday->date->format('M') }}</div>
                        </div>
                        <div>
                            <div class="font-medium">{{ $holiday->name }}</div>
                            <div class="text-sm text-muted-foreground">{{ $holiday->date->format('l') }}</div>
                        </div>
                    </div>
                    @if($holiday->is_observed && $holiday->observed_date)
                    <span class="text-sm text-amber-600">Observed: {{ $holiday->observed_date->format('M d') }}</span>
                    @endif
                </div>
                @empty
                <div class="p-6 text-center text-muted-foreground">
                    No upcoming holidays. <a href="{{ route('leave-management.holidays.seed-jamaica', now()->year) }}" class="text-primary hover:underline">Seed Jamaica holidays for {{ now()->year }}</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
