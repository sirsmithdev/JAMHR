<x-app-layout>
    @section('title', 'Performance & Appraisals')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Performance & Appraisals</h1>
                <p class="text-muted-foreground mt-1">Track KPIs, manage reviews, and foster employee growth.</p>
            </div>
            <a href="{{ route('performance.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
                Start Appraisal
            </a>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-muted-foreground">Company Avg.</span>
                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                </div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ number_format($avgRating, 1) }} / 5.0</div>
                <p class="text-xs text-emerald-600 mt-1">+0.3 from last quarter</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-muted-foreground">Reviews Due</span>
                    <svg class="h-4 w-4 text-secondary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div class="text-2xl font-bold font-serif text-foreground">{{ $reviewsDue }}</div>
                <p class="text-xs text-muted-foreground mt-1">Due by end of month</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-muted-foreground">Top Performer</span>
                    <svg class="h-4 w-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
                    </svg>
                </div>
                @if($topPerformer)
                <div class="flex items-center gap-2">
                    <div class="h-6 w-6 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-medium">
                        {{ $topPerformer->employee->initials }}
                    </div>
                    <span class="font-medium">{{ $topPerformer->employee->full_name }}</span>
                </div>
                <p class="text-xs text-muted-foreground mt-1">{{ $topPerformer->employee->department }} - {{ $topPerformer->rating_overall }} Rating</p>
                @else
                <div class="text-sm text-muted-foreground">No appraisals yet</div>
                @endif
            </div>
        </div>

        <!-- Evaluations Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="font-serif font-semibold">Recent Evaluations</h3>
                <p class="text-sm text-muted-foreground">Appraisals conducted in the current fiscal year</p>
            </div>
            <table class="w-full">
                <thead class="bg-muted/30">
                    <tr>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Employee</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Date</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Rating</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Goals Met</th>
                        <th class="text-left px-6 py-3 text-sm font-medium text-muted-foreground">Status</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-muted-foreground">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($appraisals as $appraisal)
                    <tr class="hover:bg-muted/5">
                        <td class="px-6 py-4">
                            <div>
                                <div class="font-medium">{{ $appraisal->employee->full_name }}</div>
                                <div class="text-xs text-muted-foreground">{{ $appraisal->employee->job_title }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-muted-foreground">{{ $appraisal->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-foreground">{{ $appraisal->rating_overall }}</span>
                                <svg class="h-3 w-3 text-secondary fill-secondary" viewBox="0 0 24 24">
                                    <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                </svg>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 h-2 bg-muted rounded-full overflow-hidden">
                                    <div class="h-full bg-primary" style="width: {{ $appraisal->goals_met_percentage ?? 0 }}%"></div>
                                </div>
                                <span class="text-xs text-muted-foreground">{{ $appraisal->goals_met_percentage ?? 0 }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-normal rounded-full {{ $appraisal->status_badge_class }}">
                                {{ $appraisal->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('performance.show', $appraisal) }}" class="text-primary hover:underline text-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-muted-foreground">
                            No appraisals found. <a href="{{ route('performance.create') }}" class="text-primary hover:underline">Create your first appraisal</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $appraisals->links() }}
    </div>
</x-app-layout>
