<x-app-layout>
    @section('title', 'Notification Settings')

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
                <form action="{{ route('settings.notifications.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Email Configuration -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Email Notifications</h3>
                            <div class="space-y-4">
                                <label class="flex items-center gap-3 cursor-pointer p-4 bg-gray-50 rounded-lg">
                                    <input type="checkbox" name="email_enabled" id="email_enabled" value="1"
                                           {{ ($settings['email_enabled'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary h-5 w-5">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">Enable Email Notifications</span>
                                        <p class="text-xs text-muted-foreground">Send email notifications for important events</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- HR & Workflow Notifications -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">HR & Workflow Notifications</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-start gap-3 cursor-pointer p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="leave_request_notify" id="leave_request_notify" value="1"
                                           {{ ($settings['leave_request_notify'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary mt-0.5">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">Leave Requests</span>
                                        <p class="text-xs text-muted-foreground">Notify managers when employees submit leave requests</p>
                                    </div>
                                </label>

                                <label class="flex items-start gap-3 cursor-pointer p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="leave_approval_notify" id="leave_approval_notify" value="1"
                                           {{ ($settings['leave_approval_notify'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary mt-0.5">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">Leave Approvals</span>
                                        <p class="text-xs text-muted-foreground">Notify employees when leave is approved or rejected</p>
                                    </div>
                                </label>

                                <label class="flex items-start gap-3 cursor-pointer p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="payroll_notify" id="payroll_notify" value="1"
                                           {{ ($settings['payroll_notify'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary mt-0.5">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">Payroll Processed</span>
                                        <p class="text-xs text-muted-foreground">Notify employees when payslips are ready</p>
                                    </div>
                                </label>

                                <label class="flex items-start gap-3 cursor-pointer p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="loan_notify" id="loan_notify" value="1"
                                           {{ ($settings['loan_notify'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary mt-0.5">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">Loan Applications</span>
                                        <p class="text-xs text-muted-foreground">Notify on loan application status changes</p>
                                    </div>
                                </label>

                                <label class="flex items-start gap-3 cursor-pointer p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="appraisal_notify" id="appraisal_notify" value="1"
                                           {{ ($settings['appraisal_notify'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary mt-0.5">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">Performance Appraisals</span>
                                        <p class="text-xs text-muted-foreground">Notify when performance reviews are scheduled or completed</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Celebration Notifications -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Celebration Notifications</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-start gap-3 cursor-pointer p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="birthday_notify" id="birthday_notify" value="1"
                                           {{ ($settings['birthday_notify'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary mt-0.5">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">Birthday Reminders</span>
                                        <p class="text-xs text-muted-foreground">Send birthday notifications to team</p>
                                    </div>
                                </label>

                                <label class="flex items-start gap-3 cursor-pointer p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="anniversary_notify" id="anniversary_notify" value="1"
                                           {{ ($settings['anniversary_notify'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary mt-0.5">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">Work Anniversary Reminders</span>
                                        <p class="text-xs text-muted-foreground">Celebrate employee work anniversaries</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Compliance Reminders -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Compliance Reminders</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="compliance_remind_days" class="block text-sm font-medium text-gray-700 mb-1">Reminder Days Before Deadline *</label>
                                    <input type="number" name="compliance_remind_days" id="compliance_remind_days" value="{{ old('compliance_remind_days', $settings['compliance_remind_days'] ?? 7) }}" min="1" max="30" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <p class="text-xs text-muted-foreground mt-1">NIS, NHT, PAYE submission reminders</p>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Digest -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Admin Digest</h3>
                            <div>
                                <label for="digest_frequency" class="block text-sm font-medium text-gray-700 mb-1">Digest Email Frequency</label>
                                <select name="digest_frequency" id="digest_frequency"
                                        class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <option value="none" {{ ($settings['digest_frequency'] ?? '') === 'none' ? 'selected' : '' }}>None</option>
                                    <option value="daily" {{ ($settings['digest_frequency'] ?? 'daily') === 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ ($settings['digest_frequency'] ?? '') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                </select>
                                <p class="text-xs text-muted-foreground mt-1">Summary of HR activities sent to administrators</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                            Save Notification Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
