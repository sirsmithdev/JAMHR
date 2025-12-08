<x-app-layout>
    @section('title', 'Termination Details')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <a href="{{ route('terminations.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Terminations
                </a>
                <h1 class="text-3xl font-serif text-foreground">{{ $termination->employee->full_name }}</h1>
                <p class="text-muted-foreground mt-1">{{ $termination->type }} - {{ $termination->employee->department }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $termination->type_badge_class }}">
                    {{ $termination->type }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $termination->status_badge_class }}">
                    {{ $termination->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Termination Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Termination Details</h3>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-muted-foreground">Notice Date</dt>
                            <dd class="font-medium mt-1">{{ $termination->notice_date->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-muted-foreground">Last Working Day</dt>
                            <dd class="font-medium mt-1">{{ $termination->last_working_day->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-muted-foreground">Notice Period</dt>
                            <dd class="font-medium mt-1">{{ $termination->notice_period_days }} days</dd>
                        </div>
                        <div>
                            <dt class="text-muted-foreground">Eligible for Rehire</dt>
                            <dd class="font-medium mt-1">{{ $termination->eligible_for_rehire ? 'Yes' : 'No' }}</dd>
                        </div>
                    </dl>
                    @if($termination->reason)
                    <div class="mt-4 pt-4 border-t border-border">
                        <dt class="text-sm text-muted-foreground mb-1">Reason</dt>
                        <dd class="text-sm">{{ $termination->reason }}</dd>
                    </div>
                    @endif
                </div>

                <!-- Offboarding Checklist -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-serif font-semibold">Offboarding Checklist</h3>
                        <span class="text-sm text-muted-foreground">{{ $termination->clearance_progress }}% Complete</span>
                    </div>
                    <div class="w-full h-2 bg-muted rounded-full overflow-hidden mb-6">
                        <div class="h-full bg-primary transition-all" style="width: {{ $termination->clearance_progress }}%"></div>
                    </div>

                    <div class="space-y-3">
                        @php
                            $checklistItems = [
                                ['field' => 'exit_interview_completed', 'label' => 'Exit Interview Completed', 'checked' => $termination->exit_interview_completed],
                                ['field' => 'company_property_returned', 'label' => 'Company Property Returned', 'checked' => $termination->company_property_returned],
                                ['field' => 'access_revoked', 'label' => 'System Access Revoked', 'checked' => $termination->access_revoked],
                                ['field' => 'knowledge_transfer_complete', 'label' => 'Knowledge Transfer Complete', 'checked' => $termination->knowledge_transfer_complete],
                                ['field' => 'benefits_terminated', 'label' => 'Benefits Terminated', 'checked' => $termination->benefits_terminated],
                                ['field' => 'final_pay_processed', 'label' => 'Final Pay Processed', 'checked' => $termination->final_pay_processed],
                            ];
                        @endphp

                        @foreach($checklistItems as $item)
                        <form action="{{ route('terminations.checklist', $termination) }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            <input type="hidden" name="field" value="{{ $item['field'] }}">
                            <input type="hidden" name="value" value="{{ $item['checked'] ? '0' : '1' }}">
                            <button type="submit" class="flex items-center gap-3 w-full text-left hover:bg-muted/50 p-2 rounded-md transition-colors">
                                <div class="h-5 w-5 rounded border {{ $item['checked'] ? 'bg-emerald-500 border-emerald-500' : 'border-border' }} flex items-center justify-center">
                                    @if($item['checked'])
                                    <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    @endif
                                </div>
                                <span class="{{ $item['checked'] ? 'line-through text-muted-foreground' : 'text-foreground' }}">{{ $item['label'] }}</span>
                            </button>
                        </form>
                        @endforeach
                    </div>

                    @if(!$termination->exit_interview_completed)
                    <div class="mt-4 pt-4 border-t border-border">
                        <a href="{{ route('terminations.exit-interview', $termination) }}" class="inline-flex items-center text-sm text-primary hover:underline">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Conduct Exit Interview
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Jamaica Statutory Clearance -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-serif font-semibold">Statutory Clearance (Jamaica)</h3>
                        <span class="text-sm text-muted-foreground">{{ $termination->jamaica_clearance_progress }}% Complete</span>
                    </div>
                    <div class="w-full h-2 bg-muted rounded-full overflow-hidden mb-6">
                        <div class="h-full bg-secondary transition-all" style="width: {{ $termination->jamaica_clearance_progress }}%"></div>
                    </div>

                    <div class="space-y-3">
                        @php
                            $statutoryItems = [
                                ['field' => 'nht_clearance', 'label' => 'NHT Clearance Obtained', 'checked' => $termination->nht_clearance],
                                ['field' => 'nis_updated', 'label' => 'NIS Records Updated', 'checked' => $termination->nis_updated],
                                ['field' => 'tax_forms_issued', 'label' => 'Tax Forms Issued (P45)', 'checked' => $termination->tax_forms_issued],
                            ];
                        @endphp

                        @foreach($statutoryItems as $item)
                        <form action="{{ route('terminations.checklist', $termination) }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            <input type="hidden" name="field" value="{{ $item['field'] }}">
                            <input type="hidden" name="value" value="{{ $item['checked'] ? '0' : '1' }}">
                            <button type="submit" class="flex items-center gap-3 w-full text-left hover:bg-muted/50 p-2 rounded-md transition-colors">
                                <div class="h-5 w-5 rounded border {{ $item['checked'] ? 'bg-secondary border-secondary' : 'border-border' }} flex items-center justify-center">
                                    @if($item['checked'])
                                    <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    @endif
                                </div>
                                <span class="{{ $item['checked'] ? 'line-through text-muted-foreground' : 'text-foreground' }}">{{ $item['label'] }}</span>
                            </button>
                        </form>
                        @endforeach
                    </div>
                </div>

                <!-- Exit Interview Notes -->
                @if($termination->exit_interview_notes)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Exit Interview Notes</h3>
                    <p class="text-sm text-muted-foreground whitespace-pre-line">{{ $termination->exit_interview_notes }}</p>
                    @if($termination->exit_interview_date)
                    <p class="text-xs text-muted-foreground mt-4">Conducted on {{ $termination->exit_interview_date->format('M d, Y') }}</p>
                    @endif
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
                            {{ $termination->employee->initials }}
                        </div>
                        <div>
                            <div class="font-medium">{{ $termination->employee->full_name }}</div>
                            <div class="text-sm text-muted-foreground">{{ $termination->employee->job_title }}</div>
                        </div>
                    </div>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Department</dt>
                            <dd class="font-medium">{{ $termination->employee->department }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Hire Date</dt>
                            <dd class="font-medium">{{ $termination->employee->hire_date?->format('M d, Y') ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Final Pay Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">Final Pay Summary</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Final Salary</dt>
                            <dd class="font-medium">J${{ number_format($termination->final_salary ?? 0, 2) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Leave Payout</dt>
                            <dd class="font-medium">J${{ number_format($termination->unused_leave_payout ?? 0, 2) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Severance</dt>
                            <dd class="font-medium">J${{ number_format($termination->severance_pay ?? 0, 2) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-muted-foreground">Other Payments</dt>
                            <dd class="font-medium">J${{ number_format($termination->other_payments ?? 0, 2) }}</dd>
                        </div>
                        <div class="flex justify-between text-red-600">
                            <dt>Deductions</dt>
                            <dd class="font-medium">-J${{ number_format($termination->deductions ?? 0, 2) }}</dd>
                        </div>
                        <div class="pt-2 border-t border-border flex justify-between font-semibold">
                            <dt>Total Final Pay</dt>
                            <dd>J${{ number_format($termination->total_final_pay ?? 0, 2) }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 space-y-3">
                    <a href="{{ route('terminations.edit', $termination) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Edit Details
                    </a>
                    <form action="{{ route('terminations.destroy', $termination) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this termination record?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-md hover:bg-red-50 transition-colors">
                            Delete Record
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
