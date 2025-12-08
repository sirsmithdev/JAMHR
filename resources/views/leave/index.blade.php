<x-app-layout>
    @section('title', 'Leave Management')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Leave Management</h1>
                <p class="text-muted-foreground mt-1">Manage employee leave requests and track balances.</p>
            </div>
            <a href="{{ route('leave.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Request Leave
            </a>
        </div>

        <!-- Leave Balance Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm font-medium text-muted-foreground">Vacation Balance</div>
                <div class="text-2xl font-bold font-serif text-primary">{{ $vacationBalance }} days</div>
                <p class="text-xs text-muted-foreground mt-1">Remaining this year</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm font-medium text-muted-foreground">Sick Leave Balance</div>
                <div class="text-2xl font-bold font-serif text-emerald-600">{{ $sickBalance }} days</div>
                <p class="text-xs text-muted-foreground mt-1">Remaining this year</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm font-medium text-muted-foreground">Pending Requests</div>
                <div class="text-2xl font-bold font-serif text-amber-600">{{ $pendingRequests }}</div>
                <p class="text-xs text-muted-foreground mt-1">Awaiting approval</p>
            </div>
        </div>

        <!-- Leave Requests Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="font-serif font-semibold">Leave Requests</h3>
            </div>
            <table class="w-full">
                <thead class="bg-muted/30">
                    <tr>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Employee</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Type</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Dates</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Days</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Status</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-muted-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($leaveRequests as $request)
                    <tr class="hover:bg-muted/5">
                        <td class="px-6 py-4 font-medium">{{ $request->employee->full_name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $request->type_label }}</td>
                        <td class="px-6 py-4 text-sm text-muted-foreground">{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm">{{ $request->days_count }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $request->status_badge_class }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($request->isPending())
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('leave.approve', $request) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">Approve</button>
                                </form>
                                <form action="{{ route('leave.reject', $request) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">Reject</button>
                                </form>
                            </div>
                            @else
                            <span class="text-sm text-muted-foreground">{{ $request->approver?->name ?? '-' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-muted-foreground">
                            No leave requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $leaveRequests->links() }}
    </div>
</x-app-layout>
