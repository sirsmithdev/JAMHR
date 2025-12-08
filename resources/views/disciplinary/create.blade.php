<x-app-layout>
    @section('title', 'New Disciplinary Action')

    <div class="space-y-8">
        <div>
            <a href="{{ route('disciplinary.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Disciplinary Actions
            </a>
            <h1 class="text-3xl font-serif text-foreground">New Disciplinary Action</h1>
            <p class="text-muted-foreground mt-1">Document a disciplinary action or warning for an employee.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
            <form action="{{ route('disciplinary.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="employee_id" class="block text-sm font-medium text-foreground mb-1">Employee *</label>
                    <select name="employee_id" id="employee_id" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('employee_id') border-red-500 @enderror">
                        <option value="">Select employee...</option>
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ (old('employee_id') == $emp->id || (isset($employee) && $employee->id == $emp->id)) ? 'selected' : '' }}>
                            {{ $emp->full_name }} - {{ $emp->department }}
                        </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-foreground mb-1">Action Type *</label>
                        <select name="type" id="type" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('type') border-red-500 @enderror">
                            <option value="">Select type...</option>
                            <option value="Verbal Warning" {{ old('type') == 'Verbal Warning' ? 'selected' : '' }}>Verbal Warning</option>
                            <option value="Written Warning" {{ old('type') == 'Written Warning' ? 'selected' : '' }}>Written Warning</option>
                            <option value="Final Written Warning" {{ old('type') == 'Final Written Warning' ? 'selected' : '' }}>Final Written Warning</option>
                            <option value="Suspension" {{ old('type') == 'Suspension' ? 'selected' : '' }}>Suspension</option>
                            <option value="Demotion" {{ old('type') == 'Demotion' ? 'selected' : '' }}>Demotion</option>
                            <option value="Performance Improvement Plan" {{ old('type') == 'Performance Improvement Plan' ? 'selected' : '' }}>Performance Improvement Plan (PIP)</option>
                            <option value="Termination" {{ old('type') == 'Termination' ? 'selected' : '' }}>Termination</option>
                        </select>
                        @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-foreground mb-1">Category *</label>
                        <select name="category" id="category" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('category') border-red-500 @enderror">
                            <option value="">Select category...</option>
                            <option value="Attendance" {{ old('category') == 'Attendance' ? 'selected' : '' }}>Attendance</option>
                            <option value="Performance" {{ old('category') == 'Performance' ? 'selected' : '' }}>Performance</option>
                            <option value="Conduct" {{ old('category') == 'Conduct' ? 'selected' : '' }}>Conduct</option>
                            <option value="Policy Violation" {{ old('category') == 'Policy Violation' ? 'selected' : '' }}>Policy Violation</option>
                            <option value="Insubordination" {{ old('category') == 'Insubordination' ? 'selected' : '' }}>Insubordination</option>
                            <option value="Harassment" {{ old('category') == 'Harassment' ? 'selected' : '' }}>Harassment</option>
                            <option value="Safety Violation" {{ old('category') == 'Safety Violation' ? 'selected' : '' }}>Safety Violation</option>
                            <option value="Theft" {{ old('category') == 'Theft' ? 'selected' : '' }}>Theft</option>
                            <option value="Substance Abuse" {{ old('category') == 'Substance Abuse' ? 'selected' : '' }}>Substance Abuse</option>
                            <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="incident_date" class="block text-sm font-medium text-foreground mb-1">Incident Date *</label>
                        <input type="date" name="incident_date" id="incident_date" value="{{ old('incident_date') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('incident_date') border-red-500 @enderror">
                        @error('incident_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="action_date" class="block text-sm font-medium text-foreground mb-1">Action Date *</label>
                        <input type="date" name="action_date" id="action_date" value="{{ old('action_date', now()->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('action_date') border-red-500 @enderror">
                        @error('action_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-foreground mb-1">Description of Incident *</label>
                    <textarea name="description" id="description" rows="4" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('description') border-red-500 @enderror" placeholder="Describe what happened in detail...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="evidence" class="block text-sm font-medium text-foreground mb-1">Evidence / Supporting Documentation</label>
                    <textarea name="evidence" id="evidence" rows="2" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="List any evidence or documentation...">{{ old('evidence') }}</textarea>
                </div>

                <div>
                    <label for="corrective_action" class="block text-sm font-medium text-foreground mb-1">Required Corrective Action</label>
                    <textarea name="corrective_action" id="corrective_action" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="What must the employee do to correct this behavior?">{{ old('corrective_action') }}</textarea>
                </div>

                <!-- Suspension Fields -->
                <div id="suspension-fields" class="hidden border-t border-border pt-6">
                    <h4 class="font-medium text-foreground mb-4">Suspension Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="suspension_start" class="block text-sm font-medium text-foreground mb-1">Start Date</label>
                            <input type="date" name="suspension_start" id="suspension_start" value="{{ old('suspension_start') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label for="suspension_end" class="block text-sm font-medium text-foreground mb-1">End Date</label>
                            <input type="date" name="suspension_end" id="suspension_end" value="{{ old('suspension_end') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-1">Pay Status</label>
                            <div class="flex items-center gap-4 mt-2">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="with_pay" value="1" {{ old('with_pay') == '1' ? 'checked' : '' }} class="h-4 w-4 border-border text-primary focus:ring-primary/20">
                                    <span class="text-sm">With Pay</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="with_pay" value="0" {{ old('with_pay') == '0' ? 'checked' : '' }} class="h-4 w-4 border-border text-primary focus:ring-primary/20">
                                    <span class="text-sm">Without Pay</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PIP Fields -->
                <div id="pip-fields" class="hidden border-t border-border pt-6">
                    <h4 class="font-medium text-foreground mb-4">Performance Improvement Plan Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="pip_start_date" class="block text-sm font-medium text-foreground mb-1">PIP Start Date</label>
                            <input type="date" name="pip_start_date" id="pip_start_date" value="{{ old('pip_start_date') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label for="pip_end_date" class="block text-sm font-medium text-foreground mb-1">PIP End Date</label>
                            <input type="date" name="pip_end_date" id="pip_end_date" value="{{ old('pip_end_date') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                    </div>
                    <div>
                        <label for="pip_goals" class="block text-sm font-medium text-foreground mb-1">PIP Goals & Expectations</label>
                        <textarea name="pip_goals" id="pip_goals" rows="4" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="List specific, measurable goals the employee must achieve...">{{ old('pip_goals') }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="witnesses" class="block text-sm font-medium text-foreground mb-1">Witnesses</label>
                        <input type="text" name="witnesses" id="witnesses" value="{{ old('witnesses') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Names of witnesses (if any)">
                    </div>
                    <div>
                        <label for="follow_up_date" class="block text-sm font-medium text-foreground mb-1">Follow-up Date</label>
                        <input type="date" name="follow_up_date" id="follow_up_date" value="{{ old('follow_up_date') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="union_representative_present" value="1" {{ old('union_representative_present') ? 'checked' : '' }} class="h-4 w-4 rounded border-border text-primary focus:ring-primary/20">
                        <span class="text-sm text-foreground">Union representative present</span>
                    </label>
                </div>

                <div id="union-rep-name" class="hidden">
                    <label for="union_representative_name" class="block text-sm font-medium text-foreground mb-1">Union Representative Name</label>
                    <input type="text" name="union_representative_name" id="union_representative_name" value="{{ old('union_representative_name') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <h4 class="font-medium text-amber-900 text-sm mb-2">Progressive Discipline Policy</h4>
                    <p class="text-xs text-amber-800">Ensure you follow proper progressive discipline procedures: Verbal Warning → Written Warning → Final Warning → Suspension/Termination. Document each step thoroughly.</p>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('disciplinary.index') }}" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Create Disciplinary Action
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const typeSelect = document.getElementById('type');
        const suspensionFields = document.getElementById('suspension-fields');
        const pipFields = document.getElementById('pip-fields');
        const unionCheckbox = document.querySelector('[name="union_representative_present"]');
        const unionNameField = document.getElementById('union-rep-name');

        typeSelect.addEventListener('change', function() {
            suspensionFields.classList.toggle('hidden', this.value !== 'Suspension');
            pipFields.classList.toggle('hidden', this.value !== 'Performance Improvement Plan');
        });

        unionCheckbox.addEventListener('change', function() {
            unionNameField.classList.toggle('hidden', !this.checked);
        });

        // Initialize on page load
        if (typeSelect.value === 'Suspension') suspensionFields.classList.remove('hidden');
        if (typeSelect.value === 'Performance Improvement Plan') pipFields.classList.remove('hidden');
        if (unionCheckbox.checked) unionNameField.classList.remove('hidden');
    </script>
</x-app-layout>
