<x-app-layout>
    @section('title', 'Report Incident')

    <div class="space-y-8">
        <div>
            <a href="{{ route('incidents.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Incidents
            </a>
            <h1 class="text-3xl font-serif text-foreground">Report Incident</h1>
            <p class="text-muted-foreground mt-1">Document workplace incidents for record keeping and resolution.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <form action="{{ route('incidents.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-foreground mb-1">Incident Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('title') border-red-500 @enderror" placeholder="Brief description of the incident">
                    @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-foreground mb-1">Incident Type</label>
                        <select name="type" id="type" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('type') border-red-500 @enderror">
                            <option value="">Select type...</option>
                            <option value="Safety" {{ old('type') == 'Safety' ? 'selected' : '' }}>Safety</option>
                            <option value="Conduct" {{ old('type') == 'Conduct' ? 'selected' : '' }}>Conduct</option>
                            <option value="Harassment" {{ old('type') == 'Harassment' ? 'selected' : '' }}>Harassment</option>
                            <option value="Theft" {{ old('type') == 'Theft' ? 'selected' : '' }}>Theft</option>
                            <option value="Property Damage" {{ old('type') == 'Property Damage' ? 'selected' : '' }}>Property Damage</option>
                            <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="severity" class="block text-sm font-medium text-foreground mb-1">Severity</label>
                        <select name="severity" id="severity" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('severity') border-red-500 @enderror">
                            <option value="">Select severity...</option>
                            <option value="Low" {{ old('severity') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('severity') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('severity') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Critical" {{ old('severity') == 'Critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('severity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="date" class="block text-sm font-medium text-foreground mb-1">Incident Date</label>
                        <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('date') border-red-500 @enderror">
                        @error('date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-foreground mb-1">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('location') border-red-500 @enderror" placeholder="Where did this occur?">
                        @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="employee_id" class="block text-sm font-medium text-foreground mb-1">Employee Involved (Optional)</label>
                    <select name="employee_id" id="employee_id" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('employee_id') border-red-500 @enderror">
                        <option value="">Select employee...</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name }} - {{ $employee->department }}
                        </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-foreground mb-1">Description</label>
                    <textarea name="description" id="description" rows="5" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('description') border-red-500 @enderror" placeholder="Provide a detailed description of what happened...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="witnesses" class="block text-sm font-medium text-foreground mb-1">Witnesses (Optional)</label>
                    <input type="text" name="witnesses" id="witnesses" value="{{ old('witnesses') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Names of any witnesses">
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <div>
                            <h4 class="font-medium text-amber-800 text-sm">Important Note</h4>
                            <p class="text-xs text-amber-700 mt-1">All incident reports are confidential and will be reviewed by HR. For emergencies, please contact your supervisor immediately.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('incidents.index') }}" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
