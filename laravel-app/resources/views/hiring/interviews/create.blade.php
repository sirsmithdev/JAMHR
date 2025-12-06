<x-app-layout>
    @section('title', 'Schedule Interview')

    <div class="space-y-8">
        <div>
            <a href="{{ route('hiring.interviews') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Interviews
            </a>
            <h1 class="text-3xl font-serif text-foreground">Schedule Interview</h1>
            <p class="text-muted-foreground mt-1">Set up an interview with a candidate.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <form action="{{ route('hiring.interviews.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="job_application_id" class="block text-sm font-medium text-foreground mb-1">Candidate *</label>
                    <select name="job_application_id" id="job_application_id" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('job_application_id') border-red-500 @enderror">
                        <option value="">Select candidate...</option>
                        @foreach($applications as $app)
                        <option value="{{ $app->id }}" {{ (old('job_application_id') == $app->id || (isset($application) && $application->id == $app->id)) ? 'selected' : '' }}>
                            {{ $app->full_name }} - {{ $app->jobPosting->title }}
                        </option>
                        @endforeach
                    </select>
                    @error('job_application_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-foreground mb-1">Interview Type *</label>
                    <select name="type" id="type" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <option value="Phone Screen" {{ old('type') == 'Phone Screen' ? 'selected' : '' }}>Phone Screen</option>
                        <option value="Video Call" {{ old('type') == 'Video Call' ? 'selected' : '' }}>Video Call</option>
                        <option value="In-Person" {{ old('type', 'In-Person') == 'In-Person' ? 'selected' : '' }}>In-Person</option>
                        <option value="Panel" {{ old('type') == 'Panel' ? 'selected' : '' }}>Panel Interview</option>
                        <option value="Technical" {{ old('type') == 'Technical' ? 'selected' : '' }}>Technical Interview</option>
                        <option value="Final" {{ old('type') == 'Final' ? 'selected' : '' }}>Final Interview</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium text-foreground mb-1">Date & Time *</label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" value="{{ old('scheduled_at') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('scheduled_at') border-red-500 @enderror">
                        @error('scheduled_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-foreground mb-1">Duration (minutes) *</label>
                        <select name="duration_minutes" id="duration_minutes" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="30" {{ old('duration_minutes') == '30' ? 'selected' : '' }}>30 minutes</option>
                            <option value="45" {{ old('duration_minutes') == '45' ? 'selected' : '' }}>45 minutes</option>
                            <option value="60" {{ old('duration_minutes', '60') == '60' ? 'selected' : '' }}>1 hour</option>
                            <option value="90" {{ old('duration_minutes') == '90' ? 'selected' : '' }}>1.5 hours</option>
                            <option value="120" {{ old('duration_minutes') == '120' ? 'selected' : '' }}>2 hours</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-foreground mb-1">Location / Meeting Link</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="e.g., Conference Room A or https://zoom.us/j/...">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-foreground mb-1">Notes for Interviewers</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Any preparation notes or topics to cover...">{{ old('notes') }}</textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('hiring.interviews') }}" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Schedule Interview
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
