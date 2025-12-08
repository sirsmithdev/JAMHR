<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-primary/10 via-white to-secondary/10 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center h-16 w-16 rounded-xl bg-primary text-white text-2xl font-bold mb-4">
                    J
                </div>
                <h1 class="text-3xl font-serif font-bold text-foreground">JamHR Kiosk</h1>
                <p class="text-muted-foreground mt-1">Employee Time Clock</p>
            </div>

            <!-- Time Display -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
                <div class="text-center mb-8">
                    <div id="current-time" class="text-5xl font-bold font-serif text-foreground">
                        {{ now()->format('h:i A') }}
                    </div>
                    <div class="text-muted-foreground mt-2">
                        {{ now()->format('l, F j, Y') }}
                    </div>
                </div>

                <!-- PIN Entry -->
                <form id="kiosk-form" method="POST" action="{{ route('kiosk.clock-in') }}">
                    @csrf
                    <input type="hidden" name="action" id="kiosk-action" value="clock_in">

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-muted-foreground mb-2 text-center">Enter Your PIN</label>
                        <input
                            type="password"
                            name="pin"
                            id="pin-input"
                            maxlength="4"
                            pattern="\d{4}"
                            class="w-full text-center text-3xl tracking-widest py-4 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
                            placeholder="••••"
                            autocomplete="off"
                        >
                    </div>

                    @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm text-center">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm text-center">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Number Pad -->
                    <div class="grid grid-cols-3 gap-3 mb-6">
                        @foreach([1, 2, 3, 4, 5, 6, 7, 8, 9] as $num)
                        <button type="button" onclick="appendPin('{{ $num }}')" class="h-14 text-xl font-medium bg-muted/30 hover:bg-muted rounded-lg transition-colors">
                            {{ $num }}
                        </button>
                        @endforeach
                        <button type="button" onclick="clearPin()" class="h-14 text-sm font-medium bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors">
                            Clear
                        </button>
                        <button type="button" onclick="appendPin('0')" class="h-14 text-xl font-medium bg-muted/30 hover:bg-muted rounded-lg transition-colors">
                            0
                        </button>
                        <button type="button" onclick="backspacePin()" class="h-14 text-sm font-medium bg-muted/30 hover:bg-muted rounded-lg transition-colors">
                            ←
                        </button>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-4">
                        <button type="submit" onclick="setAction('clock_in')" class="py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            Clock In
                        </button>
                        <button type="submit" onclick="setAction('clock_out')" class="py-4 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            Clock Out
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <button type="submit" onclick="setAction('start_break')" class="py-3 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors text-sm">
                            Start Break
                        </button>
                        <button type="submit" onclick="setAction('end_break')" class="py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                            End Break
                        </button>
                    </div>
                </form>
            </div>

            <!-- Recent Activity -->
            @if(isset($recentEntries) && $recentEntries->count() > 0)
            <div class="bg-white/80 backdrop-blur rounded-xl p-4">
                <h3 class="text-sm font-medium text-muted-foreground mb-3">Recent Activity</h3>
                <div class="space-y-2">
                    @foreach($recentEntries->take(3) as $entry)
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium">{{ $entry->employee->full_name }}</span>
                        <span class="text-muted-foreground">
                            {{ $entry->type === 'clock_in' ? 'In' : 'Out' }} at {{ $entry->clock_in->format('h:i A') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <p class="text-center text-xs text-muted-foreground mt-6">
                Contact HR if you've forgotten your PIN
            </p>
        </div>
    </div>

    <script>
        // Update clock every second
        setInterval(function() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const displayHours = hours % 12 || 12;
            document.getElementById('current-time').textContent = `${displayHours}:${minutes} ${ampm}`;
        }, 1000);

        function appendPin(num) {
            const input = document.getElementById('pin-input');
            if (input.value.length < 4) {
                input.value += num;
            }
        }

        function clearPin() {
            document.getElementById('pin-input').value = '';
        }

        function backspacePin() {
            const input = document.getElementById('pin-input');
            input.value = input.value.slice(0, -1);
        }

        function setAction(action) {
            document.getElementById('kiosk-action').value = action;

            const form = document.getElementById('kiosk-form');
            if (action === 'clock_out') {
                form.action = '{{ route('kiosk.clock-out') }}';
            } else if (action === 'start_break') {
                form.action = '{{ route('kiosk.start-break') }}';
            } else if (action === 'end_break') {
                form.action = '{{ route('kiosk.end-break') }}';
            } else {
                form.action = '{{ route('kiosk.clock-in') }}';
            }
        }
    </script>
</x-guest-layout>
