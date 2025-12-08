<x-app-layout>
    @section('title', 'Time Kiosk Settings')

    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-serif text-foreground">Settings</h1>
            <p class="text-muted-foreground mt-1">Configure your employee time kiosk for clock in/out with photo verification.</p>
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
                <form action="{{ route('settings.kiosk.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Kiosk Display Settings -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Kiosk Display</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Kiosk Title *</label>
                                    <input type="text" name="title" id="title" value="{{ old('title', $settings['title'] ?? 'Employee Time Clock') }}" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <p class="text-xs text-muted-foreground mt-1">Main heading displayed on the kiosk</p>
                                </div>
                                <div>
                                    <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Kiosk Subtitle</label>
                                    <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $settings['subtitle'] ?? 'Enter your PIN to clock in or out') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <p class="text-xs text-muted-foreground mt-1">Secondary text below the title</p>
                                </div>
                            </div>
                        </div>

                        <!-- Photo Verification Settings -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Photo Verification</h3>
                            <p class="text-sm text-muted-foreground mb-4">Configure photo capture to prevent buddy punching and ensure attendance accuracy.</p>

                            <div class="space-y-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="require_photo" id="require_photo" value="1"
                                           {{ ($settings['require_photo'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Require photo for clock in/out</span>
                                </label>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                    <div>
                                        <label for="photo_quality" class="block text-sm font-medium text-gray-700 mb-1">Photo Quality</label>
                                        <select name="photo_quality" id="photo_quality"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                            <option value="low" {{ ($settings['photo_quality'] ?? '') === 'low' ? 'selected' : '' }}>Low (faster uploads, smaller files)</option>
                                            <option value="medium" {{ ($settings['photo_quality'] ?? 'medium') === 'medium' ? 'selected' : '' }}>Medium (recommended)</option>
                                            <option value="high" {{ ($settings['photo_quality'] ?? '') === 'high' ? 'selected' : '' }}>High (best quality, larger files)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="camera_facing" class="block text-sm font-medium text-gray-700 mb-1">Default Camera</label>
                                        <select name="camera_facing" id="camera_facing"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                            <option value="user" {{ ($settings['camera_facing'] ?? 'user') === 'user' ? 'selected' : '' }}>Front-facing (selfie camera)</option>
                                            <option value="environment" {{ ($settings['camera_facing'] ?? '') === 'environment' ? 'selected' : '' }}>Rear-facing camera</option>
                                        </select>
                                        <p class="text-xs text-muted-foreground mt-1">For tablets/phones with multiple cameras</p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="photo_retention_days" class="block text-sm font-medium text-gray-700 mb-1">Photo Retention Period (days) *</label>
                                    <input type="number" name="photo_retention_days" id="photo_retention_days" value="{{ old('photo_retention_days', $settings['photo_retention_days'] ?? 90) }}" min="7" max="365" required
                                           class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <p class="text-xs text-muted-foreground mt-1">Photos older than this will be automatically deleted</p>
                                </div>
                            </div>
                        </div>

                        <!-- Time Tracking Settings -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Time Tracking Rules</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="work_start_hour" class="block text-sm font-medium text-gray-700 mb-1">Work Start Hour *</label>
                                    <select name="work_start_hour" id="work_start_hour" required
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        @for($h = 0; $h < 24; $h++)
                                        <option value="{{ $h }}" {{ (int)($settings['work_start_hour'] ?? 9) === $h ? 'selected' : '' }}>
                                            {{ sprintf('%02d:00', $h) }} ({{ date('g:00 A', strtotime("$h:00")) }})
                                        </option>
                                        @endfor
                                    </select>
                                    <p class="text-xs text-muted-foreground mt-1">Clock-ins after this time are marked as "Late"</p>
                                </div>
                                <div>
                                    <label for="overtime_threshold" class="block text-sm font-medium text-gray-700 mb-1">Overtime Threshold (hours) *</label>
                                    <input type="number" name="overtime_threshold" id="overtime_threshold" value="{{ old('overtime_threshold', $settings['overtime_threshold'] ?? 8) }}" min="1" max="24" step="0.5" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <p class="text-xs text-muted-foreground mt-1">Working more than this marks the entry as "Overtime"</p>
                                </div>
                            </div>
                        </div>

                        <!-- Kiosk Behavior -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Kiosk Behavior</h3>
                            <div class="space-y-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="enable_breaks" id="enable_breaks" value="1"
                                           {{ ($settings['enable_breaks'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Enable break tracking (Start Break / End Break buttons)</span>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="allow_multiple_clockins" id="allow_multiple_clockins" value="1"
                                           {{ ($settings['allow_multiple_clockins'] ?? false) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Allow multiple clock-ins per day (for shift workers)</span>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="show_employee_photo" id="show_employee_photo" value="1"
                                           {{ ($settings['show_employee_photo'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Show employee name and details after PIN verification</span>
                                </label>

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="allow_manual_entry" id="allow_manual_entry" value="1"
                                           {{ ($settings['allow_manual_entry'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Allow administrators to create manual time entries</span>
                                </label>
                            </div>
                        </div>

                        <!-- Kiosk Access -->
                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">Kiosk Access</h3>
                            <p class="text-sm text-blue-700 mb-4">The time kiosk is publicly accessible at the URL below. No login required - employees use their PIN to clock in/out.</p>
                            <div class="flex items-center gap-2">
                                <code class="bg-white px-3 py-2 rounded border border-blue-300 text-sm text-blue-900 flex-1">{{ url('/kiosk') }}</code>
                                <a href="{{ route('kiosk.index') }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                                    Open Kiosk
                                </a>
                            </div>
                            <p class="text-xs text-blue-600 mt-3">Tip: Open this URL on a dedicated tablet at your workplace entrance for employees to use.</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                            Save Kiosk Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
