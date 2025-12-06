<x-app-layout>
    @section('title', 'Scheduling')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Scheduling</h1>
                <p class="text-muted-foreground mt-1">Manage employee shifts and work schedules.</p>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('scheduling.publish') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="week_start" value="{{ $weekStart->format('Y-m-d') }}">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                        Publish Week
                    </button>
                </form>
                <button onclick="openAddShiftModal()" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Shift
                </button>
            </div>
        </div>

        <!-- Week Navigation -->
        <div class="bg-white rounded-lg shadow-sm p-4 flex items-center justify-between">
            <a href="{{ route('scheduling.index', ['week_start' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}" class="p-2 hover:bg-muted rounded-md transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div class="text-center">
                <div class="font-semibold text-foreground">
                    {{ $weekStart->format('M d') }} - {{ $weekEnd->format('M d, Y') }}
                </div>
                <div class="text-sm text-muted-foreground">Week {{ $weekStart->weekOfYear }}</div>
            </div>
            <a href="{{ route('scheduling.index', ['week_start' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}" class="p-2 hover:bg-muted rounded-md transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </div>

        <!-- Schedule Grid -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px]">
                    <thead class="bg-muted/30">
                        <tr>
                            <th class="text-left px-4 py-3 text-sm font-medium text-muted-foreground w-48">Employee</th>
                            @foreach($days as $day)
                            <th class="text-center px-2 py-3 text-sm font-medium text-muted-foreground {{ $day['date']->isToday() ? 'bg-primary/10' : '' }}">
                                <div>{{ $day['date']->format('D') }}</div>
                                <div class="text-xs">{{ $day['date']->format('M d') }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($employees as $employee)
                        <tr class="hover:bg-muted/5">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary text-xs font-medium">
                                        {{ $employee->initials }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-sm">{{ $employee->full_name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $employee->department }}</div>
                                    </div>
                                </div>
                            </td>
                            @foreach($days as $day)
                            @php
                                $key = $employee->id . '-' . $day['date']->format('Y-m-d');
                                $dayShifts = $shifts->get($key, collect());
                            @endphp
                            <td class="px-2 py-2 text-center {{ $day['date']->isToday() ? 'bg-primary/5' : '' }}">
                                @if($dayShifts->count() > 0)
                                    @foreach($dayShifts as $shift)
                                    <div class="text-xs p-1.5 rounded {{ $shift->is_published ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }} mb-1">
                                        <div class="font-medium">
                                            {{ \Carbon\Carbon::parse($shift->start_time)->format('g:i') }}-{{ \Carbon\Carbon::parse($shift->end_time)->format('g:i') }}
                                        </div>
                                        @if($shift->type)
                                        <div class="text-[10px] opacity-75">{{ $shift->type }}</div>
                                        @endif
                                    </div>
                                    @endforeach
                                @else
                                <button
                                    onclick="openAddShiftModal('{{ $employee->id }}', '{{ $day['date']->format('Y-m-d') }}')"
                                    class="w-full h-8 border border-dashed border-border rounded hover:border-primary hover:bg-primary/5 transition-colors text-muted-foreground hover:text-primary"
                                >
                                    <svg class="h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($days) + 1 }}" class="px-6 py-12 text-center text-muted-foreground">
                                No employees found. <a href="{{ route('employees.create') }}" class="text-primary hover:underline">Add employees</a> to start scheduling.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend -->
        <div class="flex items-center gap-6 text-sm">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded bg-emerald-100 border border-emerald-300"></div>
                <span class="text-muted-foreground">Published</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded bg-amber-100 border border-amber-300"></div>
                <span class="text-muted-foreground">Draft</span>
            </div>
        </div>
    </div>

    <!-- Add Shift Modal -->
    <div id="add-shift-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 class="font-serif font-semibold">Add Shift</h3>
                <button onclick="closeAddShiftModal()" class="text-muted-foreground hover:text-foreground">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('scheduling.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Employee</label>
                    <select name="employee_id" id="modal-employee" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <option value="">Select employee...</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Date</label>
                    <input type="date" name="date" id="modal-date" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Start Time</label>
                        <input type="time" name="start_time" value="09:00" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">End Time</label>
                        <input type="time" name="end_time" value="17:00" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-foreground mb-1">Shift Type (Optional)</label>
                    <select name="type" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <option value="">Regular</option>
                        <option value="Morning">Morning</option>
                        <option value="Evening">Evening</option>
                        <option value="Night">Night</option>
                        <option value="On-Call">On-Call</option>
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAddShiftModal()" class="flex-1 px-4 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Add Shift
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddShiftModal(employeeId = '', date = '') {
            document.getElementById('add-shift-modal').classList.remove('hidden');
            if (employeeId) {
                document.getElementById('modal-employee').value = employeeId;
            }
            if (date) {
                document.getElementById('modal-date').value = date;
            }
        }

        function closeAddShiftModal() {
            document.getElementById('add-shift-modal').classList.add('hidden');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddShiftModal();
            }
        });

        // Close modal on backdrop click
        document.getElementById('add-shift-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddShiftModal();
            }
        });
    </script>
</x-app-layout>
