<x-app-layout>
    @section('title', 'Request Leave')

    <div class="space-y-8">
        <div>
            <a href="{{ route('leave.index') }}" class="text-sm text-muted-foreground hover:text-foreground inline-flex items-center gap-1 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Leave Management
            </a>
            <h1 class="text-3xl font-serif text-foreground">Request Leave</h1>
            <p class="text-muted-foreground mt-1">Submit a new leave request for approval.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <form action="{{ route('leave.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="employee_id" class="block text-sm font-medium text-foreground mb-1">Employee</label>
                    <select name="employee_id" id="employee_id" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('employee_id') border-red-500 @enderror">
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
                    <label for="leave_type" class="block text-sm font-medium text-foreground mb-1">Leave Type</label>
                    <select name="leave_type" id="leave_type" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('leave_type') border-red-500 @enderror">
                        <option value="">Select type...</option>
                        <option value="Annual" {{ old('leave_type') == 'Annual' ? 'selected' : '' }}>Annual Leave</option>
                        <option value="Sick" {{ old('leave_type') == 'Sick' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="Personal" {{ old('leave_type') == 'Personal' ? 'selected' : '' }}>Personal Leave</option>
                        <option value="Maternity" {{ old('leave_type') == 'Maternity' ? 'selected' : '' }}>Maternity Leave</option>
                        <option value="Paternity" {{ old('leave_type') == 'Paternity' ? 'selected' : '' }}>Paternity Leave</option>
                        <option value="Bereavement" {{ old('leave_type') == 'Bereavement' ? 'selected' : '' }}>Bereavement Leave</option>
                        <option value="Unpaid" {{ old('leave_type') == 'Unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                    </select>
                    @error('leave_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-foreground mb-1">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-foreground mb-1">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="reason" class="block text-sm font-medium text-foreground mb-1">Reason</label>
                    <textarea name="reason" id="reason" rows="4" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary @error('reason') border-red-500 @enderror" placeholder="Please provide a reason for your leave request...">{{ old('reason') }}</textarea>
                    @error('reason')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Leave Balance Info -->
                <div class="bg-muted/30 rounded-lg p-4">
                    <h4 class="font-medium text-sm mb-2">Leave Entitlements (Jamaica)</h4>
                    <ul class="text-xs text-muted-foreground space-y-1">
                        <li>Annual Leave: 10 working days (2 weeks) per year</li>
                        <li>Sick Leave: 10 days per year after first year of employment</li>
                        <li>Maternity Leave: 12 weeks (8 weeks paid)</li>
                    </ul>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('leave.index') }}" class="px-6 py-2 border border-border rounded-md text-foreground hover:bg-muted transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
