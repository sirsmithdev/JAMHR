<x-app-layout>
    @section('title', 'Interviews')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('hiring.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Hiring
                </a>
                <h1 class="text-3xl font-serif text-foreground">Interviews</h1>
                <p class="text-muted-foreground mt-1">Schedule and manage candidate interviews.</p>
            </div>
            <a href="{{ route('hiring.interviews.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Schedule Interview
            </a>
        </div>

        @if($upcomingInterviews->count() > 0)
        <!-- Upcoming Today -->
        <div class="bg-gradient-to-r from-primary/10 to-secondary/10 rounded-lg p-6">
            <h3 class="font-serif font-semibold mb-4">Upcoming Interviews</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($upcomingInterviews as $interview)
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="flex items-start justify-between mb-2">
                        <span class="text-xs font-medium px-2 py-1 rounded bg-primary/10 text-primary">{{ $interview->type }}</span>
                        <span class="text-xs text-muted-foreground">{{ $interview->scheduled_at->diffForHumans() }}</span>
                    </div>
                    <div class="font-medium">{{ $interview->application->full_name }}</div>
                    <div class="text-sm text-muted-foreground">{{ $interview->application->jobPosting->title }}</div>
                    <div class="text-sm text-muted-foreground mt-2">
                        <svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $interview->scheduled_at->format('M d, Y \a\t h:i A') }}
                    </div>
                    @if($interview->location)
                    <div class="text-sm text-muted-foreground">
                        <svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        {{ $interview->location }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <form action="{{ route('hiring.interviews') }}" method="GET" class="flex flex-wrap gap-4">
                <input type="date" name="date" value="{{ request('date') }}" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                <select name="status" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">All Statuses</option>
                    @foreach(['Scheduled', 'Completed', 'Cancelled', 'No Show', 'Rescheduled'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                    Filter
                </button>
            </form>
        </div>

        <!-- Interviews Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-muted/30">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Candidate</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Position</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Type</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Date & Time</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Outcome</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($interviews as $interview)
                        <tr class="hover:bg-muted/5">
                            <td class="px-6 py-4">
                                <a href="{{ route('hiring.applications.show', $interview->application) }}" class="font-medium text-foreground hover:text-primary">
                                    {{ $interview->application->full_name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $interview->application->jobPosting->title }}</td>
                            <td class="px-6 py-4 text-sm">{{ $interview->type }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div>{{ $interview->scheduled_at->format('M d, Y') }}</div>
                                <div class="text-muted-foreground">{{ $interview->scheduled_at->format('h:i A') }} ({{ $interview->duration_minutes }} min)</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $interview->status_badge_class }}">
                                    {{ $interview->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $interview->outcome_badge_class }}">
                                    {{ $interview->outcome }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('hiring.interviews.show', $interview) }}" class="text-muted-foreground hover:text-foreground">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-muted-foreground">
                                No interviews found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($interviews->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $interviews->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
