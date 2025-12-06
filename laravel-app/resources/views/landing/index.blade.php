<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="JamHR - The complete HR management solution built for Jamaican businesses. Payroll, benefits, leave management, and compliance made simple.">
    <meta name="keywords" content="HR software Jamaica, payroll Jamaica, NIS NHT PAYE, employee management, Jamaica HR">
    <title>JamHR - HR Management Built for Jamaica</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&family=playfair-display:700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #059669 0%, #fbbf24 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #f0fdf4 0%, #fefce8 50%, #ffffff 100%);
        }
        .feature-card:hover {
            transform: translateY(-4px);
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('landing') }}" class="flex items-center gap-2">
                    <div class="h-9 w-9 rounded-lg bg-emerald-600 flex items-center justify-center">
                        <span class="font-serif font-bold text-white text-xl">J</span>
                    </div>
                    <span class="font-serif font-bold text-xl text-gray-900">JamHR</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-gray-600 hover:text-gray-900 transition-colors">Features</a>
                    <a href="#pricing" class="text-gray-600 hover:text-gray-900 transition-colors">Pricing</a>
                    <a href="#faq" class="text-gray-600 hover:text-gray-900 transition-colors">FAQ</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900 transition-colors">Contact</a>
                </div>

                <!-- CTA Buttons -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex text-gray-600 hover:text-gray-900 transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-600/20">
                        Start Free Trial
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20 lg:pt-40 lg:pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-sm font-medium mb-6">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        100% Jamaica Compliant
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-gray-900 leading-tight mb-6">
                        HR Management<br>
                        <span class="gradient-text">Built for Jamaica</span>
                    </h1>

                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        The complete HR platform that handles payroll, benefits, leave, and compliance - all designed specifically for Jamaican businesses and labor laws.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-base font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-600/30">
                            Start 14-Day Free Trial
                            <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="#demo" class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-base font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                            </svg>
                            Watch Demo
                        </a>
                    </div>

                    <div class="flex items-center gap-6 text-sm text-gray-500">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            No credit card required
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Setup in minutes
                        </div>
                    </div>
                </div>

                <!-- Hero Image/Dashboard Preview -->
                <div class="relative">
                    <div class="relative rounded-2xl shadow-2xl overflow-hidden border border-gray-200 bg-white">
                        <div class="bg-gray-800 px-4 py-3 flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <div class="p-6 bg-gradient-to-br from-gray-50 to-white">
                            <!-- Mock Dashboard -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="h-8 w-32 bg-gray-200 rounded"></div>
                                    <div class="h-8 w-24 bg-emerald-100 rounded"></div>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                                        <div class="h-4 w-16 bg-gray-200 rounded mb-2"></div>
                                        <div class="h-8 w-20 bg-emerald-200 rounded"></div>
                                    </div>
                                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                                        <div class="h-4 w-16 bg-gray-200 rounded mb-2"></div>
                                        <div class="h-8 w-20 bg-amber-200 rounded"></div>
                                    </div>
                                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                                        <div class="h-4 w-16 bg-gray-200 rounded mb-2"></div>
                                        <div class="h-8 w-20 bg-blue-200 rounded"></div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                                    <div class="h-4 w-24 bg-gray-200 rounded mb-4"></div>
                                    <div class="space-y-2">
                                        <div class="h-10 bg-gray-100 rounded flex items-center px-3 gap-3">
                                            <div class="w-8 h-8 rounded-full bg-emerald-200"></div>
                                            <div class="flex-1 h-4 bg-gray-200 rounded"></div>
                                            <div class="w-16 h-6 bg-emerald-100 rounded"></div>
                                        </div>
                                        <div class="h-10 bg-gray-100 rounded flex items-center px-3 gap-3">
                                            <div class="w-8 h-8 rounded-full bg-amber-200"></div>
                                            <div class="flex-1 h-4 bg-gray-200 rounded"></div>
                                            <div class="w-16 h-6 bg-amber-100 rounded"></div>
                                        </div>
                                        <div class="h-10 bg-gray-100 rounded flex items-center px-3 gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-200"></div>
                                            <div class="flex-1 h-4 bg-gray-200 rounded"></div>
                                            <div class="w-16 h-6 bg-blue-100 rounded"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating badges -->
                    <div class="absolute -left-4 top-1/4 bg-white rounded-lg shadow-lg p-3 border border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium">NIS Calculated</span>
                        </div>
                    </div>

                    <div class="absolute -right-4 bottom-1/4 bg-white rounded-lg shadow-lg p-3 border border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium">Payroll Ready</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted By Section -->
    <section class="py-16 bg-gray-50 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 font-medium mb-8">Trusted by leading Jamaican businesses</p>
            <div class="flex flex-wrap items-center justify-center gap-x-12 gap-y-8">
                <!-- Placeholder logos - these would be real company logos -->
                @for ($i = 0; $i < 6; $i++)
                <div class="h-8 w-32 bg-gray-300 rounded opacity-50"></div>
                @endfor
            </div>
            <p class="text-center text-sm text-gray-400 mt-8">Join 100+ companies managing their HR with JamHR</p>
        </div>
    </section>

    <!-- Problem/Solution Section -->
    <section class="py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-gray-900 mb-4">
                    Stop struggling with HR headaches
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Managing HR in Jamaica shouldn't be complicated. We built JamHR to solve the real problems you face every day.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
                <!-- Problems -->
                <div class="bg-red-50 rounded-2xl p-8 border border-red-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-red-900">Without JamHR</h3>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-red-800">Manual calculations for NIS, NHT, PAYE every pay period</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-red-800">Spreadsheets scattered across multiple files</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-red-800">Risk of compliance errors and TAJ penalties</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-red-800">Hours spent tracking leave balances manually</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-red-800">No visibility into HR metrics and trends</span>
                        </li>
                    </ul>
                </div>

                <!-- Solutions -->
                <div class="bg-emerald-50 rounded-2xl p-8 border border-emerald-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-emerald-900">With JamHR</h3>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-emerald-800">Automatic statutory calculations - always accurate</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-emerald-800">Everything in one place - accessible anywhere</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-emerald-800">Built-in compliance with SO 2 form generation</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-emerald-800">Real-time leave tracking with Jamaica labor law rules</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-emerald-800">Powerful dashboards and reports at your fingertips</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 lg:py-28 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-gray-900 mb-4">
                    Everything you need to manage HR
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    From payroll to performance, JamHR has all the tools you need to run HR efficiently.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($features as $feature)
                <div class="feature-card bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
                    <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center mb-4">
                        @if($feature['icon'] === 'calculator')
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        @elseif($feature['icon'] === 'heart')
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        @elseif($feature['icon'] === 'calendar')
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        @elseif($feature['icon'] === 'clock')
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @elseif($feature['icon'] === 'users')
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        @elseif($feature['icon'] === 'chart')
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        @elseif($feature['icon'] === 'folder')
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        @elseif($feature['icon'] === 'shield')
                        <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600 text-sm">{{ $feature['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Jamaica Compliance Highlight -->
    <section class="py-20 lg:py-28 bg-gradient-to-br from-emerald-900 to-emerald-800 text-white overflow-hidden relative">
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

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-200 text-sm font-medium mb-6">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Jamaica Compliant
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-serif font-bold mb-6">
                        Automatic statutory deductions, every time
                    </h2>

                    <p class="text-xl text-emerald-100 mb-8">
                        Never worry about calculating NIS, NHT, Education Tax, or PAYE again. JamHR uses the latest Jamaica tax tables to ensure 100% accuracy.
                    </p>

                    <div class="grid sm:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                            <div class="text-2xl font-bold text-amber-400 mb-1">NIS</div>
                            <div class="text-emerald-200 text-sm">3% Employee / 3% Employer</div>
                            <div class="text-emerald-300 text-xs mt-1">Auto-calculated up to wage ceiling</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                            <div class="text-2xl font-bold text-amber-400 mb-1">NHT</div>
                            <div class="text-emerald-200 text-sm">2% Employee / 3% Employer</div>
                            <div class="text-emerald-300 text-xs mt-1">Applied to gross salary</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                            <div class="text-2xl font-bold text-amber-400 mb-1">Education Tax</div>
                            <div class="text-emerald-200 text-sm">2.25% Employee / 3.5% Employer</div>
                            <div class="text-emerald-300 text-xs mt-1">On taxable income</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                            <div class="text-2xl font-bold text-amber-400 mb-1">PAYE</div>
                            <div class="text-emerald-200 text-sm">25% / 30% Tax Rates</div>
                            <div class="text-emerald-300 text-xs mt-1">After annual threshold of $1.5M</div>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 rounded-lg text-base font-semibold text-emerald-900 bg-amber-400 hover:bg-amber-300 transition-colors">
                        Try It Free
                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>

                <div class="relative">
                    <!-- Tax calculation visualization -->
                    <div class="bg-white rounded-2xl shadow-2xl p-6 text-gray-900">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold">Payroll Calculator</div>
                                <div class="text-sm text-gray-500">Monthly Breakdown</div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-gray-600">Gross Salary</span>
                                <span class="font-bold">JMD 250,000.00</span>
                            </div>

                            <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Deductions</div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">NIS (3%)</span>
                                <span class="text-red-600">- JMD 7,500.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">NHT (2%)</span>
                                <span class="text-red-600">- JMD 5,000.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Education Tax (2.25%)</span>
                                <span class="text-red-600">- JMD 5,625.00</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">PAYE</span>
                                <span class="text-red-600">- JMD 31,250.00</span>
                            </div>

                            <div class="flex justify-between items-center py-3 border-t-2 border-emerald-500 mt-4">
                                <span class="font-bold text-gray-900">Net Pay</span>
                                <span class="font-bold text-2xl text-emerald-600">JMD 200,625.00</span>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-emerald-50 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-emerald-800">All calculations verified with current Jamaica tax tables</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-gray-900 mb-4">
                    Simple, transparent pricing
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Choose the plan that fits your business. All plans include a 14-day free trial.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach($pricingPlans as $plan)
                <div class="relative bg-white rounded-2xl {{ $plan['highlighted'] ? 'ring-2 ring-emerald-500 shadow-xl' : 'border border-gray-200 shadow-sm' }}">
                    @if($plan['highlighted'])
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                        <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-semibold bg-emerald-500 text-white">
                            Most Popular
                        </span>
                    </div>
                    @endif

                    <div class="p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan['name'] }}</h3>
                        <p class="text-gray-500 text-sm mb-6">{{ $plan['description'] }}</p>

                        <div class="mb-6">
                            @if($plan['price'])
                            <span class="text-4xl font-bold text-gray-900">JMD {{ number_format($plan['price']) }}</span>
                            <span class="text-gray-500">/{{ $plan['period'] }}</span>
                            @else
                            <span class="text-4xl font-bold text-gray-900">Custom</span>
                            <span class="text-gray-500 block">{{ $plan['period'] }}</span>
                            @endif
                        </div>

                        <a href="{{ $plan['cta'] === 'Contact Sales' ? route('contact') : route('register') }}" class="block w-full text-center px-6 py-3 rounded-lg font-semibold {{ $plan['highlighted'] ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }} transition-colors mb-8">
                            {{ $plan['cta'] }}
                        </a>

                        <ul class="space-y-3">
                            @foreach($plan['features'] as $feature)
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-600">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>

            <p class="text-center text-gray-500 mt-12">
                All prices in Jamaican Dollars (JMD). Need a custom solution? <a href="{{ route('contact') }}" class="text-emerald-600 hover:underline">Contact us</a>
            </p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 lg:py-28 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-gray-900 mb-4">
                    Frequently asked questions
                </h2>
                <p class="text-xl text-gray-600">
                    Everything you need to know about JamHR
                </p>
            </div>

            <div class="space-y-4">
                @foreach($faqs as $index => $faq)
                <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-4 text-left">
                        <span class="font-semibold text-gray-900">{{ $faq['question'] }}</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-4">
                        <p class="text-gray-600">{{ $faq['answer'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <p class="text-gray-600 mb-4">Still have questions?</p>
                <a href="{{ route('contact') }}" class="inline-flex items-center text-emerald-600 font-semibold hover:underline">
                    Contact our team
                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="demo" class="py-20 lg:py-28">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-3xl p-8 lg:p-12 text-center text-white relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-white/10 rounded-full"></div>
                <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-48 h-48 bg-white/10 rounded-full"></div>

                <div class="relative">
                    <h2 class="text-3xl sm:text-4xl font-serif font-bold mb-4">
                        Ready to simplify your HR?
                    </h2>
                    <p class="text-xl text-emerald-100 mb-8 max-w-2xl mx-auto">
                        Join hundreds of Jamaican businesses already saving time and staying compliant with JamHR.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-lg text-lg font-semibold text-emerald-900 bg-white hover:bg-gray-100 transition-colors shadow-lg">
                            Start Free Trial
                            <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-lg text-lg font-semibold text-white bg-white/20 hover:bg-white/30 transition-colors border border-white/30">
                            Schedule Demo
                        </a>
                    </div>

                    <p class="text-emerald-200 text-sm mt-6">
                        No credit card required. 14-day free trial.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
                <!-- Brand -->
                <div class="lg:col-span-2">
                    <a href="{{ route('landing') }}" class="flex items-center gap-2 mb-4">
                        <div class="h-9 w-9 rounded-lg bg-emerald-600 flex items-center justify-center">
                            <span class="font-serif font-bold text-white text-xl">J</span>
                        </div>
                        <span class="font-serif font-bold text-xl text-white">JamHR</span>
                    </a>
                    <p class="text-gray-400 mb-6 max-w-sm">
                        The complete HR management solution built specifically for Jamaican businesses.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Product -->
                <div>
                    <h4 class="font-semibold text-white mb-4">Product</h4>
                    <ul class="space-y-3">
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Integrations</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Updates</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="font-semibold text-white mb-4">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="font-semibold text-white mb-4">Legal</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Cookie Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Security</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm">
                    &copy; {{ date('Y') }} JamHR. All rights reserved.
                </p>
                <p class="text-sm flex items-center gap-2">
                    Made with
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                    in Jamaica
                </p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js collapse plugin for FAQ -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.directive('collapse', (el, { expression }, { effect, evaluateLater }) => {
                let evaluate = evaluateLater(expression)

                el.style.overflow = 'hidden'

                effect(() => {
                    evaluate(value => {
                        if (value) {
                            el.style.height = el.scrollHeight + 'px'
                            setTimeout(() => {
                                el.style.height = 'auto'
                            }, 300)
                        } else {
                            el.style.height = el.scrollHeight + 'px'
                            requestAnimationFrame(() => {
                                el.style.height = '0px'
                            })
                        }
                    })
                })
            })
        })
    </script>
</body>
</html>
