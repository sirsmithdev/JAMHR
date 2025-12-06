<x-app-layout>
    @section('title', 'Incident Reporting')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Incident Reporting</h1>
                <p class="text-muted-foreground mt-1">Log, track, and resolve workplace incidents and safety concerns.</p>
            </div>
            <a href="{{ route('incidents.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-destructive hover:bg-destructive/90 shadow-lg transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                Report Incident
            </a>
        </div>

        <!-- Summary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm font-medium text-muted-foreground">Open Cases</div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ $openCases }}</div>
                <p class="text-xs text-muted-foreground mt-1">Requiring action</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm font-medium text-muted-foreground">Safety Score</div>
                <div class="text-2xl font-bold font-serif text-emerald-600">94%</div>
                <p class="text-xs text-muted-foreground mt-1">Last 30 days</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm font-medium text-muted-foreground">Days Incident Free</div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ $daysIncidentFree }}</div>
                <p class="text-xs text-emerald-600 mt-1">Keep it up!</p>
            </div>
        </div>

        <!-- Incidents List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="font-serif font-semibold">Recent Logs</h3>
            </div>
            <table class="w-full">
                <thead class="bg-muted/30">
                    <tr>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">ID</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Date</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Type</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Description</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Severity</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Status</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-muted-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($incidents as $incident)
                    <tr class="hover:bg-muted/5">
                        <td class="px-6 py-4 font-mono text-xs text-muted-foreground">{{ $incident->incident_id }}</td>
                        <td class="px-6 py-4 text-sm">{{ $incident->occurred_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 font-medium">{{ $incident->type }}</td>
                        <td class="px-6 py-4 text-sm text-muted-foreground max-w-[300px] truncate">{{ $incident->description }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-normal rounded-full border {{ $incident->severity_badge_class }}">
                                {{ ucfirst($incident->severity) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-2 rounded-full {{ $incident->status_indicator_class }}"></div>
                                <span class="text-sm">{{ ucfirst($incident->status) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('incidents.show', $incident) }}" class="text-muted-foreground hover:text-primary">
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
                            No incidents recorded.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $incidents->links() }}
    </div>
</x-app-layout>
