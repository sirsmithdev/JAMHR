<x-app-layout>
    @section('title', $application->full_name)

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <a href="{{ route('hiring.applications') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Applications
                </a>
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xl font-medium">
                        {{ $application->initials }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-serif text-foreground">{{ $application->full_name }}</h1>
                        <p class="text-muted-foreground">Applied for <a href="{{ route('hiring.postings.show', $application->jobPosting) }}" class="text-primary hover:underline">{{ $application->jobPosting->title }}</a></p>
                    </div>
                </div>
            </div>
            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $application->status_badge_class }}">
                {{ $application->status }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Contact Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Contact Information</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-muted-foreground">Email</dt>
                            <dd class="font-medium mt-1">
                                <a href="mailto:{{ $application->email }}" class="text-primary hover:underline">{{ $application->email }}</a>
                            </dd>
                        </div>
                        @if($application->phone)
                        <div>
                            <dt class="text-muted-foreground">Phone</dt>
                            <dd class="font-medium mt-1">{{ $application->phone }}</dd>
                        </div>
                        @endif
                        @if($application->address)
                        <div class="md:col-span-2">
                            <dt class="text-muted-foreground">Address</dt>
                            <dd class="font-medium mt-1">{{ $application->address }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Experience & Skills -->
                @if($application->experience_summary)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Experience Summary</h3>
                    <p class="text-muted-foreground text-sm whitespace-pre-line">{{ $application->experience_summary }}</p>
                </div>
                @endif

                @if($application->education)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Education</h3>
                    <p class="text-muted-foreground text-sm whitespace-pre-line">{{ $application->education }}</p>
                </div>
                @endif

                @if($application->skills)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Skills</h3>
                    <p class="text-muted-foreground text-sm whitespace-pre-line">{{ $application->skills }}</p>
                </div>
                @endif

                @if($application->cover_letter_text)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Cover Letter</h3>
                    <p class="text-muted-foreground text-sm whitespace-pre-line">{{ $application->cover_letter_text }}</p>
                </div>
                @endif

                <!-- Interviews -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                        <h3 class="font-serif font-semibold">Interviews</h3>
                        <a href="{{ route('hiring.interviews.create', $application) }}" class="text-sm text-primary hover:underline">Schedule Interview</a>
                    </div>
                    @if($application->interviews->count() > 0)
                    <div class="divide-y divide-border">
                        @foreach($application->interviews as $interview)
                        <div class="px-6 py-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="font-medium">{{ $interview->type }}</div>
                                    <div class="text-sm text-muted-foreground">{{ $interview->scheduled_at->format('M d, Y \a\t h:i A') }}</div>
                                    @if($interview->location)
                                    <div class="text-sm text-muted-foreground">{{ $interview->location }}</div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $interview->status_badge_class }}">
                                        {{ $interview->status }}
                                    </span>
                                    @if($interview->outcome !== 'Pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $interview->outcome_badge_class }}">
                                        {{ $interview->outcome }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @if($interview->feedback)
                            <div class="mt-3 text-sm text-muted-foreground">
                                <strong>Feedback:</strong> {{ $interview->feedback }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="px-6 py-8 text-center text-muted-foreground">
                        No interviews scheduled yet.
                    </div>
                    @endif
                </div>

                <!-- Update Status -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Update Application</h3>
                    <form action="{{ route('hiring.applications.update', $application) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-foreground mb-1">Status</label>
                                <select name="status" id="status" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    @foreach(['New', 'Reviewing', 'Phone Screen', 'Interview Scheduled', 'Interviewed', 'Under Consideration', 'Offer Extended', 'Offer Accepted', 'Offer Declined', 'Hired', 'Rejected', 'Withdrawn'] as $status)
                                    <option value="{{ $status }}" {{ $application->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="rating" class="block text-sm font-medium text-foreground mb-1">Rating</label>
                                <select name="rating" id="rating" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    <option value="">Not rated</option>
                                    @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ $application->rating == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'][$i-1] }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-foreground mb-1">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('notes', $application->notes) }}</textarea>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                            Update Application
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Details</h3>
                    <dl class="space-y-3 text-sm">
                        @if($application->expected_salary)
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Expected Salary</dt>
                            <dd class="font-medium">J${{ number_format($application->expected_salary) }}</dd>
                        </div>
                        @endif
                        @if($application->available_start_date)
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Available From</dt>
                            <dd class="font-medium">{{ $application->available_start_date->format('M d, Y') }}</dd>
                        </div>
                        @endif
                        @if($application->source)
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Source</dt>
                            <dd class="font-medium">{{ $application->source }}</dd>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Applied</dt>
                            <dd class="font-medium">{{ $application->created_at->format('M d, Y') }}</dd>
                        </div>
                        @if($application->reviewer)
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Reviewed By</dt>
                            <dd class="font-medium">{{ $application->reviewer->name }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Documents -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Documents</h3>
                    <div class="space-y-2">
                        @if($application->resume_path)
                        <a href="{{ Storage::url($application->resume_path) }}" target="_blank" class="flex items-center gap-2 p-2 border border-border rounded-md hover:bg-muted/50 transition-colors">
                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span class="text-sm">Resume</span>
                        </a>
                        @endif
                        @if($application->cover_letter_path)
                        <a href="{{ Storage::url($application->cover_letter_path) }}" target="_blank" class="flex items-center gap-2 p-2 border border-border rounded-md hover:bg-muted/50 transition-colors">
                            <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span class="text-sm">Cover Letter</span>
                        </a>
                        @endif
                        @if(!$application->resume_path && !$application->cover_letter_path)
                        <p class="text-sm text-muted-foreground">No documents uploaded.</p>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 space-y-3">
                    <a href="{{ route('hiring.interviews.create', $application) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Schedule Interview
                    </a>
                    @if(!in_array($application->status, ['Hired', 'Rejected', 'Withdrawn']))
                    <form action="{{ route('hiring.applications.hire', $application) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition-colors" onclick="return confirm('This will create an employee record for this applicant. Continue?')">
                            Hire Applicant
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
