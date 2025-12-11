<x-app-layout>
    @section('title', 'Integrations')

    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-serif text-foreground">Settings</h1>
            <p class="text-muted-foreground mt-1">Connect JamHR with external services like Google Workspace and Microsoft 365.</p>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
            {{ session('info') }}
        </div>
        @endif

        <!-- Settings Navigation -->
        <div class="bg-white rounded-lg shadow-sm">
            @include('settings.partials.tabs', ['activeTab' => $activeTab])

            <div class="p-6">
                <div class="space-y-8">
                    <!-- Google Workspace -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 bg-white rounded-lg shadow-sm flex items-center justify-center">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24">
                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Google Workspace</h3>
                                    <p class="text-sm text-gray-500">Calendar sync, directory access</p>
                                </div>
                            </div>
                            @if($googleInfo)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Connected
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                Not Connected
                            </span>
                            @endif
                        </div>

                        <div class="p-6">
                            @if($googleInfo)
                            <!-- Connected State -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="h-12 w-12 bg-primary/10 rounded-full flex items-center justify-center text-primary font-semibold">
                                        {{ strtoupper(substr($googleInfo['name'] ?? $googleInfo['email'], 0, 2)) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ $googleInfo['name'] ?? 'Google Account' }}</div>
                                        <div class="text-sm text-gray-500">{{ $googleInfo['email'] }}</div>
                                        @if($googleInfo['domain'])
                                        <div class="text-xs text-gray-400 mt-0.5">Workspace Domain: {{ $googleInfo['domain'] }}</div>
                                        @endif
                                    </div>
                                    <div class="text-right text-sm text-gray-500">
                                        <div>Connected {{ $googleInfo['connected_at']->diffForHumans() }}</div>
                                        <div class="text-xs">by {{ $googleInfo['connected_by'] }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="testGoogleCalendar()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        Test Calendar
                                    </button>
                                    <form action="{{ route('integrations.google.disconnect') }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect Google Workspace?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                                            Disconnect
                                        </button>
                                    </form>
                                </div>

                                <div id="calendar-test-result" class="hidden p-4 rounded-lg text-sm"></div>
                            </div>
                            @else
                            <!-- Not Connected State -->
                            <form action="{{ route('integrations.google.redirect') }}" method="GET" class="space-y-4">
                                <p class="text-gray-600">Connect your Google Workspace to enable:</p>
                                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1 ml-2">
                                    <li>Sync leave requests to Google Calendar</li>
                                    <li>Import employee directory (Workspace Admin)</li>
                                    <li>Schedule meetings for interviews</li>
                                </ul>

                                <div class="pt-4 border-t border-gray-200">
                                    <p class="text-sm font-medium text-gray-700 mb-3">Select permissions to grant:</p>
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="calendar" value="1" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="text-sm text-gray-700">Google Calendar (read & write events)</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="directory" value="1" class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="text-sm text-gray-700">Directory access (requires Workspace Admin)</span>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition-colors">
                                    <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    </svg>
                                    Connect Google Workspace
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    <!-- Microsoft 365 / Teams -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 bg-white rounded-lg shadow-sm flex items-center justify-center">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24">
                                        <path fill="#00A4EF" d="M0 0h11.5v11.5H0z"/>
                                        <path fill="#FFB900" d="M12.5 0H24v11.5H12.5z"/>
                                        <path fill="#7FBA00" d="M0 12.5h11.5V24H0z"/>
                                        <path fill="#F25022" d="M12.5 12.5H24V24H12.5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Microsoft 365</h3>
                                    <p class="text-sm text-gray-500">Teams, Outlook Calendar, Azure AD</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                Coming Soon
                            </span>
                        </div>

                        <div class="p-6">
                            <p class="text-gray-600 mb-4">Microsoft 365 integration will enable:</p>
                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1 ml-2">
                                <li>Sync leave requests to Outlook Calendar</li>
                                <li>Send notifications via Microsoft Teams</li>
                                <li>Single Sign-On with Azure AD</li>
                                <li>Import employees from Azure Active Directory</li>
                            </ul>

                            <button type="button" disabled class="mt-4 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-400 font-medium rounded-lg cursor-not-allowed">
                                <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M0 0h11.5v11.5H0zM12.5 0H24v11.5H12.5zM0 12.5h11.5V24H0zM12.5 12.5H24V24H12.5z"/>
                                </svg>
                                Connect Microsoft 365
                            </button>
                        </div>
                    </div>

                    <!-- Setup Instructions -->
                    <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-800 mb-3">Setup Instructions</h3>
                        <div class="text-sm text-blue-700 space-y-3">
                            <p><strong>Google Workspace:</strong></p>
                            <ol class="list-decimal list-inside ml-2 space-y-1">
                                <li>Go to <a href="https://console.cloud.google.com" target="_blank" class="underline hover:text-blue-900">Google Cloud Console</a></li>
                                <li>Create a new project or select existing</li>
                                <li>Enable Calendar API and Admin SDK API</li>
                                <li>Create OAuth 2.0 credentials (Web application)</li>
                                <li>Add <code class="bg-blue-100 px-1 rounded">{{ url('/integrations/google/callback') }}</code> as authorized redirect URI</li>
                                <li>Copy Client ID and Secret to your environment variables</li>
                            </ol>
                            <p class="mt-3"><strong>Environment Variables:</strong></p>
                            <pre class="bg-blue-100 p-3 rounded text-xs overflow-x-auto">GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI={{ url('/integrations/google/callback') }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function testGoogleCalendar() {
            const resultDiv = document.getElementById('calendar-test-result');
            resultDiv.className = 'p-4 rounded-lg text-sm bg-gray-100 text-gray-700';
            resultDiv.textContent = 'Testing calendar access...';
            resultDiv.classList.remove('hidden');

            try {
                const response = await fetch('{{ route("integrations.google.test-calendar") }}');
                const data = await response.json();

                if (data.success) {
                    resultDiv.className = 'p-4 rounded-lg text-sm bg-emerald-50 text-emerald-700';
                    if (data.events && data.events.length > 0) {
                        resultDiv.innerHTML = `<strong>Success!</strong> Found ${data.events.length} upcoming events:<br><ul class="list-disc list-inside mt-2">${data.events.map(e => `<li>${e.title}</li>`).join('')}</ul>`;
                    } else {
                        resultDiv.textContent = 'Success! Calendar access is working. No upcoming events found.';
                    }
                } else {
                    resultDiv.className = 'p-4 rounded-lg text-sm bg-red-50 text-red-700';
                    resultDiv.textContent = 'Error: ' + (data.message || 'Failed to access calendar');
                }
            } catch (error) {
                resultDiv.className = 'p-4 rounded-lg text-sm bg-red-50 text-red-700';
                resultDiv.textContent = 'Error: Failed to connect to server';
            }
        }
    </script>
</x-app-layout>
