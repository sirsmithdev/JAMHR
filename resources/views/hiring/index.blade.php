<x-app-layout>
    @section('title', 'Hiring')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Hiring</h1>
                <p class="text-muted-foreground mt-1">Manage job postings, applications, and interviews.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('hiring.applications') }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Applications
                </a>
                <a href="{{ route('hiring.interviews') }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    Interviews
                </a>
                <a href="{{ route('hiring.postings.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Post Job
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-foreground">{{ $stats['total_postings'] }}</div>
                <div class="text-xs text-muted-foreground">Total Postings</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-emerald-600">{{ $stats['open_positions'] }}</div>
                <div class="text-xs text-muted-foreground">Open Positions</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-foreground">{{ $stats['total_applications'] }}</div>
                <div class="text-xs text-muted-foreground">Applications</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['new_applications'] }}</div>
                <div class="text-xs text-muted-foreground">New Applications</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-amber-600">{{ $stats['interviews_today'] }}</div>
                <div class="text-xs text-muted-foreground">Interviews Today</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['pending_offers'] }}</div>
                <div class="text-xs text-muted-foreground">Pending Offers</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <form action="{{ route('hiring.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search job titles..." class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>
                <select name="status" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">All Statuses</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    <option value="Filled" {{ request('status') == 'Filled' ? 'selected' : '' }}>Filled</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                    Search
                </button>
            </form>
        </div>

        <!-- Job Postings -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h2 class="font-serif font-semibold">Job Postings</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-muted/30">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Position</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Department</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Type</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Applications</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Posted</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($jobPostings as $posting)
                        <tr class="hover:bg-muted/5">
                            <td class="px-6 py-4">
                                <a href="{{ route('hiring.postings.show', $posting) }}" class="font-medium text-primary hover:underline">
                                    {{ $posting->title }}
                                </a>
                                @if($posting->salary_range !== 'Negotiable')
                                <div class="text-xs text-muted-foreground">{{ $posting->salary_range }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $posting->department }}</td>
                            <td class="px-6 py-4 text-sm">{{ $posting->employment_type }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">{{ $posting->applications_count }}</span>
                                    @if($posting->new_applications_count > 0)
                                    <span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">{{ $posting->new_applications_count }} new</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $posting->status_badge_class }}">
                                    {{ $posting->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-muted-foreground">
                                {{ $posting->posted_date?->format('M d, Y') ?? 'Not posted' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('hiring.postings.show', $posting) }}" class="text-muted-foreground hover:text-foreground">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('hiring.postings.edit', $posting) }}" class="text-muted-foreground hover:text-foreground">
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
                                No job postings found. <a href="{{ route('hiring.postings.create') }}" class="text-primary hover:underline">Create your first posting</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($jobPostings->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $jobPostings->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
