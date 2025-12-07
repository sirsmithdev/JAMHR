<x-app-layout>
    @section('title', 'System Settings')

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
                <form action="{{ route('settings.system.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Regional Settings -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Regional Settings</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1">Timezone *</label>
                                    <select name="timezone" id="timezone" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        @foreach($timezones as $tz)
                                        <option value="{{ $tz }}" {{ ($settings['timezone'] ?? 'America/Jamaica') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="week_starts_on" class="block text-sm font-medium text-gray-700 mb-1">Week Starts On *</label>
                                    <select name="week_starts_on" id="week_starts_on" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="sunday" {{ ($settings['week_starts_on'] ?? '') === 'sunday' ? 'selected' : '' }}>Sunday</option>
                                        <option value="monday" {{ ($settings['week_starts_on'] ?? 'monday') === 'monday' ? 'selected' : '' }}>Monday</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Date & Time Formats -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Date & Time Formats</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="date_format" class="block text-sm font-medium text-gray-700 mb-1">Date Format *</label>
                                    <select name="date_format" id="date_format" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="d/m/Y" {{ ($settings['date_format'] ?? 'd/m/Y') === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY ({{ date('d/m/Y') }})</option>
                                        <option value="m/d/Y" {{ ($settings['date_format'] ?? '') === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY ({{ date('m/d/Y') }})</option>
                                        <option value="Y-m-d" {{ ($settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD ({{ date('Y-m-d') }})</option>
                                        <option value="d-m-Y" {{ ($settings['date_format'] ?? '') === 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY ({{ date('d-m-Y') }})</option>
                                        <option value="d M Y" {{ ($settings['date_format'] ?? '') === 'd M Y' ? 'selected' : '' }}>DD Mon YYYY ({{ date('d M Y') }})</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="time_format" class="block text-sm font-medium text-gray-700 mb-1">Time Format *</label>
                                    <select name="time_format" id="time_format" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="H:i" {{ ($settings['time_format'] ?? 'H:i') === 'H:i' ? 'selected' : '' }}>24-hour ({{ date('H:i') }})</option>
                                        <option value="h:i A" {{ ($settings['time_format'] ?? '') === 'h:i A' ? 'selected' : '' }}>12-hour ({{ date('h:i A') }})</option>
                                        <option value="H:i:s" {{ ($settings['time_format'] ?? '') === 'H:i:s' ? 'selected' : '' }}>24-hour with seconds ({{ date('H:i:s') }})</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Security Settings -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Security Settings</h3>
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="session_timeout_minutes" class="block text-sm font-medium text-gray-700 mb-1">Session Timeout (minutes) *</label>
                                        <input type="number" name="session_timeout_minutes" id="session_timeout_minutes" value="{{ old('session_timeout_minutes', $settings['session_timeout_minutes'] ?? 60) }}" min="5" max="480" required
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <p class="text-xs text-muted-foreground mt-1">Auto-logout after inactivity</p>
                                    </div>
                                    <div>
                                        <label for="password_min_length" class="block text-sm font-medium text-gray-700 mb-1">Minimum Password Length *</label>
                                        <input type="number" name="password_min_length" id="password_min_length" value="{{ old('password_min_length', $settings['password_min_length'] ?? 8) }}" min="6" max="32" required
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="password_require_special" id="password_require_special" value="1"
                                               {{ ($settings['password_require_special'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm text-gray-700">Require special characters in passwords</span>
                                    </label>

                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="two_factor_enabled" id="two_factor_enabled" value="1"
                                               {{ ($settings['two_factor_enabled'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm text-gray-700">Enable two-factor authentication</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Audit & Logging -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Audit & Logging</h3>
                            <div class="space-y-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="audit_log_enabled" id="audit_log_enabled" value="1"
                                           {{ ($settings['audit_log_enabled'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Enable audit logging</span>
                                </label>

                                <div class="ml-6">
                                    <label for="audit_log_retention_days" class="block text-sm font-medium text-gray-700 mb-1">Log Retention Period (days) *</label>
                                    <input type="number" name="audit_log_retention_days" id="audit_log_retention_days" value="{{ old('audit_log_retention_days', $settings['audit_log_retention_days'] ?? 365) }}" min="30" max="1825" required
                                           class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <p class="text-xs text-muted-foreground mt-1">Logs older than this will be automatically purged</p>
                                </div>
                            </div>
                        </div>

                        <!-- Backup Settings -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Backups</h3>
                            <div class="space-y-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="backup_enabled" id="backup_enabled" value="1"
                                           {{ ($settings['backup_enabled'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Enable automatic backups</span>
                                </label>

                                <div class="ml-6">
                                    <label for="backup_frequency" class="block text-sm font-medium text-gray-700 mb-1">Backup Frequency</label>
                                    <select name="backup_frequency" id="backup_frequency"
                                            class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="daily" {{ ($settings['backup_frequency'] ?? 'daily') === 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ ($settings['backup_frequency'] ?? '') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance Mode -->
                        <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                            <h3 class="text-lg font-semibold text-red-800 mb-4">Maintenance Mode</h3>
                            <p class="text-sm text-red-700 mb-4">When enabled, only administrators can access the system. Use this during upgrades or maintenance.</p>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1"
                                       {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                                       class="rounded border-red-300 text-red-600 focus:ring-red-500">
                                <span class="text-sm font-medium text-red-800">Enable Maintenance Mode</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                            Save System Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
