<x-app-layout>
    @section('title', 'View Appraisal')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('performance.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Performance
                </a>
                <h1 class="text-3xl font-serif text-foreground">Performance Appraisal</h1>
                <p class="text-muted-foreground mt-1">{{ $appraisal->review_period ?? 'Review' }} for {{ $appraisal->employee->full_name }}</p>
            </div>
            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $appraisal->status_badge_class }}">
                {{ $appraisal->status_label }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Employee Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-16 w-16 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xl font-medium">
                            {{ $appraisal->employee->initials }}
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold">{{ $appraisal->employee->full_name }}</h2>
                            <p class="text-muted-foreground">{{ $appraisal->employee->job_title }}</p>
                            <p class="text-sm text-muted-foreground">{{ $appraisal->employee->department }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ratings -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Performance Ratings</h3>
                    <div class="space-y-4">
                        @php
                            $ratings = [
                                'Productivity' => $appraisal->rating_productivity ?? 0,
                                'Quality of Work' => $appraisal->rating_quality ?? 0,
                                'Communication' => $appraisal->rating_communication ?? 0,
                                'Teamwork' => $appraisal->rating_teamwork ?? 0,
                                'Attendance' => $appraisal->rating_attendance ?? 0,
                            ];
                        @endphp
                        @foreach($ratings as $label => $rating)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-muted-foreground">{{ $label }}</span>
                                <span class="font-medium">{{ $rating }}/5</span>
                            </div>
                            <div class="w-full h-2 bg-muted rounded-full overflow-hidden">
                                <div class="h-full bg-primary transition-all" style="width: {{ ($rating / 5) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Feedback -->
                <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
                    @if($appraisal->strengths)
                    <div>
                        <h4 class="font-medium text-foreground mb-2">Strengths</h4>
                        <p class="text-muted-foreground text-sm">{{ $appraisal->strengths }}</p>
                    </div>
                    @endif

                    @if($appraisal->areas_for_improvement)
                    <div>
                        <h4 class="font-medium text-foreground mb-2">Areas for Improvement</h4>
                        <p class="text-muted-foreground text-sm">{{ $appraisal->areas_for_improvement }}</p>
                    </div>
                    @endif

                    @if($appraisal->goals_next_period)
                    <div>
                        <h4 class="font-medium text-foreground mb-2">Goals for Next Period</h4>
                        <p class="text-muted-foreground text-sm">{{ $appraisal->goals_next_period }}</p>
                    </div>
                    @endif

                    @if($appraisal->comments)
                    <div>
                        <h4 class="font-medium text-foreground mb-2">Additional Comments</h4>
                        <p class="text-muted-foreground text-sm">{{ $appraisal->comments }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Overall Score -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <h3 class="font-serif font-semibold mb-4">Overall Rating</h3>
                    <div class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-primary/10 mb-4">
                        <span class="text-3xl font-bold text-primary">{{ $appraisal->rating_overall }}</span>
                    </div>
                    <p class="text-muted-foreground text-sm">out of 5.0</p>
                    <div class="flex justify-center gap-1 mt-2">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="h-5 w-5 {{ $i <= round($appraisal->rating_overall) ? 'text-secondary fill-secondary' : 'text-muted' }}" viewBox="0 0 24 24">
                            <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                        @endfor
                    </div>
                </div>

                <!-- Goals Met -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Goals Achievement</h3>
                    <div class="relative pt-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-muted-foreground">Progress</span>
                            <span class="text-xs font-semibold text-primary">{{ $appraisal->goals_met_percentage ?? 0 }}%</span>
                        </div>
                        <div class="w-full h-3 bg-muted rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 transition-all" style="width: {{ $appraisal->goals_met_percentage ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Meta Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Details</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Review Period</dt>
                            <dd class="font-medium">{{ $appraisal->review_period ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Created</dt>
                            <dd class="font-medium">{{ $appraisal->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Reviewed By</dt>
                            <dd class="font-medium">{{ $appraisal->reviewer->name ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <a href="{{ route('performance.edit', $appraisal) }}" class="flex-1 px-4 py-2 text-center border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Edit
                    </a>
                    <button onclick="window.print()" class="flex-1 px-4 py-2 text-center bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
