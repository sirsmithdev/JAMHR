<x-app-layout>
    @section('title', 'Edit Job Posting')

    <div class="space-y-8">
        <div>
            <a href="{{ route('hiring.postings.show', $posting) }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Job Posting
            </a>
            <h1 class="text-3xl font-serif text-foreground">Edit Job Posting</h1>
            <p class="text-muted-foreground mt-1">Update the job posting details.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
            <form action="{{ route('hiring.postings.update', $posting) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-foreground mb-1">Job Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $posting->title) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-foreground mb-1">Department *</label>
                        <input type="text" name="department" id="department" value="{{ old('department', $posting->department) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-foreground mb-1">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $posting->location) }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label for="employment_type" class="block text-sm font-medium text-foreground mb-1">Employment Type *</label>
                        <select name="employment_type" id="employment_type" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach(['Full-time', 'Part-time', 'Contract', 'Temporary', 'Internship'] as $type)
                            <option value="{{ $type }}" {{ old('employment_type', $posting->employment_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="positions_available" class="block text-sm font-medium text-foreground mb-1">Positions Available *</label>
                        <input type="number" name="positions_available" id="positions_available" value="{{ old('positions_available', $posting->positions_available) }}" min="1" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label for="salary_min" class="block text-sm font-medium text-foreground mb-1">Minimum Salary (JMD)</label>
                        <input type="number" name="salary_min" id="salary_min" value="{{ old('salary_min', $posting->salary_min) }}" min="0" step="1000" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label for="salary_max" class="block text-sm font-medium text-foreground mb-1">Maximum Salary (JMD)</label>
                        <input type="number" name="salary_max" id="salary_max" value="{{ old('salary_max', $posting->salary_max) }}" min="0" step="1000" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-foreground mb-1">Job Description *</label>
                    <textarea name="description" id="description" rows="5" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('description', $posting->description) }}</textarea>
                </div>

                <div>
                    <label for="requirements" class="block text-sm font-medium text-foreground mb-1">Requirements</label>
                    <textarea name="requirements" id="requirements" rows="4" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('requirements', $posting->requirements) }}</textarea>
                </div>

                <div>
                    <label for="responsibilities" class="block text-sm font-medium text-foreground mb-1">Key Responsibilities</label>
                    <textarea name="responsibilities" id="responsibilities" rows="4" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('responsibilities', $posting->responsibilities) }}</textarea>
                </div>

                <div>
                    <label for="benefits" class="block text-sm font-medium text-foreground mb-1">Benefits & Perks</label>
                    <textarea name="benefits" id="benefits" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('benefits', $posting->benefits) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-foreground mb-1">Status *</label>
                        <select name="status" id="status" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach(['Draft', 'Open', 'On Hold', 'Closed', 'Filled'] as $status)
                            <option value="{{ $status }}" {{ old('status', $posting->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="closing_date" class="block text-sm font-medium text-foreground mb-1">Application Deadline</label>
                        <input type="date" name="closing_date" id="closing_date" value="{{ old('closing_date', $posting->closing_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('hiring.postings.show', $posting) }}" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Update Job Posting
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
