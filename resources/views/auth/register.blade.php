<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Start Free Trial - JamHR</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&family=playfair-display:700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased h-full bg-gray-50">
    <div class="min-h-full flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-emerald-600 to-emerald-800 relative overflow-hidden">
            <!-- Background pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <defs>
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#grid)"/>
                </svg>
            </div>

            <div class="relative z-10 flex flex-col justify-between p-12 text-white w-full">
                <!-- Logo -->
                <div>
                    <a href="{{ route('landing') }}" class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-lg bg-white/20 backdrop-blur flex items-center justify-center">
                            <span class="font-serif font-bold text-white text-xl">J</span>
                        </div>
                        <span class="font-serif font-bold text-2xl">JamHR</span>
                    </a>
                </div>

                <!-- Value Propositions -->
                <div class="max-w-md">
                    <h2 class="text-3xl font-bold mb-8">Start your free 14-day trial</h2>

                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-emerald-300 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-lg">Full access to all features</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-emerald-300 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-lg">Jamaican compliance built-in (NIS, NHT, PAYE)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-emerald-300 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-lg">Unlimited employees during trial</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-emerald-300 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-lg">No credit card required</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-emerald-300 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-lg">Cancel anytime, no questions asked</span>
                        </li>
                    </ul>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8">
                    <div>
                        <div class="text-3xl font-bold">500+</div>
                        <div class="text-emerald-200 text-sm">Companies</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">50K+</div>
                        <div class="text-emerald-200 text-sm">Employees</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">99.9%</div>
                        <div class="text-emerald-200 text-sm">Uptime</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8">
                    <a href="{{ route('landing') }}" class="flex items-center gap-2">
                        <div class="h-9 w-9 rounded-lg bg-emerald-600 flex items-center justify-center">
                            <span class="font-serif font-bold text-white text-xl">J</span>
                        </div>
                        <span class="font-serif font-bold text-xl text-gray-900">JamHR</span>
                    </a>
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-serif font-bold text-gray-900">Create your account</h2>
                    <p class="mt-2 text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-500">
                            Sign in
                        </a>
                    </p>
                </div>

                <!-- Social Signup -->
                <div class="space-y-3 mb-6">
                    <button type="button" class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Continue with Google</span>
                    </button>

                    <button type="button" class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#00A4EF">
                            <path d="M11.4 24H0V12.6h11.4V24zM24 24H12.6V12.6H24V24zM11.4 11.4H0V0h11.4v11.4zm12.6 0H12.6V0H24v11.4z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Continue with Microsoft</span>
                    </button>
                </div>

                <div class="relative mb-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-gray-50 text-gray-500">or register with email</span>
                    </div>
                </div>

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{
                    password: '',
                    get strength() {
                        let score = 0;
                        if (this.password.length >= 8) score++;
                        if (/[a-z]/.test(this.password)) score++;
                        if (/[A-Z]/.test(this.password)) score++;
                        if (/[0-9]/.test(this.password)) score++;
                        if (/[^a-zA-Z0-9]/.test(this.password)) score++;
                        return score;
                    },
                    get strengthText() {
                        if (this.password.length === 0) return '';
                        if (this.strength <= 2) return 'Weak';
                        if (this.strength <= 3) return 'Fair';
                        if (this.strength <= 4) return 'Good';
                        return 'Strong';
                    },
                    get strengthColor() {
                        if (this.strength <= 2) return 'bg-red-500';
                        if (this.strength <= 3) return 'bg-yellow-500';
                        if (this.strength <= 4) return 'bg-blue-500';
                        return 'bg-emerald-500';
                    }
                }">
                    @csrf

                    <!-- Name Fields -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                First name
                            </label>
                            <input
                                id="first_name"
                                name="first_name"
                                type="text"
                                autocomplete="given-name"
                                required
                                value="{{ old('first_name') }}"
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('first_name') border-red-300 @enderror"
                                placeholder="John"
                            >
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Last name
                            </label>
                            <input
                                id="last_name"
                                name="last_name"
                                type="text"
                                autocomplete="family-name"
                                required
                                value="{{ old('last_name') }}"
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('last_name') border-red-300 @enderror"
                                placeholder="Brown"
                            >
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Work Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Work email
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            value="{{ old('email') }}"
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-300 @enderror"
                            placeholder="you@company.com"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Company name
                        </label>
                        <input
                            id="company_name"
                            name="company_name"
                            type="text"
                            autocomplete="organization"
                            required
                            value="{{ old('company_name') }}"
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('company_name') border-red-300 @enderror"
                            placeholder="Acme Ltd"
                        >
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Size -->
                    <div>
                        <label for="company_size" class="block text-sm font-medium text-gray-700 mb-1">
                            Number of employees
                        </label>
                        <select
                            id="company_size"
                            name="company_size"
                            required
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('company_size') border-red-300 @enderror"
                        >
                            <option value="">Select company size</option>
                            <option value="1-10" {{ old('company_size') == '1-10' ? 'selected' : '' }}>1-10 employees</option>
                            <option value="11-25" {{ old('company_size') == '11-25' ? 'selected' : '' }}>11-25 employees</option>
                            <option value="26-50" {{ old('company_size') == '26-50' ? 'selected' : '' }}>26-50 employees</option>
                            <option value="51-100" {{ old('company_size') == '51-100' ? 'selected' : '' }}>51-100 employees</option>
                            <option value="101-250" {{ old('company_size') == '101-250' ? 'selected' : '' }}>101-250 employees</option>
                            <option value="251+" {{ old('company_size') == '251+' ? 'selected' : '' }}>251+ employees</option>
                        </select>
                        @error('company_size')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password with Strength Indicator -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                required
                                x-model="password"
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-300 @enderror"
                                placeholder="Create a strong password"
                            >
                            <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" id="password-eye" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-2" x-show="password.length > 0">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div
                                        class="h-full transition-all duration-300"
                                        :class="strengthColor"
                                        :style="'width: ' + (strength * 20) + '%'"
                                    ></div>
                                </div>
                                <span class="text-xs font-medium" :class="{
                                    'text-red-600': strength <= 2,
                                    'text-yellow-600': strength === 3,
                                    'text-blue-600': strength === 4,
                                    'text-emerald-600': strength === 5
                                }" x-text="strengthText"></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Use 8+ characters with uppercase, lowercase, numbers & symbols</p>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm password
                        </label>
                        <div class="relative">
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                required
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Confirm your password"
                            >
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" id="password_confirmation-eye" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <input
                                id="terms"
                                name="terms"
                                type="checkbox"
                                required
                                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded mt-0.5"
                            >
                            <label for="terms" class="ml-2 block text-sm text-gray-700">
                                I agree to the
                                <a href="#" class="text-emerald-600 hover:underline">Terms of Service</a>
                                and
                                <a href="#" class="text-emerald-600 hover:underline">Privacy Policy</a>
                            </label>
                        </div>
                        @error('terms')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="flex items-start">
                            <input
                                id="marketing"
                                name="marketing"
                                type="checkbox"
                                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded mt-0.5"
                            >
                            <label for="marketing" class="ml-2 block text-sm text-gray-700">
                                Send me product updates, tips, and special offers (optional)
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
                    >
                        Start free trial
                    </button>
                </form>

                <!-- Footer -->
                <p class="mt-8 text-center text-xs text-gray-500">
                    No credit card required. Cancel anytime.
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(inputId + '-eye');
            if (input.type === 'password') {
                input.type = 'text';
                eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                eye.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</body>
</html>
