<x-app-layout>
    @section('title', 'Interview Details')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <a href="{{ route('hiring.interviews') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Interviews
                </a>
                <h1 class="text-3xl font-serif text-foreground">{{ $interview->type }} Interview</h1>
                <p class="text-muted-foreground mt-1">
                    With <a href="{{ route('hiring.applications.show', $interview->application) }}" class="text-primary hover:underline">{{ $interview->application->full_name }}</a>
                    for {{ $interview->application->jobPosting->title }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $interview->status_badge_class }}">
                    {{ $interview->status }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $interview->outcome_badge_class }}">
                    {{ $interview->outcome }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Interview Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Interview Details</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-muted-foreground">Date & Time</dt>
                            <dd class="font-medium mt-1">{{ $interview->scheduled_at->format('l, M d, Y \a\t h:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-muted-foreground">Duration</dt>
                            <dd class="font-medium mt-1">{{ $interview->duration_minutes }} minutes</dd>
                        </div>
                        @if($interview->location)
                        <div class="md:col-span-2">
                            <dt class="text-muted-foreground">Location</dt>
                            <dd class="font-medium mt-1">
                                @if(str_starts_with($interview->location, 'http'))
                                <a href="{{ $interview->location }}" target="_blank" class="text-primary hover:underline">{{ $interview->location }}</a>
                                @else
                                {{ $interview->location }}
                                @endif
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                @if($interview->notes)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Pre-Interview Notes</h3>
                    <p class="text-muted-foreground text-sm whitespace-pre-line">{{ $interview->notes }}</p>
                </div>
                @endif

                <!-- Update Interview -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Update Interview</h3>
                    <form action="{{ route('hiring.interviews.update', $interview) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-foreground mb-1">Status</label>
                                <select name="status" id="status" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    @foreach(['Scheduled', 'Completed', 'Cancelled', 'No Show', 'Rescheduled'] as $status)
                                    <option value="{{ $status }}" {{ $interview->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="outcome" class="block text-sm font-medium text-foreground mb-1">Outcome</label>
                                <select name="outcome" id="outcome" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    @foreach(['Pending', 'Pass', 'Fail', 'On Hold'] as $outcome)
                                    <option value="{{ $outcome }}" {{ $interview->outcome == $outcome ? 'selected' : '' }}>{{ $outcome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="rating" class="block text-sm font-medium text-foreground mb-1">Candidate Rating</label>
                            <select name="rating" id="rating" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Not rated</option>
                                @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $interview->rating == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'][$i-1] }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="feedback" class="block text-sm font-medium text-foreground mb-1">Feedback</label>
                            <textarea name="feedback" id="feedback" rows="4" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Overall assessment of the candidate...">{{ old('feedback', $interview->feedback) }}</textarea>
                        </div>

                        <div>
                            <label for="questions_asked" class="block text-sm font-medium text-foreground mb-1">Questions Asked</label>
                            <textarea name="questions_asked" id="questions_asked" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="List of questions asked during the interview...">{{ old('questions_asked', $interview->questions_asked) }}</textarea>
                        </div>

                        <div>
                            <label for="candidate_questions" class="block text-sm font-medium text-foreground mb-1">Candidate's Questions</label>
                            <textarea name="candidate_questions" id="candidate_questions" rows="2" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Questions the candidate asked...">{{ old('candidate_questions', $interview->candidate_questions) }}</textarea>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                            Update Interview
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Candidate Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Candidate</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium">
                            {{ $interview->application->initials }}
                        </div>
                        <div>
                            <div class="font-medium">{{ $interview->application->full_name }}</div>
                            <div class="text-sm text-muted-foreground">{{ $interview->application->email }}</div>
                        </div>
                    </div>
                    <a href="{{ route('hiring.applications.show', $interview->application) }}" class="text-sm text-primary hover:underline">View Full Application →</a>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 space-y-3">
                    <h3 class="font-serif font-semibold mb-2">Actions</h3>
                    <a href="{{ route('hiring.interviews.create', $interview->application) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors text-sm">
                        Schedule Follow-up
                    </a>
                    <a href="mailto:{{ $interview->application->email }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors text-sm">
                        Email Candidate
                    </a>
                </div>

                <!-- Created Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Details</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Scheduled By</dt>
                            <dd class="font-medium">{{ $interview->creator->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Created</dt>
                            <dd class="font-medium">{{ $interview->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
