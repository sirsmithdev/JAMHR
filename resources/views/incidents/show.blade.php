<x-app-layout>
    @section('title', 'View Incident')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('incidents.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Incidents
                </a>
                <h1 class="text-3xl font-serif text-foreground">{{ $incident->title }}</h1>
                <p class="text-muted-foreground mt-1">Incident Report #{{ $incident->id }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $incident->severity_badge_class }}">
                    {{ $incident->severity }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $incident->status_badge_class }}">
                    {{ $incident->status_label }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Incident Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Incident Details</h3>
                    <div class="prose prose-sm max-w-none text-muted-foreground">
                        <p>{{ $incident->description }}</p>
                    </div>
                </div>

                <!-- Resolution -->
                @if($incident->resolution)
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-6">
                    <h3 class="font-serif font-semibold text-emerald-900 mb-4">Resolution</h3>
                    <p class="text-emerald-800 text-sm">{{ $incident->resolution }}</p>
                    @if($incident->resolved_at)
                    <p class="text-emerald-600 text-xs mt-4">Resolved on {{ $incident->resolved_at->format('M d, Y \a\t h:i A') }}</p>
                    @endif
                </div>
                @endif

                <!-- Actions for Open Incidents -->
                @if($incident->status !== 'Resolved')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Update Status</h3>
                    <form action="{{ route('incidents.update', $incident) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="status" class="block text-sm font-medium text-foreground mb-1">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="Open" {{ $incident->status === 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Under Investigation" {{ $incident->status === 'Under Investigation' ? 'selected' : '' }}>Under Investigation</option>
                                <option value="Resolved" {{ $incident->status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>

                        <div>
                            <label for="resolution" class="block text-sm font-medium text-foreground mb-1">Resolution Notes</label>
                            <textarea name="resolution" id="resolution" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Describe how this incident was resolved...">{{ old('resolution', $incident->resolution) }}</textarea>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                            Update Incident
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Information</h3>
                    <dl class="space-y-4 text-sm">
                        <div>
                            <dt class="text-muted-foreground">Type</dt>
                            <dd class="font-medium mt-1">{{ $incident->type }}</dd>
                        </div>
                        <div>
                            <dt class="text-muted-foreground">Date Occurred</dt>
                            <dd class="font-medium mt-1">{{ $incident->date->format('M d, Y') }}</dd>
                        </div>
                        @if($incident->location)
                        <div>
                            <dt class="text-muted-foreground">Location</dt>
                            <dd class="font-medium mt-1">{{ $incident->location }}</dd>
                        </div>
                        @endif
                        @if($incident->employee)
                        <div>
                            <dt class="text-muted-foreground">Employee Involved</dt>
                            <dd class="font-medium mt-1">
                                <a href="{{ route('employees.show', $incident->employee) }}" class="text-primary hover:underline">
                                    {{ $incident->employee->full_name }}
                                </a>
                            </dd>
                        </div>
                        @endif
                        @if($incident->witnesses)
                        <div>
                            <dt class="text-muted-foreground">Witnesses</dt>
                            <dd class="font-medium mt-1">{{ $incident->witnesses }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-muted-foreground">Reported On</dt>
                            <dd class="font-medium mt-1">{{ $incident->created_at->format('M d, Y \a\t h:i A') }}</dd>
                        </div>
                        @if($incident->reporter)
                        <div>
                            <dt class="text-muted-foreground">Reported By</dt>
                            <dd class="font-medium mt-1">{{ $incident->reporter->name }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="h-2 w-2 rounded-full bg-primary mt-2"></div>
                            <div>
                                <p class="text-sm font-medium">Report Created</p>
                                <p class="text-xs text-muted-foreground">{{ $incident->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @if($incident->status === 'Under Investigation')
                        <div class="flex gap-3">
                            <div class="h-2 w-2 rounded-full bg-amber-500 mt-2"></div>
                            <div>
                                <p class="text-sm font-medium">Investigation Started</p>
                                <p class="text-xs text-muted-foreground">{{ $incident->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($incident->status === 'Resolved')
                        <div class="flex gap-3">
                            <div class="h-2 w-2 rounded-full bg-emerald-500 mt-2"></div>
                            <div>
                                <p class="text-sm font-medium">Resolved</p>
                                <p class="text-xs text-muted-foreground">{{ ($incident->resolved_at ?? $incident->updated_at)->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Delete Action -->
                <form action="{{ route('incidents.destroy', $incident) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this incident report?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 text-center border border-red-300 text-red-600 rounded-md hover:bg-red-50 transition-colors">
                        Delete Incident
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
