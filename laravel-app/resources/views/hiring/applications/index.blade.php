<x-app-layout>
    @section('title', 'Applications')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('hiring.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Hiring
                </a>
                <h1 class="text-3xl font-serif text-foreground">Applications</h1>
                <p class="text-muted-foreground mt-1">Review and manage job applications.</p>
            </div>
            <a href="{{ route('hiring.applications.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Application
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <form action="{{ route('hiring.applications') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>
                <select name="job_posting_id" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">All Jobs</option>
                    @foreach($jobPostings as $posting)
                    <option value="{{ $posting->id }}" {{ request('job_posting_id') == $posting->id ? 'selected' : '' }}>{{ $posting->title }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">All Statuses</option>
                    @foreach(['New', 'Reviewing', 'Phone Screen', 'Interview Scheduled', 'Interviewed', 'Under Consideration', 'Offer Extended', 'Hired', 'Rejected'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                    Filter
                </button>
            </form>
        </div>

        <!-- Applications Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-muted/30">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Candidate</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Position</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Rating</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Applied</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($applications as $application)
                        <tr class="hover:bg-muted/5">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium">
                                        {{ $application->initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('hiring.applications.show', $application) }}" class="font-medium text-foreground hover:text-primary">
                                            {{ $application->full_name }}
                                        </a>
                                        <div class="text-sm text-muted-foreground">{{ $application->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('hiring.postings.show', $application->jobPosting) }}" class="text-sm hover:text-primary">
                                    {{ $application->jobPosting->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $application->status_badge_class }}">
                                    {{ $application->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($application->rating)
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $application->rating ? 'text-secondary fill-secondary' : 'text-muted' }}" viewBox="0 0 24 24">
                                        <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                    </svg>
                                    @endfor
                                </div>
                                @else
                                <span class="text-muted-foreground text-sm">Not rated</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-muted-foreground">
                                {{ $application->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('hiring.applications.show', $application) }}" class="text-muted-foreground hover:text-foreground" title="View">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('hiring.interviews.create', $application) }}" class="text-muted-foreground hover:text-foreground" title="Schedule Interview">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-muted-foreground">
                                No applications found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($applications->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $applications->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
