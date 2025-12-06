<x-app-layout>
    @section('title', 'Start Appraisal')

    <div class="space-y-8">
        <div>
            <a href="{{ route('performance.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Performance
            </a>
            <h1 class="text-3xl font-serif text-foreground">Start Appraisal</h1>
            <p class="text-muted-foreground mt-1">Create a new performance evaluation for an employee.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <form action="{{ route('performance.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="employee_id" class="block text-sm font-medium text-foreground mb-1">Employee</label>
                    <select name="employee_id" id="employee_id" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('employee_id') border-red-500 @enderror">
                        <option value="">Select employee...</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name }} - {{ $employee->job_title }}
                        </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="review_period" class="block text-sm font-medium text-foreground mb-1">Review Period</label>
                    <select name="review_period" id="review_period" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('review_period') border-red-500 @enderror">
                        <option value="">Select period...</option>
                        <option value="Q1 2025" {{ old('review_period') == 'Q1 2025' ? 'selected' : '' }}>Q1 2025 (Jan - Mar)</option>
                        <option value="Q2 2025" {{ old('review_period') == 'Q2 2025' ? 'selected' : '' }}>Q2 2025 (Apr - Jun)</option>
                        <option value="Q3 2025" {{ old('review_period') == 'Q3 2025' ? 'selected' : '' }}>Q3 2025 (Jul - Sep)</option>
                        <option value="Q4 2025" {{ old('review_period') == 'Q4 2025' ? 'selected' : '' }}>Q4 2025 (Oct - Dec)</option>
                        <option value="Annual 2025" {{ old('review_period') == 'Annual 2025' ? 'selected' : '' }}>Annual Review 2025</option>
                    </select>
                    @error('review_period')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-border pt-6">
                    <h3 class="font-medium text-foreground mb-4">Performance Ratings</h3>
                    <p class="text-sm text-muted-foreground mb-4">Rate each area on a scale of 1-5 (1 = Poor, 5 = Excellent)</p>

                    <div class="space-y-4">
                        <div>
                            <label for="rating_productivity" class="block text-sm font-medium text-foreground mb-1">Productivity</label>
                            <select name="rating_productivity" id="rating_productivity" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select rating...</option>
                                @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating_productivity') == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Poor', 'Below Average', 'Average', 'Good', 'Excellent'][$i-1] }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="rating_quality" class="block text-sm font-medium text-foreground mb-1">Quality of Work</label>
                            <select name="rating_quality" id="rating_quality" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select rating...</option>
                                @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating_quality') == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Poor', 'Below Average', 'Average', 'Good', 'Excellent'][$i-1] }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="rating_communication" class="block text-sm font-medium text-foreground mb-1">Communication</label>
                            <select name="rating_communication" id="rating_communication" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select rating...</option>
                                @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating_communication') == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Poor', 'Below Average', 'Average', 'Good', 'Excellent'][$i-1] }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="rating_teamwork" class="block text-sm font-medium text-foreground mb-1">Teamwork</label>
                            <select name="rating_teamwork" id="rating_teamwork" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select rating...</option>
                                @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating_teamwork') == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Poor', 'Below Average', 'Average', 'Good', 'Excellent'][$i-1] }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="rating_attendance" class="block text-sm font-medium text-foreground mb-1">Attendance & Punctuality</label>
                            <select name="rating_attendance" id="rating_attendance" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select rating...</option>
                                @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating_attendance') == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Poor', 'Below Average', 'Average', 'Good', 'Excellent'][$i-1] }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-t border-border pt-6 space-y-4">
                    <div>
                        <label for="goals_met" class="block text-sm font-medium text-foreground mb-1">Goals Met (%)</label>
                        <input type="number" name="goals_met" id="goals_met" value="{{ old('goals_met', 0) }}" min="0" max="100" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('goals_met') border-red-500 @enderror">
                        @error('goals_met')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="strengths" class="block text-sm font-medium text-foreground mb-1">Strengths</label>
                        <textarea name="strengths" id="strengths" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="What does this employee do well?">{{ old('strengths') }}</textarea>
                    </div>

                    <div>
                        <label for="areas_for_improvement" class="block text-sm font-medium text-foreground mb-1">Areas for Improvement</label>
                        <textarea name="areas_for_improvement" id="areas_for_improvement" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="What areas need development?">{{ old('areas_for_improvement') }}</textarea>
                    </div>

                    <div>
                        <label for="goals_next_period" class="block text-sm font-medium text-foreground mb-1">Goals for Next Period</label>
                        <textarea name="goals_next_period" id="goals_next_period" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="What should the employee focus on next?">{{ old('goals_next_period') }}</textarea>
                    </div>

                    <div>
                        <label for="comments" class="block text-sm font-medium text-foreground mb-1">Additional Comments</label>
                        <textarea name="comments" id="comments" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Any other feedback...">{{ old('comments') }}</textarea>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('performance.index') }}" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </a>
                    <button type="submit" name="status" value="draft" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Save as Draft
                    </button>
                    <button type="submit" name="status" value="completed" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Complete Appraisal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
