<x-app-layout>
    @section('title', 'Terminations')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Terminations</h1>
                <p class="text-muted-foreground mt-1">Manage employee offboarding and termination processes.</p>
            </div>
            <a href="{{ route('terminations.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Termination
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</div>
                <div class="text-xs text-muted-foreground">Total Records</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</div>
                <div class="text-xs text-muted-foreground">Pending</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['in_progress'] }}</div>
                <div class="text-xs text-muted-foreground">In Progress</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-emerald-600">{{ $stats['completed_this_month'] }}</div>
                <div class="text-xs text-muted-foreground">Completed This Month</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-foreground">{{ $stats['resignations'] }}</div>
                <div class="text-xs text-muted-foreground">Resignations</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-red-600">{{ $stats['terminations'] }}</div>
                <div class="text-xs text-muted-foreground">Terminations</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <form action="{{ route('terminations.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by employee name..." class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>
                <select name="type" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">All Types</option>
                    @foreach(['Resignation', 'Termination', 'Redundancy', 'End of Contract', 'Retirement', 'Mutual Agreement'] as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">All Statuses</option>
                    @foreach(['Pending', 'In Progress', 'Completed', 'Cancelled'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                    Filter
                </button>
            </form>
        </div>

        <!-- Terminations Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-muted/30">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Employee</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Type</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Notice Date</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Last Day</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Clearance</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($terminations as $termination)
                        <tr class="hover:bg-muted/5">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium">
                                        {{ $termination->employee->initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('terminations.show', $termination) }}" class="font-medium text-foreground hover:text-primary">
                                            {{ $termination->employee->full_name }}
                                        </a>
                                        <div class="text-sm text-muted-foreground">{{ $termination->employee->department }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $termination->type_badge_class }}">
                                    {{ $termination->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $termination->notice_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">{{ $termination->last_working_day->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="w-24 h-2 bg-muted rounded-full overflow-hidden">
                                    <div class="h-full bg-primary transition-all" style="width: {{ $termination->clearance_progress }}%"></div>
                                </div>
                                <span class="text-xs text-muted-foreground">{{ $termination->clearance_progress }}%</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $termination->status_badge_class }}">
                                    {{ $termination->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('terminations.show', $termination) }}" class="text-muted-foreground hover:text-foreground" title="View">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('terminations.edit', $termination) }}" class="text-muted-foreground hover:text-foreground" title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-muted-foreground">
                                No termination records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($terminations->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $terminations->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
