<x-app-layout>
    @section('title', 'Time & Attendance')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Time & Attendance</h1>
                <p class="text-muted-foreground mt-1">Track employee clock-ins, overtime, and attendance.</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('time.index') }}" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <button type="submit" class="px-4 py-2 bg-white border border-border rounded-md hover:bg-muted transition-colors">Go</button>
                </form>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm font-medium text-muted-foreground">Total Employees</div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ $totalEmployees }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm font-medium text-muted-foreground">Present Today</div>
                <div class="text-2xl font-bold font-serif text-emerald-600">{{ $presentToday }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm font-medium text-muted-foreground">Late Arrivals</div>
                <div class="text-2xl font-bold font-serif text-amber-600">{{ $lateArrivals }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm font-medium text-muted-foreground">Overtime Hours</div>
                <div class="text-2xl font-bold font-serif text-blue-600">{{ number_format($overtimeHours, 1) }}</div>
            </div>
        </div>

        <!-- Time Entries Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="font-serif font-semibold">Time Log for {{ $date->format('F d, Y') }}</h3>
            </div>
            <table class="w-full">
                <thead class="bg-muted/30">
                    <tr>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Employee</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Clock In</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Clock Out</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Total Hours</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($timeEntries as $entry)
                    <tr class="hover:bg-muted/5">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium text-sm">
                                    {{ $entry->employee->initials }}
                                </div>
                                <span class="font-medium">{{ $entry->employee->full_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $entry->clock_in?->format('h:i A') ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $entry->clock_out?->format('h:i A') ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium">{{ $entry->total_hours ? number_format($entry->total_hours, 2) . ' hrs' : '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full border {{ $entry->status_badge_class }}">
                                {{ ucfirst($entry->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-muted-foreground">
                            No time entries for this date.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $timeEntries->links() }}
    </div>
</x-app-layout>
