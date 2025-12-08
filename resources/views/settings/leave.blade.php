<x-app-layout>
    @section('title', 'Leave Settings')

    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-serif text-foreground">Settings</h1>
            <p class="text-muted-foreground mt-1">Manage your organization's configuration and preferences.</p>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Settings Navigation -->
        <div class="bg-white rounded-lg shadow-sm">
            @include('settings.partials.tabs', ['activeTab' => $activeTab])

            <div class="p-6">
                <form action="{{ route('settings.leave.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Default Leave Entitlements -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Default Leave Entitlements</h3>
                            <p class="text-sm text-muted-foreground mb-4">These are the default leave days assigned to new employees. Individual entitlements can be adjusted per employee.</p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="annual_leave_days" class="block text-sm font-medium text-gray-700 mb-1">Annual/Vacation Leave (days) *</label>
                                    <input type="number" name="annual_leave_days" id="annual_leave_days" value="{{ old('annual_leave_days', $settings['annual_leave_days'] ?? 14) }}" min="0" max="60" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <p class="text-xs text-muted-foreground mt-1">Jamaica minimum: 10 days after 220 days service</p>
                                </div>
                                <div>
                                    <label for="sick_leave_days" class="block text-sm font-medium text-gray-700 mb-1">Sick Leave (days) *</label>
                                    <input type="number" name="sick_leave_days" id="sick_leave_days" value="{{ old('sick_leave_days', $settings['sick_leave_days'] ?? 10) }}" min="0" max="60" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label for="bereavement_leave_days" class="block text-sm font-medium text-gray-700 mb-1">Bereavement Leave (days) *</label>
                                    <input type="number" name="bereavement_leave_days" id="bereavement_leave_days" value="{{ old('bereavement_leave_days', $settings['bereavement_leave_days'] ?? 5) }}" min="0" max="14" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                            </div>
                        </div>

                        <!-- Parental Leave -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Parental Leave</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="maternity_leave_weeks" class="block text-sm font-medium text-gray-700 mb-1">Maternity Leave (weeks) *</label>
                                    <input type="number" name="maternity_leave_weeks" id="maternity_leave_weeks" value="{{ old('maternity_leave_weeks', $settings['maternity_leave_weeks'] ?? 12) }}" min="0" max="26" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <p class="text-xs text-muted-foreground mt-1">Jamaica law: 12 weeks (8 weeks paid)</p>
                                </div>
                                <div>
                                    <label for="paternity_leave_days" class="block text-sm font-medium text-gray-700 mb-1">Paternity Leave (days) *</label>
                                    <input type="number" name="paternity_leave_days" id="paternity_leave_days" value="{{ old('paternity_leave_days', $settings['paternity_leave_days'] ?? 5) }}" min="0" max="30" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                            </div>
                        </div>

                        <!-- Accrual Settings -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Leave Accrual</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="accrual_method" class="block text-sm font-medium text-gray-700 mb-1">Accrual Method *</label>
                                    <select name="accrual_method" id="accrual_method" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="annual" {{ ($settings['accrual_method'] ?? 'annual') === 'annual' ? 'selected' : '' }}>Annual (all at once)</option>
                                        <option value="monthly" {{ ($settings['accrual_method'] ?? '') === 'monthly' ? 'selected' : '' }}>Monthly accrual</option>
                                        <option value="bi-weekly" {{ ($settings['accrual_method'] ?? '') === 'bi-weekly' ? 'selected' : '' }}>Bi-weekly accrual</option>
                                    </select>
                                    <p class="text-xs text-muted-foreground mt-1">How leave is credited to employees</p>
                                </div>
                                <div>
                                    <label for="probation_period_months" class="block text-sm font-medium text-gray-700 mb-1">Probation Period (months) *</label>
                                    <input type="number" name="probation_period_months" id="probation_period_months" value="{{ old('probation_period_months', $settings['probation_period_months'] ?? 3) }}" min="0" max="12" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                            </div>
                        </div>

                        <!-- Carry Over Settings -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Leave Carry-Over</h3>
                            <div class="space-y-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="carry_over_enabled" id="carry_over_enabled" value="1"
                                           {{ ($settings['carry_over_enabled'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Allow unused leave to carry over to the next year</span>
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 ml-6">
                                    <div>
                                        <label for="carry_over_max_days" class="block text-sm font-medium text-gray-700 mb-1">Maximum Carry-Over Days *</label>
                                        <input type="number" name="carry_over_max_days" id="carry_over_max_days" value="{{ old('carry_over_max_days', $settings['carry_over_max_days'] ?? 5) }}" min="0" max="30" required
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    </div>
                                    <div>
                                        <label for="carry_over_expiry_months" class="block text-sm font-medium text-gray-700 mb-1">Expiry (months into new year) *</label>
                                        <input type="number" name="carry_over_expiry_months" id="carry_over_expiry_months" value="{{ old('carry_over_expiry_months', $settings['carry_over_expiry_months'] ?? 3) }}" min="0" max="12" required
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <p class="text-xs text-muted-foreground mt-1">0 = no expiry</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Approval Settings -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Approval & Policies</h3>
                            <div class="space-y-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="require_approval" id="require_approval" value="1"
                                           {{ ($settings['require_approval'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Require manager approval for leave requests</span>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="probation_leave_eligible" id="probation_leave_eligible" value="1"
                                           {{ ($settings['probation_leave_eligible'] ?? false) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Allow leave requests during probation period</span>
                                </label>

                                <div class="mt-4">
                                    <label for="min_notice_days" class="block text-sm font-medium text-gray-700 mb-1">Minimum Notice Days *</label>
                                    <input type="number" name="min_notice_days" id="min_notice_days" value="{{ old('min_notice_days', $settings['min_notice_days'] ?? 3) }}" min="0" max="30" required
                                           class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <p class="text-xs text-muted-foreground mt-1">How many days in advance leave must be requested (0 = no minimum)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                            Save Leave Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
