<x-app-layout>
    @section('title', 'Employees')

    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Employee Directory</h1>
                <p class="text-muted-foreground mt-1">Manage your organization's workforce records.</p>
            </div>
            <a href="{{ route('employees.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Employee
            </a>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('employees.index') }}" class="flex gap-4">
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employees..." class="w-full pl-9 pr-4 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white">
            </div>
            <button type="submit" class="px-4 py-2 bg-white border border-border rounded-md hover:bg-muted transition-colors">Search</button>
        </form>

        <!-- Employee List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-muted/30 border-b border-border">
                    <tr>
                        <th class="text-left px-6 py-4 text-sm font-medium text-muted-foreground">Employee</th>
                        <th class="text-left px-6 py-4 text-sm font-medium text-muted-foreground">Role</th>
                        <th class="text-left px-6 py-4 text-sm font-medium text-muted-foreground">Department</th>
                        <th class="text-left px-6 py-4 text-sm font-medium text-muted-foreground">Start Date</th>
                        <th class="text-left px-6 py-4 text-sm font-medium text-muted-foreground">Status</th>
                        <th class="text-right px-6 py-4 text-sm font-medium text-muted-foreground">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($users as $user)
                    <tr class="hover:bg-muted/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium">
                                    {{ $user->employee ? $user->employee->initials : strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-foreground">{{ $user->name }}</div>
                                    <div class="text-sm text-muted-foreground">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-muted-foreground">{{ $user->employee?->job_title ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-muted-foreground">{{ $user->employee?->department ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-muted-foreground">{{ $user->employee?->start_date?->format('M d, Y') ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                @if($user->employee)
                                <a href="{{ route('employees.edit', $user->employee) }}" class="p-2 text-muted-foreground hover:text-primary transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-muted-foreground">
                            No employees found. <a href="{{ route('employees.create') }}" class="text-primary hover:underline">Add your first employee</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
