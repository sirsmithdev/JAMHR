<x-app-layout>
    @section('title', 'Disciplinary History - ' . $employee->full_name)

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('disciplinary.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Disciplinary Actions
                </a>
                <h1 class="text-3xl font-serif text-foreground">Disciplinary History</h1>
                <div class="flex items-center gap-3 mt-2">
                    <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium">
                        {{ $employee->initials }}
                    </div>
                    <div>
                        <p class="font-medium">{{ $employee->full_name }}</p>
                        <p class="text-sm text-muted-foreground">{{ $employee->job_title }} - {{ $employee->department }}</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('disciplinary.create', $employee) }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Action
            </a>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-foreground">{{ $summary['total'] }}</div>
                <div class="text-xs text-muted-foreground">Total Actions</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-yellow-600">{{ $summary['verbal_warnings'] }}</div>
                <div class="text-xs text-muted-foreground">Verbal Warnings</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-amber-600">{{ $summary['written_warnings'] }}</div>
                <div class="text-xs text-muted-foreground">Written Warnings</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-orange-600">{{ $summary['final_warnings'] }}</div>
                <div class="text-xs text-muted-foreground">Final Warnings</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-red-600">{{ $summary['suspensions'] }}</div>
                <div class="text-xs text-muted-foreground">Suspensions</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $summary['pips'] }}</div>
                <div class="text-xs text-muted-foreground">PIPs</div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-serif font-semibold mb-6">Disciplinary Timeline</h3>
            @if($actions->count() > 0)
            <div class="space-y-6">
                @foreach($actions as $action)
                <div class="relative pl-8 pb-6 {{ !$loop->last ? 'border-l-2 border-border' : '' }}">
                    <div class="absolute -left-2 top-0 h-4 w-4 rounded-full {{ $action->status === 'Resolved' ? 'bg-emerald-500' : 'bg-primary' }}"></div>
                    <div class="bg-muted/30 rounded-lg p-4">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $action->type_badge_class }}">
                                {{ $action->type }}
                            </span>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $action->category_badge_class }}">
                                {{ $action->category }}
                            </span>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $action->status_badge_class }}">
                                {{ $action->status }}
                            </span>
                        </div>
                        <p class="text-sm text-muted-foreground mb-2">{{ Str::limit($action->description, 200) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-muted-foreground">{{ $action->action_date->format('M d, Y') }}</span>
                            <a href="{{ route('disciplinary.show', $action) }}" class="text-xs text-primary hover:underline">View details →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @if($actions->hasPages())
            <div class="mt-6 pt-6 border-t border-border">
                {{ $actions->links() }}
            </div>
            @endif
            @else
            <p class="text-muted-foreground text-center py-8">No disciplinary actions found for this employee.</p>
            @endif
        </div>
    </div>
</x-app-layout>
