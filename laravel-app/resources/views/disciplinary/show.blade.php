<x-app-layout>
    @section('title', 'Disciplinary Action Details')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <a href="{{ route('disciplinary.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Disciplinary Actions
                </a>
                <h1 class="text-3xl font-serif text-foreground">{{ $disciplinary->type }}</h1>
                <p class="text-muted-foreground mt-1">{{ $disciplinary->employee->full_name }} - {{ $disciplinary->category }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $disciplinary->type_badge_class }}">
                    {{ $disciplinary->type }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $disciplinary->status_badge_class }}">
                    {{ $disciplinary->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Incident Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Incident Details</h3>
                    <dl class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <dt class="text-muted-foreground">Incident Date</dt>
                            <dd class="font-medium mt-1">{{ $disciplinary->incident_date->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-muted-foreground">Action Date</dt>
                            <dd class="font-medium mt-1">{{ $disciplinary->action_date->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-muted-foreground">Category</dt>
                            <dd class="mt-1">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $disciplinary->category_badge_class }}">
                                    {{ $disciplinary->category }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-muted-foreground">Severity Level</dt>
                            <dd class="font-medium mt-1">{{ $disciplinary->severity_level }} / 5</dd>
                        </div>
                    </dl>
                    <div class="pt-4 border-t border-border">
                        <dt class="text-sm text-muted-foreground mb-2">Description</dt>
                        <dd class="text-sm whitespace-pre-line">{{ $disciplinary->description }}</dd>
                    </div>
                </div>

                @if($disciplinary->evidence)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Evidence</h3>
                    <p class="text-sm text-muted-foreground whitespace-pre-line">{{ $disciplinary->evidence }}</p>
                </div>
                @endif

                @if($disciplinary->corrective_action)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Required Corrective Action</h3>
                    <p class="text-sm text-muted-foreground whitespace-pre-line">{{ $disciplinary->corrective_action }}</p>
                </div>
                @endif

                <!-- Suspension Details -->
                @if($disciplinary->type === 'Suspension' && $disciplinary->suspension_start)
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h3 class="font-serif font-semibold text-red-900 mb-4">Suspension Details</h3>
                    <dl class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <dt class="text-red-700">Start Date</dt>
                            <dd class="font-medium text-red-900 mt-1">{{ $disciplinary->suspension_start->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-red-700">End Date</dt>
                            <dd class="font-medium text-red-900 mt-1">{{ $disciplinary->suspension_end->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-red-700">Duration</dt>
                            <dd class="font-medium text-red-900 mt-1">{{ $disciplinary->suspension_days }} days {{ $disciplinary->with_pay ? '(with pay)' : '(without pay)' }}</dd>
                        </div>
                    </dl>
                </div>
                @endif

                <!-- PIP Details -->
                @if($disciplinary->type === 'Performance Improvement Plan')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-serif font-semibold text-blue-900">Performance Improvement Plan</h3>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $disciplinary->pip_outcome === 'Successful' ? 'bg-emerald-100 text-emerald-800' : ($disciplinary->pip_outcome === 'Failed' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $disciplinary->pip_outcome ?? 'Pending' }}
                        </span>
                    </div>
                    <dl class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <dt class="text-blue-700">Start Date</dt>
                            <dd class="font-medium text-blue-900 mt-1">{{ $disciplinary->pip_start_date?->format('M d, Y') ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-blue-700">End Date</dt>
                            <dd class="font-medium text-blue-900 mt-1">{{ $disciplinary->pip_end_date?->format('M d, Y') ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                    @if($disciplinary->pip_days_remaining !== null)
                    <div class="mb-4">
                        <div class="text-sm text-blue-700 mb-1">{{ $disciplinary->pip_days_remaining }} days remaining</div>
                        <div class="w-full h-2 bg-blue-200 rounded-full overflow-hidden">
                            @php
                                $totalDays = $disciplinary->pip_start_date->diffInDays($disciplinary->pip_end_date);
                                $elapsed = $totalDays - $disciplinary->pip_days_remaining;
                                $progress = $totalDays > 0 ? ($elapsed / $totalDays) * 100 : 0;
                            @endphp
                            <div class="h-full bg-blue-600 transition-all" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                    @endif
                    @if($disciplinary->pip_goals)
                    <div class="pt-4 border-t border-blue-200">
                        <dt class="text-sm text-blue-700 mb-2">Goals & Expectations</dt>
                        <dd class="text-sm text-blue-900 whitespace-pre-line">{{ $disciplinary->pip_goals }}</dd>
                    </div>
                    @endif

                    @if($disciplinary->pip_outcome === 'Pending')
                    <form action="{{ route('disciplinary.pip-outcome', $disciplinary) }}" method="POST" class="mt-4 pt-4 border-t border-blue-200">
                        @csrf
                        <div class="flex items-center gap-4">
                            <select name="pip_outcome" class="px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 text-sm">
                                <option value="Pending">Pending</option>
                                <option value="Successful">Successful</option>
                                <option value="Failed">Failed</option>
                                <option value="Extended">Extended</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                                Update Outcome
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
                @endif

                <!-- Employee Response -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Employee Response</h3>
                    @if($disciplinary->employee_response)
                    <p class="text-sm text-muted-foreground whitespace-pre-line">{{ $disciplinary->employee_response }}</p>
                    @else
                    <p class="text-sm text-muted-foreground mb-4">No response recorded yet.</p>
                    <form action="{{ route('disciplinary.response', $disciplinary) }}" method="POST">
                        @csrf
                        <textarea name="employee_response" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm mb-3" placeholder="Record the employee's response..."></textarea>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors text-sm">
                            Save Response
                        </button>
                    </form>
                    @endif
                </div>

                <!-- Acknowledgment -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-serif font-semibold">Employee Acknowledgment</h3>
                            @if($disciplinary->employee_acknowledged)
                            <p class="text-sm text-emerald-600 mt-1">
                                Acknowledged on {{ $disciplinary->acknowledged_at->format('M d, Y \a\t h:i A') }}
                            </p>
                            @else
                            <p class="text-sm text-muted-foreground mt-1">Not yet acknowledged</p>
                            @endif
                        </div>
                        @if(!$disciplinary->employee_acknowledged)
                        <form action="{{ route('disciplinary.acknowledge', $disciplinary) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition-colors text-sm">
                                Mark Acknowledged
                            </button>
                        </form>
                        @else
                        <svg class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @endif
                    </div>
                </div>

                <!-- Employee History -->
                @if($history->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Previous Disciplinary Actions</h3>
                    <div class="space-y-3">
                        @foreach($history as $item)
                        <a href="{{ route('disciplinary.show', $item) }}" class="block p-3 border border-border rounded-lg hover:bg-muted/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $item->type_badge_class }}">
                                        {{ $item->type }}
                                    </span>
                                    <span class="text-sm text-muted-foreground ml-2">{{ $item->category }}</span>
                                </div>
                                <span class="text-sm text-muted-foreground">{{ $item->action_date->format('M d, Y') }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <a href="{{ route('disciplinary.employee-history', $disciplinary->employee) }}" class="inline-block mt-4 text-sm text-primary hover:underline">
                        View full history →
                    </a>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Employee Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Employee</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium">
                            {{ $disciplinary->employee->initials }}
                        </div>
                        <div>
                            <div class="font-medium">{{ $disciplinary->employee->full_name }}</div>
                            <div class="text-sm text-muted-foreground">{{ $disciplinary->employee->job_title }}</div>
                        </div>
                    </div>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Department</dt>
                            <dd class="font-medium">{{ $disciplinary->employee->department }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Meta Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Details</h3>
                    <dl class="space-y-3 text-sm">
                        @if($disciplinary->witnesses)
                        <div>
                            <dt class="text-muted-foreground">Witnesses</dt>
                            <dd class="font-medium mt-1">{{ $disciplinary->witnesses }}</dd>
                        </div>
                        @endif
                        @if($disciplinary->union_representative_present)
                        <div>
                            <dt class="text-muted-foreground">Union Rep</dt>
                            <dd class="font-medium mt-1">{{ $disciplinary->union_representative_name ?? 'Present' }}</dd>
                        </div>
                        @endif
                        @if($disciplinary->follow_up_date)
                        <div>
                            <dt class="text-muted-foreground">Follow-up Date</dt>
                            <dd class="font-medium mt-1">{{ $disciplinary->follow_up_date->format('M d, Y') }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-muted-foreground">Issued By</dt>
                            <dd class="font-medium mt-1">{{ $disciplinary->issuer->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-muted-foreground">Created</dt>
                            <dd class="font-medium mt-1">{{ $disciplinary->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 space-y-3">
                    <a href="{{ route('disciplinary.edit', $disciplinary) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Edit Action
                    </a>
                    <form action="{{ route('disciplinary.destroy', $disciplinary) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this disciplinary action?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-md hover:bg-red-50 transition-colors">
                            Delete Action
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
