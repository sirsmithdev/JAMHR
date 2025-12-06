<x-app-layout>
    @section('title', 'New Termination')

    <div class="space-y-8">
        <div>
            <a href="{{ route('terminations.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Terminations
            </a>
            <h1 class="text-3xl font-serif text-foreground">New Termination</h1>
            <p class="text-muted-foreground mt-1">Initiate the offboarding process for an employee.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <form action="{{ route('terminations.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="employee_id" class="block text-sm font-medium text-foreground mb-1">Employee *</label>
                    <select name="employee_id" id="employee_id" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('employee_id') border-red-500 @enderror">
                        <option value="">Select employee...</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id', request('employee_id')) == $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name }} - {{ $employee->department }} ({{ $employee->job_title }})
                        </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-foreground mb-1">Termination Type *</label>
                    <select name="type" id="type" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('type') border-red-500 @enderror">
                        <option value="">Select type...</option>
                        <option value="Resignation" {{ old('type') == 'Resignation' ? 'selected' : '' }}>Resignation (Voluntary)</option>
                        <option value="Termination" {{ old('type') == 'Termination' ? 'selected' : '' }}>Termination (Involuntary)</option>
                        <option value="Redundancy" {{ old('type') == 'Redundancy' ? 'selected' : '' }}>Redundancy</option>
                        <option value="End of Contract" {{ old('type') == 'End of Contract' ? 'selected' : '' }}>End of Contract</option>
                        <option value="Retirement" {{ old('type') == 'Retirement' ? 'selected' : '' }}>Retirement</option>
                        <option value="Mutual Agreement" {{ old('type') == 'Mutual Agreement' ? 'selected' : '' }}>Mutual Agreement</option>
                        <option value="Dismissal" {{ old('type') == 'Dismissal' ? 'selected' : '' }}>Dismissal (Gross Misconduct)</option>
                    </select>
                    @error('type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="notice_date" class="block text-sm font-medium text-foreground mb-1">Notice Date *</label>
                        <input type="date" name="notice_date" id="notice_date" value="{{ old('notice_date', now()->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('notice_date') border-red-500 @enderror">
                        @error('notice_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last_working_day" class="block text-sm font-medium text-foreground mb-1">Last Working Day *</label>
                        <input type="date" name="last_working_day" id="last_working_day" value="{{ old('last_working_day') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('last_working_day') border-red-500 @enderror">
                        @error('last_working_day')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="reason" class="block text-sm font-medium text-foreground mb-1">Reason for Departure</label>
                    <textarea name="reason" id="reason" rows="4" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Provide details about the reason for termination...">{{ old('reason') }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="eligible_for_rehire" id="eligible_for_rehire" value="1" {{ old('eligible_for_rehire', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-border text-primary focus:ring-primary/20">
                    <label for="eligible_for_rehire" class="text-sm text-foreground">Eligible for rehire</label>
                </div>

                <div>
                    <label for="rehire_notes" class="block text-sm font-medium text-foreground mb-1">Rehire Notes</label>
                    <textarea name="rehire_notes" id="rehire_notes" rows="2" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Any notes regarding future employment eligibility...">{{ old('rehire_notes') }}</textarea>
                </div>

                <!-- Jamaica Notice Period Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-medium text-blue-900 text-sm mb-2">Jamaica Notice Period Requirements</h4>
                    <ul class="text-xs text-blue-800 space-y-1">
                        <li>Less than 5 years service: 2 weeks notice</li>
                        <li>5-10 years service: 4 weeks notice</li>
                        <li>10-15 years service: 6 weeks notice</li>
                        <li>15-20 years service: 8 weeks notice</li>
                        <li>20+ years service: 12 weeks notice</li>
                    </ul>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('terminations.index') }}" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Create Termination Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
