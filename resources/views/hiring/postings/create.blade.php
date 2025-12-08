<x-app-layout>
    @section('title', 'Post New Job')

    <div class="space-y-8">
        <div>
            <a href="{{ route('hiring.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Hiring
            </a>
            <h1 class="text-3xl font-serif text-foreground">Post New Job</h1>
            <p class="text-muted-foreground mt-1">Create a new job posting to attract candidates.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
            <form action="{{ route('hiring.postings.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-foreground mb-1">Job Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('title') border-red-500 @enderror" placeholder="e.g., Senior Software Developer">
                        @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-foreground mb-1">Department *</label>
                        <input type="text" name="department" id="department" value="{{ old('department') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('department') border-red-500 @enderror" placeholder="e.g., Engineering">
                        @error('department')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-foreground mb-1">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="e.g., Kingston, Jamaica">
                    </div>

                    <div>
                        <label for="employment_type" class="block text-sm font-medium text-foreground mb-1">Employment Type *</label>
                        <select name="employment_type" id="employment_type" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="Full-time" {{ old('employment_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="Part-time" {{ old('employment_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="Contract" {{ old('employment_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Temporary" {{ old('employment_type') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                            <option value="Internship" {{ old('employment_type') == 'Internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>

                    <div>
                        <label for="positions_available" class="block text-sm font-medium text-foreground mb-1">Positions Available *</label>
                        <input type="number" name="positions_available" id="positions_available" value="{{ old('positions_available', 1) }}" min="1" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label for="salary_min" class="block text-sm font-medium text-foreground mb-1">Minimum Salary (JMD)</label>
                        <input type="number" name="salary_min" id="salary_min" value="{{ old('salary_min') }}" min="0" step="1000" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="e.g., 150000">
                    </div>

                    <div>
                        <label for="salary_max" class="block text-sm font-medium text-foreground mb-1">Maximum Salary (JMD)</label>
                        <input type="number" name="salary_max" id="salary_max" value="{{ old('salary_max') }}" min="0" step="1000" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="e.g., 250000">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-foreground mb-1">Job Description *</label>
                    <textarea name="description" id="description" rows="5" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('description') border-red-500 @enderror" placeholder="Describe the role, responsibilities, and what the candidate can expect...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="requirements" class="block text-sm font-medium text-foreground mb-1">Requirements</label>
                    <textarea name="requirements" id="requirements" rows="4" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="List the qualifications, skills, and experience required...">{{ old('requirements') }}</textarea>
                </div>

                <div>
                    <label for="responsibilities" class="block text-sm font-medium text-foreground mb-1">Key Responsibilities</label>
                    <textarea name="responsibilities" id="responsibilities" rows="4" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="List the main duties and responsibilities...">{{ old('responsibilities') }}</textarea>
                </div>

                <div>
                    <label for="benefits" class="block text-sm font-medium text-foreground mb-1">Benefits & Perks</label>
                    <textarea name="benefits" id="benefits" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Health insurance, vacation days, etc...">{{ old('benefits') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-foreground mb-1">Status *</label>
                        <select name="status" id="status" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Open" {{ old('status', 'Open') == 'Open' ? 'selected' : '' }}>Open (Publish Now)</option>
                            <option value="On Hold" {{ old('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                        </select>
                    </div>

                    <div>
                        <label for="closing_date" class="block text-sm font-medium text-foreground mb-1">Application Deadline</label>
                        <input type="date" name="closing_date" id="closing_date" value="{{ old('closing_date') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('hiring.index') }}" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Create Job Posting
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
