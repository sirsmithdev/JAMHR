<x-app-layout>
    @section('title', 'Payroll & Tax Settings')

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
                <form action="{{ route('settings.payroll.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Payroll Configuration -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Payroll Configuration</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="pay_frequency" class="block text-sm font-medium text-gray-700 mb-1">Pay Frequency *</label>
                                    <select name="pay_frequency" id="pay_frequency" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="weekly" {{ ($settings['pay_frequency'] ?? '') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="bi-weekly" {{ ($settings['pay_frequency'] ?? '') === 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                        <option value="semi-monthly" {{ ($settings['pay_frequency'] ?? '') === 'semi-monthly' ? 'selected' : '' }}>Semi-Monthly</option>
                                        <option value="monthly" {{ ($settings['pay_frequency'] ?? 'monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="pay_day" class="block text-sm font-medium text-gray-700 mb-1">Pay Day *</label>
                                    <select name="pay_day" id="pay_day" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="last" {{ ($settings['pay_day'] ?? 'last') === 'last' ? 'selected' : '' }}>Last Day of Month</option>
                                        <option value="15" {{ ($settings['pay_day'] ?? '') === '15' ? 'selected' : '' }}>15th</option>
                                        <option value="friday" {{ ($settings['pay_day'] ?? '') === 'friday' ? 'selected' : '' }}>Friday</option>
                                        @for($i = 1; $i <= 28; $i++)
                                        <option value="{{ $i }}" {{ ($settings['pay_day'] ?? '') === (string)$i ? 'selected' : '' }}>{{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label for="standard_hours_per_week" class="block text-sm font-medium text-gray-700 mb-1">Standard Hours/Week *</label>
                                    <input type="number" name="standard_hours_per_week" id="standard_hours_per_week" value="{{ old('standard_hours_per_week', $settings['standard_hours_per_week'] ?? 40) }}" min="20" max="60" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                            </div>
                        </div>

                        <!-- Currency Settings -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Currency</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency Code *</label>
                                    <select name="currency" id="currency" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="JMD" {{ ($settings['currency'] ?? 'JMD') === 'JMD' ? 'selected' : '' }}>JMD - Jamaican Dollar</option>
                                        <option value="USD" {{ ($settings['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="currency_symbol" class="block text-sm font-medium text-gray-700 mb-1">Currency Symbol *</label>
                                    <input type="text" name="currency_symbol" id="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol'] ?? '$') }}" maxlength="5" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                            </div>
                        </div>

                        <!-- Overtime Rates -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Overtime Rates</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="overtime_rate" class="block text-sm font-medium text-gray-700 mb-1">Overtime Rate (multiplier) *</label>
                                    <input type="number" name="overtime_rate" id="overtime_rate" value="{{ old('overtime_rate', $settings['overtime_rate'] ?? 1.5) }}" min="1" max="5" step="0.1" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <p class="text-xs text-muted-foreground mt-1">e.g., 1.5 = time and a half</p>
                                </div>
                                <div>
                                    <label for="double_time_rate" class="block text-sm font-medium text-gray-700 mb-1">Double Time Rate (multiplier) *</label>
                                    <input type="number" name="double_time_rate" id="double_time_rate" value="{{ old('double_time_rate', $settings['double_time_rate'] ?? 2.0) }}" min="1" max="5" step="0.1" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <p class="text-xs text-muted-foreground mt-1">e.g., 2.0 = double time</p>
                                </div>
                            </div>
                        </div>

                        <!-- Jamaica Statutory Deductions -->
                        <div class="bg-amber-50 rounded-lg p-6 border border-amber-200">
                            <h3 class="text-lg font-semibold text-amber-800 mb-4">Jamaica Statutory Deductions</h3>
                            <p class="text-sm text-amber-700 mb-6">These rates are set by the Government of Jamaica. Update only when official rates change.</p>

                            <div class="space-y-6">
                                <!-- PAYE -->
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">PAYE (Income Tax)</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label for="paye_threshold_annual" class="block text-sm font-medium text-gray-700 mb-1">Annual Threshold (JMD)</label>
                                            <input type="number" name="paye_threshold_annual" id="paye_threshold_annual" value="{{ old('paye_threshold_annual', $settings['paye_threshold_annual'] ?? 1500096) }}" step="1" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        </div>
                                        <div>
                                            <label for="paye_rate" class="block text-sm font-medium text-gray-700 mb-1">Standard Rate (%)</label>
                                            <input type="number" name="paye_rate" id="paye_rate" value="{{ old('paye_rate', ($settings['paye_rate'] ?? 0.25) * 100) }}" min="0" max="100" step="0.01" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                                                   onchange="this.value = this.value / 100" data-percent>
                                            <input type="hidden" name="paye_rate" value="{{ $settings['paye_rate'] ?? 0.25 }}">
                                        </div>
                                        <div>
                                            <label for="paye_rate_higher" class="block text-sm font-medium text-gray-700 mb-1">Higher Rate (%)</label>
                                            <input type="number" name="paye_rate_higher" id="paye_rate_higher" value="{{ old('paye_rate_higher', ($settings['paye_rate_higher'] ?? 0.30) * 100) }}" min="0" max="100" step="0.01" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label for="paye_higher_threshold" class="block text-sm font-medium text-gray-700 mb-1">Higher Rate Threshold (JMD)</label>
                                        <input type="number" name="paye_higher_threshold" id="paye_higher_threshold" value="{{ old('paye_higher_threshold', $settings['paye_higher_threshold'] ?? 6000000) }}" step="1" required
                                               class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    </div>
                                </div>

                                <!-- NIS -->
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">NIS (National Insurance Scheme)</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label for="nis_rate_employee" class="block text-sm font-medium text-gray-700 mb-1">Employee Rate (%)</label>
                                            <input type="number" name="nis_rate_employee" id="nis_rate_employee" value="{{ old('nis_rate_employee', ($settings['nis_rate_employee'] ?? 0.03) * 100) }}" min="0" max="100" step="0.01" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        </div>
                                        <div>
                                            <label for="nis_rate_employer" class="block text-sm font-medium text-gray-700 mb-1">Employer Rate (%)</label>
                                            <input type="number" name="nis_rate_employer" id="nis_rate_employer" value="{{ old('nis_rate_employer', ($settings['nis_rate_employer'] ?? 0.03) * 100) }}" min="0" max="100" step="0.01" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        </div>
                                        <div>
                                            <label for="nis_ceiling_weekly" class="block text-sm font-medium text-gray-700 mb-1">Weekly Wage Ceiling (JMD)</label>
                                            <input type="number" name="nis_ceiling_weekly" id="nis_ceiling_weekly" value="{{ old('nis_ceiling_weekly', $settings['nis_ceiling_weekly'] ?? 5000) }}" step="1" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        </div>
                                    </div>
                                </div>

                                <!-- NHT -->
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">NHT (National Housing Trust)</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="nht_rate_employee" class="block text-sm font-medium text-gray-700 mb-1">Employee Rate (%)</label>
                                            <input type="number" name="nht_rate_employee" id="nht_rate_employee" value="{{ old('nht_rate_employee', ($settings['nht_rate_employee'] ?? 0.02) * 100) }}" min="0" max="100" step="0.01" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        </div>
                                        <div>
                                            <label for="nht_rate_employer" class="block text-sm font-medium text-gray-700 mb-1">Employer Rate (%)</label>
                                            <input type="number" name="nht_rate_employer" id="nht_rate_employer" value="{{ old('nht_rate_employer', ($settings['nht_rate_employer'] ?? 0.03) * 100) }}" min="0" max="100" step="0.01" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        </div>
                                    </div>
                                </div>

                                <!-- Education Tax & HEART -->
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-3">Other Statutory Contributions</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="education_tax_rate" class="block text-sm font-medium text-gray-700 mb-1">Education Tax Rate (%)</label>
                                            <input type="number" name="education_tax_rate" id="education_tax_rate" value="{{ old('education_tax_rate', ($settings['education_tax_rate'] ?? 0.0225) * 100) }}" min="0" max="100" step="0.01" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        </div>
                                        <div>
                                            <label for="heart_rate" class="block text-sm font-medium text-gray-700 mb-1">HEART/NSTA Rate (%)</label>
                                            <input type="number" name="heart_rate" id="heart_rate" value="{{ old('heart_rate', ($settings['heart_rate'] ?? 0.03) * 100) }}" min="0" max="100" step="0.01" required
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                            <p class="text-xs text-muted-foreground mt-1">Employer contribution only</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Automation -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Automation</h3>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="auto_process_payroll" id="auto_process_payroll" value="1"
                                       {{ ($settings['auto_process_payroll'] ?? false) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700">Automatically process payroll on pay day</span>
                            </label>
                            <p class="text-xs text-muted-foreground mt-1 ml-6">When enabled, payroll will be automatically calculated and processed on the scheduled pay day.</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                            Save Payroll Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Convert percentage inputs to decimal values before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const percentFields = ['paye_rate', 'paye_rate_higher', 'nis_rate_employee', 'nis_rate_employer',
                                   'nht_rate_employee', 'nht_rate_employer', 'education_tax_rate', 'heart_rate'];
            percentFields.forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    input.value = parseFloat(input.value) / 100;
                }
            });
        });
    </script>
</x-app-layout>
