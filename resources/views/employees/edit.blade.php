<x-app-layout>
    @section('title', 'Edit Employee')

    <div class="space-y-8">
        <div>
            <h1 class="text-3xl font-serif text-foreground">Edit Employee</h1>
            <p class="text-muted-foreground mt-1">Update employee information for {{ $employee->full_name }}.</p>
        </div>

        <form action="{{ route('employees.update', $employee) }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="first_name" class="block text-sm font-medium text-foreground">First Name *</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $employee->first_name) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('first_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="last_name" class="block text-sm font-medium text-foreground">Last Name *</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $employee->last_name) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('last_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-foreground">Email Address *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $employee->user->email) }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="role" class="block text-sm font-medium text-foreground">Role *</label>
                    <select name="role" id="role" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <option value="employee" {{ old('role', $employee->user->role) === 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="manager" {{ old('role', $employee->user->role) === 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="admin" {{ old('role', $employee->user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="job_title" class="block text-sm font-medium text-foreground">Job Title</label>
                    <input type="text" name="job_title" id="job_title" value="{{ old('job_title', $employee->job_title) }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>

                <div class="space-y-2">
                    <label for="department" class="block text-sm font-medium text-foreground">Department</label>
                    <input type="text" name="department" id="department" value="{{ old('department', $employee->department) }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>

                <div class="space-y-2">
                    <label for="trn_number" class="block text-sm font-medium text-foreground">TRN (Tax Registration Number)</label>
                    <input type="text" name="trn_number" id="trn_number" value="{{ old('trn_number', $employee->trn_number) }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>

                <div class="space-y-2">
                    <label for="nis_number" class="block text-sm font-medium text-foreground">NIS Number</label>
                    <input type="text" name="nis_number" id="nis_number" value="{{ old('nis_number', $employee->nis_number) }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>

                <div class="space-y-2">
                    <label for="start_date" class="block text-sm font-medium text-foreground">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $employee->start_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>

                <div class="space-y-2">
                    <label for="salary_annual" class="block text-sm font-medium text-foreground">Annual Salary (JMD)</label>
                    <input type="number" name="salary_annual" id="salary_annual" value="{{ old('salary_annual', $employee->salary_annual) }}" step="0.01" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>
            </div>

            <!-- Pay Settings Section -->
            <div class="border-t border-border pt-6 mt-6">
                <h3 class="text-lg font-medium text-foreground mb-4">Pay Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="pay_frequency" class="block text-sm font-medium text-foreground">Pay Frequency</label>
                        <select name="pay_frequency" id="pay_frequency" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="monthly" {{ old('pay_frequency', $employee->pay_frequency) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="fortnightly" {{ old('pay_frequency', $employee->pay_frequency) === 'fortnightly' ? 'selected' : '' }}>Fortnightly</option>
                        </select>
                        <p class="text-xs text-muted-foreground">How often the employee is paid</p>
                        @error('pay_frequency') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="pay_type" class="block text-sm font-medium text-foreground">Pay Type</label>
                        <select name="pay_type" id="pay_type" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary" onchange="toggleFlexiRateField()">
                            <option value="salaried" {{ old('pay_type', $employee->pay_type) === 'salaried' ? 'selected' : '' }}>Salaried (Fixed Amount)</option>
                            <option value="hourly_from_salary" {{ old('pay_type', $employee->pay_type) === 'hourly_from_salary' ? 'selected' : '' }}>Hourly (Calculated from Salary)</option>
                            <option value="hourly_fixed" {{ old('pay_type', $employee->pay_type) === 'hourly_fixed' ? 'selected' : '' }}>Flexi-Hour (Fixed Hourly Rate)</option>
                        </select>
                        <p class="text-xs text-muted-foreground">How the employee's pay is calculated</p>
                        @error('pay_type') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2" id="flexi_rate_container" style="display: none;">
                        <label for="flexi_hourly_rate" class="block text-sm font-medium text-foreground">Flexi Hourly Rate (JMD)</label>
                        <input type="number" name="flexi_hourly_rate" id="flexi_hourly_rate" value="{{ old('flexi_hourly_rate', $employee->flexi_hourly_rate) }}" step="0.01" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <p class="text-xs text-muted-foreground">Fixed hourly rate for flexi-hour employees</p>
                        @error('flexi_hourly_rate') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="standard_hours_per_period" class="block text-sm font-medium text-foreground">Standard Hours Per Period</label>
                        <input type="number" name="standard_hours_per_period" id="standard_hours_per_period" value="{{ old('standard_hours_per_period', $employee->standard_hours_per_period) }}" step="0.01" placeholder="Auto-calculated if left blank" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <p class="text-xs text-muted-foreground">Monthly: 173.33 hrs | Fortnightly: 80 hrs (default)</p>
                        @error('standard_hours_per_period') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Pay Summary -->
                @if($employee->salary_annual)
                <div class="mt-6 p-4 bg-muted/50 rounded-lg">
                    <h4 class="text-sm font-medium text-foreground mb-2">Calculated Pay Summary</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-muted-foreground">Monthly Salary:</span>
                            <p class="font-medium">${{ number_format($employee->monthly_salary, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-muted-foreground">Fortnightly Salary:</span>
                            <p class="font-medium">${{ number_format($employee->fortnightly_salary, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-muted-foreground">Effective Hourly Rate:</span>
                            <p class="font-medium">${{ number_format($employee->effective_hourly_rate, 2) }}/hr</p>
                        </div>
                        <div>
                            <span class="text-muted-foreground">Standard Hours:</span>
                            <p class="font-medium">{{ $employee->getDefaultStandardHours() }} hrs/{{ $employee->pay_frequency === 'fortnightly' ? 'fortnight' : 'month' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-border">
                <a href="{{ route('employees.index') }}" class="px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground hover:bg-muted transition-colors">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 transition-colors">Save Changes</button>
            </div>
        </form>
    </div>

    <script>
        function toggleFlexiRateField() {
            const payType = document.getElementById('pay_type').value;
            const flexiContainer = document.getElementById('flexi_rate_container');

            if (payType === 'hourly_fixed') {
                flexiContainer.style.display = 'block';
            } else {
                flexiContainer.style.display = 'none';
            }
        }

        // Run on page load
        document.addEventListener('DOMContentLoaded', toggleFlexiRateField);
    </script>
</x-app-layout>
