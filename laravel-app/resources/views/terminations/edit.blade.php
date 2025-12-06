<x-app-layout>
    @section('title', 'Edit Termination')

    <div class="space-y-8">
        <div>
            <a href="{{ route('terminations.show', $termination) }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Termination
            </a>
            <h1 class="text-3xl font-serif text-foreground">Edit Termination</h1>
            <p class="text-muted-foreground mt-1">Update termination details for {{ $termination->employee->full_name }}.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
            <form action="{{ route('terminations.update', $termination) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-foreground mb-1">Termination Type *</label>
                        <select name="type" id="type" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach(['Resignation', 'Termination', 'Redundancy', 'End of Contract', 'Retirement', 'Mutual Agreement', 'Dismissal', 'Death'] as $type)
                            <option value="{{ $type }}" {{ old('type', $termination->type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-foreground mb-1">Status *</label>
                        <select name="status" id="status" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach(['Pending', 'In Progress', 'Completed', 'Cancelled'] as $status)
                            <option value="{{ $status }}" {{ old('status', $termination->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="notice_date" class="block text-sm font-medium text-foreground mb-1">Notice Date *</label>
                        <input type="date" name="notice_date" id="notice_date" value="{{ old('notice_date', $termination->notice_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label for="last_working_day" class="block text-sm font-medium text-foreground mb-1">Last Working Day *</label>
                        <input type="date" name="last_working_day" id="last_working_day" value="{{ old('last_working_day', $termination->last_working_day->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                <div>
                    <label for="reason" class="block text-sm font-medium text-foreground mb-1">Reason for Departure</label>
                    <textarea name="reason" id="reason" rows="3" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('reason', $termination->reason) }}</textarea>
                </div>

                <!-- Final Pay Section -->
                <div class="border-t border-border pt-6">
                    <h3 class="font-serif font-semibold mb-4">Final Pay Calculation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="final_salary" class="block text-sm font-medium text-foreground mb-1">Final Salary (JMD)</label>
                            <input type="number" name="final_salary" id="final_salary" value="{{ old('final_salary', $termination->final_salary) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label for="unused_leave_payout" class="block text-sm font-medium text-foreground mb-1">Unused Leave Payout (JMD)</label>
                            <input type="number" name="unused_leave_payout" id="unused_leave_payout" value="{{ old('unused_leave_payout', $termination->unused_leave_payout) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label for="severance_pay" class="block text-sm font-medium text-foreground mb-1">Severance Pay (JMD)</label>
                            <input type="number" name="severance_pay" id="severance_pay" value="{{ old('severance_pay', $termination->severance_pay) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label for="other_payments" class="block text-sm font-medium text-foreground mb-1">Other Payments (JMD)</label>
                            <input type="number" name="other_payments" id="other_payments" value="{{ old('other_payments', $termination->other_payments) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label for="deductions" class="block text-sm font-medium text-foreground mb-1">Deductions (JMD)</label>
                            <input type="number" name="deductions" id="deductions" value="{{ old('deductions', $termination->deductions) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                    </div>
                </div>

                <!-- Rehire Eligibility -->
                <div class="border-t border-border pt-6">
                    <div class="flex items-center gap-2 mb-4">
                        <input type="checkbox" name="eligible_for_rehire" id="eligible_for_rehire" value="1" {{ old('eligible_for_rehire', $termination->eligible_for_rehire) ? 'checked' : '' }} class="h-4 w-4 rounded border-border text-primary focus:ring-primary/20">
                        <label for="eligible_for_rehire" class="text-sm font-medium text-foreground">Eligible for rehire</label>
                    </div>
                    <div>
                        <label for="rehire_notes" class="block text-sm font-medium text-foreground mb-1">Rehire Notes</label>
                        <textarea name="rehire_notes" id="rehire_notes" rows="2" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('rehire_notes', $termination->rehire_notes) }}</textarea>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('terminations.show', $termination) }}" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Update Termination
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
