<x-app-layout>
    @section('title', 'Create Payroll')

    <div class="space-y-8">
        <div>
            <h1 class="text-3xl font-serif text-foreground">Create Payroll</h1>
            <p class="text-muted-foreground mt-1">Process a new payroll record with automatic tax calculations.</p>
        </div>

        <form action="{{ route('payroll.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="employee_id" class="block text-sm font-medium text-foreground">Employee *</label>
                    <select name="employee_id" id="employee_id" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->full_name }} - {{ $employee->job_title }}
                        </option>
                        @endforeach
                    </select>
                    @error('employee_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="gross_pay" class="block text-sm font-medium text-foreground">Gross Pay (JMD) *</label>
                    <input type="number" name="gross_pay" id="gross_pay" value="{{ old('gross_pay') }}" step="0.01" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('gross_pay') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="period_start" class="block text-sm font-medium text-foreground">Period Start *</label>
                    <input type="date" name="period_start" id="period_start" value="{{ old('period_start') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('period_start') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="period_end" class="block text-sm font-medium text-foreground">Period End *</label>
                    <input type="date" name="period_end" id="period_end" value="{{ old('period_end') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('period_end') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-800 mb-2">Automatic Tax Calculations</h4>
                <p class="text-sm text-blue-700">The following deductions will be automatically calculated based on Jamaican statutory rates:</p>
                <ul class="text-sm text-blue-700 mt-2 list-disc list-inside">
                    <li>NHT: 2% (Employee) + 3% (Employer)</li>
                    <li>NIS: 3% (Employee) + 3% (Employer) - capped at JMD $5M/year</li>
                    <li>Education Tax: 2.25% (Employee) + 3.5% (Employer)</li>
                    <li>HEART: 3% (Employer only)</li>
                    <li>Income Tax (PAYE): 25% on income above threshold</li>
                </ul>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-border">
                <a href="{{ route('payroll.index') }}" class="px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground hover:bg-muted transition-colors">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 transition-colors">Calculate & Create</button>
            </div>
        </form>
    </div>
</x-app-layout>
