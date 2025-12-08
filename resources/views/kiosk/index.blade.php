<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center p-4">
        <div class="w-full max-w-lg">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white text-xl font-bold mb-3 shadow-lg">
                    J
                </div>
                <h1 class="text-2xl font-bold text-white">{{ $settings['kiosk_title'] ?? 'Employee Time Clock' }}</h1>
                <p class="text-slate-400 text-sm mt-1">{{ $settings['kiosk_subtitle'] ?? 'Enter your PIN to clock in or out' }}</p>
            </div>

            <!-- Time Display -->
            <div class="text-center mb-6">
                <div id="current-time" class="text-5xl font-bold text-white font-mono tracking-wider">
                    {{ now()->format('h:i') }}<span class="text-2xl ml-1 text-slate-400">{{ now()->format('A') }}</span>
                </div>
                <div class="text-slate-500 text-sm mt-1">
                    {{ now()->format('l, F j, Y') }}
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-700/50 overflow-hidden">
                <!-- Step 1: PIN Entry -->
                <div id="step-pin" class="p-6">
                    <div class="mb-5">
                        <label class="block text-xs font-medium text-slate-400 mb-2 text-center uppercase tracking-wider">Enter Your 4-Digit PIN</label>
                        <input
                            type="password"
                            id="pin-input"
                            maxlength="4"
                            pattern="\d{4}"
                            class="w-full text-center text-4xl tracking-[0.5em] py-4 bg-slate-900/50 border border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 text-white placeholder-slate-600"
                            placeholder="----"
                            autocomplete="off"
                            inputmode="numeric"
                        >
                    </div>

                    <!-- Message Display -->
                    <div id="message-display" class="hidden mb-4 p-3 rounded-lg text-sm text-center"></div>

                    <!-- Number Pad -->
                    <div class="grid grid-cols-3 gap-2 mb-5">
                        @foreach([1, 2, 3, 4, 5, 6, 7, 8, 9] as $num)
                        <button type="button" onclick="appendPin('{{ $num }}')" class="h-14 text-xl font-semibold bg-slate-700/50 hover:bg-slate-600/50 text-white rounded-xl transition-all active:scale-95 border border-slate-600/50">
                            {{ $num }}
                        </button>
                        @endforeach
                        <button type="button" onclick="clearPin()" class="h-14 text-xs font-semibold bg-red-900/30 hover:bg-red-800/40 text-red-400 rounded-xl transition-all active:scale-95 border border-red-800/50">
                            CLEAR
                        </button>
                        <button type="button" onclick="appendPin('0')" class="h-14 text-xl font-semibold bg-slate-700/50 hover:bg-slate-600/50 text-white rounded-xl transition-all active:scale-95 border border-slate-600/50">
                            0
                        </button>
                        <button type="button" onclick="backspacePin()" class="h-14 text-xl font-semibold bg-slate-700/50 hover:bg-slate-600/50 text-white rounded-xl transition-all active:scale-95 border border-slate-600/50">
                            <svg class="w-6 h-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75L14.25 12m0 0l2.25 2.25M14.25 12l2.25-2.25M14.25 12L12 14.25m-2.58 4.92l-6.375-6.375a1.125 1.125 0 010-1.59L9.42 4.83c.211-.211.498-.33.796-.33H19.5a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25 2.25h-9.284c-.298 0-.585-.119-.796-.33z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="verifyAndProceed('clock_in')" class="py-4 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-900/30 flex items-center justify-center gap-2 active:scale-98">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            Clock In
                        </button>
                        <button type="button" onclick="verifyAndProceed('clock_out')" class="py-4 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-red-900/30 flex items-center justify-center gap-2 active:scale-98">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            Clock Out
                        </button>
                    </div>

                    @if($settings['enable_breaks'] ?? true)
                    <div class="grid grid-cols-2 gap-3 mt-3">
                        <button type="button" onclick="verifyAndProceed('start_break')" class="py-3 bg-amber-600/20 hover:bg-amber-600/30 text-amber-400 font-medium rounded-xl transition-all border border-amber-600/30 text-sm">
                            Start Break
                        </button>
                        <button type="button" onclick="verifyAndProceed('end_break')" class="py-3 bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 font-medium rounded-xl transition-all border border-blue-600/30 text-sm">
                            End Break
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Step 2: Photo Capture -->
                <div id="step-camera" class="hidden">
                    <!-- Employee Info Header -->
                    <div id="employee-info" class="p-4 bg-slate-700/30 border-b border-slate-700/50">
                        <div class="flex items-center gap-3">
                            <div id="employee-avatar" class="h-12 w-12 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-lg">
                                --
                            </div>
                            <div>
                                <div id="employee-name" class="text-white font-semibold">Employee Name</div>
                                <div id="employee-details" class="text-slate-400 text-sm">Department</div>
                            </div>
                            <div id="action-badge" class="ml-auto px-3 py-1 rounded-full text-xs font-semibold"></div>
                        </div>
                    </div>

                    <!-- Camera View -->
                    <div class="p-6">
                        <div class="relative mb-4">
                            <div class="aspect-[4/3] bg-slate-900 rounded-xl overflow-hidden relative">
                                <video id="camera-video" class="w-full h-full object-cover" autoplay playsinline muted></video>
                                <canvas id="photo-canvas" class="hidden w-full h-full object-cover"></canvas>
                                <img id="photo-preview" class="hidden w-full h-full object-cover" alt="Captured photo">

                                <!-- Camera overlay -->
                                <div id="camera-overlay" class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-32 h-32 border-2 border-white/30 rounded-full"></div>
                                </div>

                                <!-- Loading state -->
                                <div id="camera-loading" class="absolute inset-0 flex items-center justify-center bg-slate-900">
                                    <div class="text-center">
                                        <svg class="animate-spin h-8 w-8 text-emerald-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-slate-400 text-sm">Initializing camera...</p>
                                    </div>
                                </div>

                                <!-- Camera error -->
                                <div id="camera-error" class="hidden absolute inset-0 flex items-center justify-center bg-slate-900">
                                    <div class="text-center p-4">
                                        <svg class="h-12 w-12 text-red-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                                        </svg>
                                        <p class="text-red-400 font-medium mb-1">Camera Access Required</p>
                                        <p class="text-slate-500 text-xs">Please allow camera access to proceed</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="text-slate-400 text-sm text-center mb-4">Position your face in the circle and take a photo</p>

                        <!-- Camera Controls -->
                        <div id="camera-controls" class="flex gap-3">
                            <button type="button" onclick="goBackToPin()" class="flex-1 py-3 bg-slate-700/50 hover:bg-slate-600/50 text-white font-medium rounded-xl transition-all border border-slate-600/50">
                                Back
                            </button>
                            <button type="button" onclick="capturePhoto()" id="capture-btn" class="flex-[2] py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-900/30 flex items-center justify-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                                </svg>
                                Take Photo
                            </button>
                        </div>

                        <!-- Confirm Controls (shown after photo taken) -->
                        <div id="confirm-controls" class="hidden flex gap-3">
                            <button type="button" onclick="retakePhoto()" class="flex-1 py-3 bg-slate-700/50 hover:bg-slate-600/50 text-white font-medium rounded-xl transition-all border border-slate-600/50">
                                Retake
                            </button>
                            <button type="button" onclick="submitTimeEntry()" id="confirm-btn" class="flex-[2] py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-900/30 flex items-center justify-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Success Screen -->
                <div id="step-success" class="hidden p-8 text-center">
                    <div class="w-20 h-20 rounded-full bg-emerald-500/20 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <h3 id="success-title" class="text-xl font-bold text-white mb-2">Success!</h3>
                    <p id="success-message" class="text-slate-400 mb-6">Your time has been recorded.</p>
                    <button type="button" onclick="resetKiosk()" class="px-8 py-3 bg-slate-700/50 hover:bg-slate-600/50 text-white font-medium rounded-xl transition-all border border-slate-600/50">
                        Done
                    </button>
                </div>
            </div>

            <p class="text-center text-xs text-slate-600 mt-6">
                Contact HR if you've forgotten your PIN
            </p>
        </div>
    </div>

    <script>
        // Configuration from settings
        const kioskSettings = @json($settings);
        const requirePhoto = kioskSettings.require_photo ?? true;
        const cameraFacing = kioskSettings.camera_facing ?? 'user';

        // State
        let currentAction = null;
        let currentPin = '';
        let employeeData = null;
        let mediaStream = null;
        let capturedPhoto = null;

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        // Update clock every second
        setInterval(function() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const displayHours = (hours % 12 || 12).toString();
            document.getElementById('current-time').innerHTML = `${displayHours}:${minutes}<span class="text-2xl ml-1 text-slate-400">${ampm}</span>`;
        }, 1000);

        // PIN Functions
        function appendPin(num) {
            const input = document.getElementById('pin-input');
            if (input.value.length < 4) {
                input.value += num;
            }
        }

        function clearPin() {
            document.getElementById('pin-input').value = '';
            hideMessage();
        }

        function backspacePin() {
            const input = document.getElementById('pin-input');
            input.value = input.value.slice(0, -1);
        }

        // Message Display
        function showMessage(message, type = 'error') {
            const display = document.getElementById('message-display');
            display.className = `mb-4 p-3 rounded-lg text-sm text-center ${type === 'error' ? 'bg-red-900/30 border border-red-800/50 text-red-400' : 'bg-emerald-900/30 border border-emerald-800/50 text-emerald-400'}`;
            display.textContent = message;
            display.classList.remove('hidden');
        }

        function hideMessage() {
            document.getElementById('message-display').classList.add('hidden');
        }

        // Verify PIN and proceed
        async function verifyAndProceed(action) {
            const pin = document.getElementById('pin-input').value;

            if (pin.length !== 4) {
                showMessage('Please enter a 4-digit PIN');
                return;
            }

            currentAction = action;
            currentPin = pin;
            hideMessage();

            // For break actions, submit directly without photo
            if (action === 'start_break' || action === 'end_break') {
                await submitBreakAction(action);
                return;
            }

            // For clock in/out, verify PIN first
            try {
                const response = await fetch('{{ route("kiosk.verify-pin") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ pin: pin }),
                });

                const data = await response.json();

                if (!response.ok) {
                    showMessage(data.message || 'Invalid PIN');
                    return;
                }

                employeeData = data.employee;

                // Check if action is valid for current status
                if (action === 'clock_in' && data.status.clocked_in) {
                    showMessage('Already clocked in today');
                    return;
                }
                if (action === 'clock_out' && !data.status.clocked_in) {
                    showMessage('No active clock-in found');
                    return;
                }

                if (requirePhoto) {
                    showCameraStep();
                } else {
                    await submitTimeEntry();
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('Connection error. Please try again.');
            }
        }

        // Break Actions (no photo required)
        async function submitBreakAction(action) {
            const url = action === 'start_break' ? '{{ route("kiosk.start-break") }}' : '{{ route("kiosk.end-break") }}';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ pin: currentPin }),
                });

                const data = await response.json();

                if (!response.ok) {
                    showMessage(data.message || 'Operation failed');
                    return;
                }

                showSuccess(action === 'start_break' ? 'Break Started' : 'Break Ended', data.message);
            } catch (error) {
                console.error('Error:', error);
                showMessage('Connection error. Please try again.');
            }
        }

        // Camera Functions
        function showCameraStep() {
            document.getElementById('step-pin').classList.add('hidden');
            document.getElementById('step-camera').classList.remove('hidden');

            // Update employee info
            document.getElementById('employee-avatar').textContent = employeeData.initials || '--';
            document.getElementById('employee-name').textContent = employeeData.name;
            document.getElementById('employee-details').textContent = `${employeeData.job_title || ''} ${employeeData.department ? '· ' + employeeData.department : ''}`;

            // Update action badge
            const badge = document.getElementById('action-badge');
            if (currentAction === 'clock_in') {
                badge.textContent = 'CLOCK IN';
                badge.className = 'ml-auto px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-400';
            } else {
                badge.textContent = 'CLOCK OUT';
                badge.className = 'ml-auto px-3 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-400';
            }

            startCamera();
        }

        async function startCamera() {
            const video = document.getElementById('camera-video');
            const loading = document.getElementById('camera-loading');
            const error = document.getElementById('camera-error');
            const overlay = document.getElementById('camera-overlay');

            try {
                mediaStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: cameraFacing,
                        width: { ideal: 640 },
                        height: { ideal: 480 }
                    },
                    audio: false
                });

                video.srcObject = mediaStream;
                await video.play();

                loading.classList.add('hidden');
                video.classList.remove('hidden');
                overlay.classList.remove('hidden');
            } catch (err) {
                console.error('Camera error:', err);
                loading.classList.add('hidden');
                error.classList.remove('hidden');
            }
        }

        function stopCamera() {
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
                mediaStream = null;
            }
        }

        function capturePhoto() {
            const video = document.getElementById('camera-video');
            const canvas = document.getElementById('photo-canvas');
            const preview = document.getElementById('photo-preview');
            const overlay = document.getElementById('camera-overlay');

            // Set canvas size to video size
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Draw video frame to canvas
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);

            // Get image data
            const quality = kioskSettings.photo_quality === 'high' ? 0.9 : (kioskSettings.photo_quality === 'low' ? 0.5 : 0.7);
            capturedPhoto = canvas.toDataURL('image/jpeg', quality);

            // Show preview
            preview.src = capturedPhoto;
            video.classList.add('hidden');
            overlay.classList.add('hidden');
            preview.classList.remove('hidden');

            // Switch controls
            document.getElementById('camera-controls').classList.add('hidden');
            document.getElementById('confirm-controls').classList.remove('hidden');

            stopCamera();
        }

        function retakePhoto() {
            const video = document.getElementById('camera-video');
            const preview = document.getElementById('photo-preview');
            const overlay = document.getElementById('camera-overlay');

            preview.classList.add('hidden');
            video.classList.remove('hidden');

            document.getElementById('camera-controls').classList.remove('hidden');
            document.getElementById('confirm-controls').classList.add('hidden');

            capturedPhoto = null;
            startCamera();
        }

        // Submit Time Entry
        async function submitTimeEntry() {
            const url = currentAction === 'clock_in' ? '{{ route("kiosk.clock-in") }}' : '{{ route("kiosk.clock-out") }}';
            const confirmBtn = document.getElementById('confirm-btn');

            if (confirmBtn) {
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = '<svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
            }

            try {
                const body = { pin: currentPin };
                if (requirePhoto && capturedPhoto) {
                    body.photo = capturedPhoto;
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(body),
                });

                const data = await response.json();

                if (!response.ok) {
                    if (confirmBtn) {
                        confirmBtn.disabled = false;
                        confirmBtn.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg> Confirm';
                    }
                    showMessage(data.message || 'Operation failed');
                    goBackToPin();
                    return;
                }

                const title = currentAction === 'clock_in' ? 'Clocked In!' : 'Clocked Out!';
                showSuccess(title, data.message);
            } catch (error) {
                console.error('Error:', error);
                if (confirmBtn) {
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg> Confirm';
                }
                showMessage('Connection error. Please try again.');
                goBackToPin();
            }
        }

        // Success Screen
        function showSuccess(title, message) {
            stopCamera();
            document.getElementById('step-pin').classList.add('hidden');
            document.getElementById('step-camera').classList.add('hidden');
            document.getElementById('step-success').classList.remove('hidden');

            document.getElementById('success-title').textContent = title;
            document.getElementById('success-message').textContent = message;

            // Auto reset after 5 seconds
            setTimeout(resetKiosk, 5000);
        }

        // Navigation
        function goBackToPin() {
            stopCamera();
            capturedPhoto = null;

            document.getElementById('step-camera').classList.add('hidden');
            document.getElementById('step-pin').classList.remove('hidden');

            // Reset camera UI
            document.getElementById('camera-video').classList.add('hidden');
            document.getElementById('photo-preview').classList.add('hidden');
            document.getElementById('camera-loading').classList.remove('hidden');
            document.getElementById('camera-error').classList.add('hidden');
            document.getElementById('camera-controls').classList.remove('hidden');
            document.getElementById('confirm-controls').classList.add('hidden');
        }

        function resetKiosk() {
            stopCamera();
            capturedPhoto = null;
            currentAction = null;
            currentPin = '';
            employeeData = null;

            document.getElementById('pin-input').value = '';
            document.getElementById('step-success').classList.add('hidden');
            document.getElementById('step-camera').classList.add('hidden');
            document.getElementById('step-pin').classList.remove('hidden');

            // Reset camera UI
            document.getElementById('camera-video').classList.add('hidden');
            document.getElementById('photo-preview').classList.add('hidden');
            document.getElementById('camera-loading').classList.remove('hidden');
            document.getElementById('camera-error').classList.add('hidden');
            document.getElementById('camera-controls').classList.remove('hidden');
            document.getElementById('confirm-controls').classList.add('hidden');

            hideMessage();
        }

        // Handle keyboard input for PIN
        document.addEventListener('keydown', function(e) {
            if (document.getElementById('step-pin').classList.contains('hidden')) return;

            if (e.key >= '0' && e.key <= '9') {
                appendPin(e.key);
            } else if (e.key === 'Backspace') {
                backspacePin();
            } else if (e.key === 'Escape') {
                clearPin();
            }
        });
    </script>
</x-guest-layout>
