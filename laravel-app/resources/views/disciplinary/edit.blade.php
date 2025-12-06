<x-app-layout>
    @section('title', 'Edit Disciplinary Action')

    <div class="space-y-8">
        <div>
            <a href="{{ route('disciplinary.show', $disciplinary) }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Action
            </a>
            <h1 class="text-3xl font-serif text-foreground">Edit Disciplinary Action</h1>
            <p class="text-muted-foreground mt-1">Update the disciplinary record for {{ $disciplinary->employee->full_name }}.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
            <form action="{{ route('disciplinary.update', $disciplinary) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-foreground mb-1">Action Type *</label>
                        <select name="type" id="type" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach(['Verbal Warning', 'Written Warning', 'Final Written Warning', 'Suspension', 'Demotion', 'Performance Improvement Plan', 'Termination'] as $type)
                            <option value="{{ $type }}" {{ old('type', $disciplinary->type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-foreground mb-1">Category *</label>
                        <select name="category" id="category" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach(['Attendance', 'Performance', 'Conduct', 'Policy Violation', 'Insubordination', 'Harassment', 'Safety Violation', 'Theft', 'Substance Abuse', 'Other'] as $category)
                            <option value="{{ $category }}" {{ old('category', $disciplinary->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="incident_date" class="block text-sm font-medium text-foreground mb-1">Incident Date *</label>
                        <input type="date" name="incident_date" id="incident_date" value="{{ old('incident_date', $disciplinary->incident_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                    <div>
                        <label for="action_date" class="block text-sm font-medium text-foreground mb-1">Action Date *</label>
                        <input type="date" name="action_date" id="action_date" value="{{ old('action_date', $disciplinary->action_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-foreground mb-1">Description *</label>
                    <textarea name="description" id="description" rows="4" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('description', $disciplinary->description) }}</textarea>
                </div>

                <div>
                    <label for="evidence" class="block text-sm font-medium text-foreground mb-1">Evidence</label>
                    <textarea name="evidence" id="evidence" rows="2" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('evidence', $disciplinary->evidence) }}</textarea>
                </div>

                <div>
                    <label for="employee_response" class="block text-sm font-medium text-foreground mb-1">Employee Response</label>
                    <textarea name="employee_response" id="employee_response" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('employee_response', $disciplinary->employee_response) }}</textarea>
                </div>

                <div>
                    <label for="corrective_action" class="block text-sm font-medium text-foreground mb-1">Corrective Action</label>
                    <textarea name="corrective_action" id="corrective_action" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('corrective_action', $disciplinary->corrective_action) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-foreground mb-1">Status *</label>
                        <select name="status" id="status" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach(['Open', 'Under Review', 'Resolved', 'Appealed', 'Overturned'] as $status)
                            <option value="{{ $status }}" {{ old('status', $disciplinary->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="follow_up_date" class="block text-sm font-medium text-foreground mb-1">Follow-up Date</label>
                        <input type="date" name="follow_up_date" id="follow_up_date" value="{{ old('follow_up_date', $disciplinary->follow_up_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                <div>
                    <label for="follow_up_notes" class="block text-sm font-medium text-foreground mb-1">Follow-up Notes</label>
                    <textarea name="follow_up_notes" id="follow_up_notes" rows="2" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('follow_up_notes', $disciplinary->follow_up_notes) }}</textarea>
                </div>

                @if($disciplinary->status === 'Appealed')
                <div>
                    <label for="appeal_notes" class="block text-sm font-medium text-foreground mb-1">Appeal Notes</label>
                    <textarea name="appeal_notes" id="appeal_notes" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('appeal_notes', $disciplinary->appeal_notes) }}</textarea>
                </div>
                @endif

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('disciplinary.show', $disciplinary) }}" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Update Action
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
