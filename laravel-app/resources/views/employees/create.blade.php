<x-app-layout>
    @section('title', 'Add Employee')

    <div class="space-y-8">
        <div>
            <h1 class="text-3xl font-serif text-foreground">Add New Employee</h1>
            <p class="text-muted-foreground mt-1">Create a new employee record and user account.</p>
        </div>

        <form action="{{ route('employees.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="first_name" class="block text-sm font-medium text-foreground">First Name *</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('first_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="last_name" class="block text-sm font-medium text-foreground">Last Name *</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('last_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-foreground">Email Address *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="role" class="block text-sm font-medium text-foreground">Role *</label>
                    <select name="role" id="role" required class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="job_title" class="block text-sm font-medium text-foreground">Job Title</label>
                    <input type="text" name="job_title" id="job_title" value="{{ old('job_title') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('job_title') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="department" class="block text-sm font-medium text-foreground">Department</label>
                    <input type="text" name="department" id="department" value="{{ old('department') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('department') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="trn_number" class="block text-sm font-medium text-foreground">TRN (Tax Registration Number)</label>
                    <input type="text" name="trn_number" id="trn_number" value="{{ old('trn_number') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('trn_number') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="nis_number" class="block text-sm font-medium text-foreground">NIS Number</label>
                    <input type="text" name="nis_number" id="nis_number" value="{{ old('nis_number') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('nis_number') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="start_date" class="block text-sm font-medium text-foreground">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('start_date') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="salary_annual" class="block text-sm font-medium text-foreground">Annual Salary (JMD)</label>
                    <input type="number" name="salary_annual" id="salary_annual" value="{{ old('salary_annual') }}" step="0.01" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    @error('salary_annual') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-border">
                <a href="{{ route('employees.index') }}" class="px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground hover:bg-muted transition-colors">Cancel</a>
                <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 transition-colors">Create Employee</button>
            </div>
        </form>
    </div>
</x-app-layout>
