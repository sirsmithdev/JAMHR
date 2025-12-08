<x-app-layout>
    @section('title', 'Allowances Management')

    <div class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-serif text-foreground">Allowances Management</h1>
                <p class="text-muted-foreground mt-1">Manage employee allowances, company vehicles, and taxable benefits.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('allowances.vehicles.index') }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                    Company Vehicles
                </a>
                <a href="{{ route('allowances.types.index') }}" class="inline-flex items-center px-4 py-2 border border-border rounded-md text-sm font-medium text-foreground bg-white hover:bg-muted transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Allowance Types
                </a>
                <a href="{{ route('allowances.create') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 shadow-lg transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Assign Allowance
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-foreground">{{ $summary['employee_count'] ?? 0 }}</div>
                <div class="text-xs text-muted-foreground">Employees with Allowances</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-emerald-600">JMD {{ number_format($summary['total_monthly'] ?? 0, 0) }}</div>
                <div class="text-xs text-muted-foreground">Monthly Total</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-blue-600">JMD {{ number_format($summary['total_annual'] ?? 0, 0) }}</div>
                <div class="text-xs text-muted-foreground">Annual Total</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="text-2xl font-bold text-red-600">JMD {{ number_format($summary['taxable_amount'] ?? 0, 0) }}</div>
                <div class="text-xs text-muted-foreground">Taxable Amount</div>
            </div>
        </div>

        <!-- Allowances by Category -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($byCategory ?? [] as $category => $data)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold capitalize">{{ str_replace('_', ' ', $category) }}</h3>
                    <span class="text-sm text-muted-foreground">{{ $data['count'] }} allowances</span>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Monthly Total:</span>
                        <span class="font-medium">JMD {{ number_format($data['monthly_total'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Taxable:</span>
                        <span class="font-medium text-red-600">JMD {{ number_format($data['taxable_total'], 2) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Allowances Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-border">
                <h2 class="font-serif font-semibold">Employee Allowances</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-muted/30">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Employee</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Allowance Type</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Category</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Monthly</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Taxable</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($allowances ?? [] as $allowance)
                        <tr class="hover:bg-muted/5">
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $allowance->employee->full_name }}</div>
                                <div class="text-sm text-muted-foreground">{{ $allowance->employee->department }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $allowance->allowanceType->name }}</td>
                            <td class="px-6 py-4">
                                <span class="capitalize">{{ str_replace('_', ' ', $allowance->allowanceType->category) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium">JMD {{ number_format($allowance->monthly_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                @if($allowance->taxable_amount > 0)
                                <span class="text-red-600">JMD {{ number_format($allowance->taxable_amount, 2) }}</span>
                                @else
                                <span class="text-emerald-600">Non-taxable</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $allowance->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $allowance->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('allowances.edit', $allowance) }}" class="text-muted-foreground hover:text-foreground">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-muted-foreground">
                                No allowances found. <a href="{{ route('allowances.create') }}" class="text-primary hover:underline">Assign an allowance</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($allowances) && $allowances->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $allowances->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
