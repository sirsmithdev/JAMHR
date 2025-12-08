<x-app-layout>
    @section('title', $posting->title)

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <a href="{{ route('hiring.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Hiring
                </a>
                <h1 class="text-3xl font-serif text-foreground">{{ $posting->title }}</h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-muted-foreground">{{ $posting->department }}</span>
                    <span class="text-muted-foreground">•</span>
                    <span class="text-muted-foreground">{{ $posting->location ?? 'No location specified' }}</span>
                    <span class="text-muted-foreground">•</span>
                    <span class="text-muted-foreground">{{ $posting->employment_type }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $posting->status_badge_class }}">
                    {{ $posting->status }}
                </span>
                <a href="{{ route('hiring.postings.edit', $posting) }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Job Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Job Description</h3>
                    <div class="prose prose-sm max-w-none text-muted-foreground whitespace-pre-line">{{ $posting->description }}</div>
                </div>

                @if($posting->responsibilities)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Key Responsibilities</h3>
                    <div class="prose prose-sm max-w-none text-muted-foreground whitespace-pre-line">{{ $posting->responsibilities }}</div>
                </div>
                @endif

                @if($posting->requirements)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Requirements</h3>
                    <div class="prose prose-sm max-w-none text-muted-foreground whitespace-pre-line">{{ $posting->requirements }}</div>
                </div>
                @endif

                @if($posting->benefits)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Benefits & Perks</h3>
                    <div class="prose prose-sm max-w-none text-muted-foreground whitespace-pre-line">{{ $posting->benefits }}</div>
                </div>
                @endif

                <!-- Applications -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                        <h3 class="font-serif font-semibold">Applications ({{ $applicationStats['total'] }})</h3>
                        <a href="{{ route('hiring.applications.create', $posting) }}" class="text-sm text-primary hover:underline">Add Application</a>
                    </div>
                    @if($posting->applications->count() > 0)
                    <div class="divide-y divide-border">
                        @foreach($posting->applications->take(5) as $application)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-muted/5">
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
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $application->status_badge_class }}">
                                {{ $application->status }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @if($posting->applications->count() > 5)
                    <div class="px-6 py-3 bg-muted/20 text-center">
                        <a href="{{ route('hiring.applications', ['job_posting_id' => $posting->id]) }}" class="text-sm text-primary hover:underline">View all {{ $posting->applications->count() }} applications</a>
                    </div>
                    @endif
                    @else
                    <div class="px-6 py-8 text-center text-muted-foreground">
                        No applications yet.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Overview</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Salary Range</dt>
                            <dd class="font-medium">{{ $posting->salary_range }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Positions</dt>
                            <dd class="font-medium">{{ $posting->positions_filled }}/{{ $posting->positions_available }} filled</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Posted</dt>
                            <dd class="font-medium">{{ $posting->posted_date?->format('M d, Y') ?? 'Not posted' }}</dd>
                        </div>
                        @if($posting->closing_date)
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Closes</dt>
                            <dd class="font-medium">{{ $posting->closing_date->format('M d, Y') }}</dd>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Created By</dt>
                            <dd class="font-medium">{{ $posting->creator->name ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Application Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Pipeline</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">New</span>
                            <span class="font-medium">{{ $applicationStats['new'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">In Progress</span>
                            <span class="font-medium">{{ $applicationStats['in_progress'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Hired</span>
                            <span class="font-medium text-emerald-600">{{ $applicationStats['hired'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 space-y-3">
                    <a href="{{ route('hiring.applications.create', $posting) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Add Application
                    </a>
                    <form action="{{ route('hiring.postings.destroy', $posting) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job posting?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-md hover:bg-red-50 transition-colors">
                            Delete Posting
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
