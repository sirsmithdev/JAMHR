<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="JamHR Pricing - Affordable HR management plans for Jamaican businesses of all sizes.">
    <title>Pricing - JamHR</title>

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
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('landing') }}" class="flex items-center gap-2">
                    <div class="h-9 w-9 rounded-lg bg-emerald-600 flex items-center justify-center">
                        <span class="font-serif font-bold text-white text-xl">J</span>
                    </div>
                    <span class="font-serif font-bold text-xl text-gray-900">JamHR</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('features') }}" class="text-gray-600 hover:text-gray-900 transition-colors">Features</a>
                    <a href="{{ route('pricing') }}" class="text-emerald-600 font-medium">Pricing</a>
                    <a href="{{ route('landing') }}#faq" class="text-gray-600 hover:text-gray-900 transition-colors">FAQ</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900 transition-colors">Contact</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-gray-600 hover:text-gray-900 transition-colors">Sign In</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                            Start Free Trial
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-16 bg-gradient-to-br from-emerald-50 to-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl font-serif font-bold text-gray-900 mb-4">
                Simple, <span class="gradient-text">Transparent Pricing</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Choose the plan that fits your business. All plans include a 14-day free trial with no credit card required.
            </p>
        </div>
    </section>

    <!-- Pricing Cards -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $pricingPlans = [
                    [
                        'name' => 'Starter',
                        'price' => 2500,
                        'period' => 'per employee/month',
                        'description' => 'Perfect for small businesses getting started',
                        'features' => [
                            'Up to 25 employees',
                            'Payroll & Tax calculations',
                            'Leave management',
                            'Employee directory',
                            'Basic reporting',
                            'Email support',
                        ],
                        'cta' => 'Start Free Trial',
                        'highlighted' => false,
                    ],
                    [
                        'name' => 'Professional',
                        'price' => 4500,
                        'period' => 'per employee/month',
                        'description' => 'For growing businesses that need more',
                        'features' => [
                            'Up to 100 employees',
                            'Everything in Starter',
                            'Benefits management',
                            'Staff loans & allowances',
                            'Performance reviews',
                            'Time & attendance',
                            'Document management',
                            'Priority support',
                        ],
                        'cta' => 'Start Free Trial',
                        'highlighted' => true,
                    ],
                    [
                        'name' => 'Enterprise',
                        'price' => null,
                        'period' => 'Custom pricing',
                        'description' => 'For large organizations with complex needs',
                        'features' => [
                            'Unlimited employees',
                            'Everything in Professional',
                            'Multi-location support',
                            'Custom integrations',
                            'API access',
                            'Dedicated account manager',
                            'On-site training',
                            'SLA guarantee',
                        ],
                        'cta' => 'Contact Sales',
                        'highlighted' => false,
                    ],
                ];
            @endphp

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

    <!-- Feature Comparison Table -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-serif font-bold text-gray-900 mb-4">Compare Plans</h2>
                <p class="text-lg text-gray-600">See what's included in each plan</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full bg-white rounded-xl shadow-sm border border-gray-200">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-4 px-6 text-left text-gray-900 font-semibold">Feature</th>
                            <th class="py-4 px-6 text-center text-gray-900 font-semibold">Starter</th>
                            <th class="py-4 px-6 text-center text-gray-900 font-semibold bg-emerald-50">Professional</th>
                            <th class="py-4 px-6 text-center text-gray-900 font-semibold">Enterprise</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $comparisonFeatures = [
                                ['name' => 'Employee limit', 'starter' => '25', 'professional' => '100', 'enterprise' => 'Unlimited'],
                                ['name' => 'Payroll & Tax', 'starter' => true, 'professional' => true, 'enterprise' => true],
                                ['name' => 'Leave Management', 'starter' => true, 'professional' => true, 'enterprise' => true],
                                ['name' => 'Employee Directory', 'starter' => true, 'professional' => true, 'enterprise' => true],
                                ['name' => 'Benefits Management', 'starter' => false, 'professional' => true, 'enterprise' => true],
                                ['name' => 'Staff Loans', 'starter' => false, 'professional' => true, 'enterprise' => true],
                                ['name' => 'Performance Reviews', 'starter' => false, 'professional' => true, 'enterprise' => true],
                                ['name' => 'Time & Attendance', 'starter' => false, 'professional' => true, 'enterprise' => true],
                                ['name' => 'Document Management', 'starter' => false, 'professional' => true, 'enterprise' => true],
                                ['name' => 'Multi-location', 'starter' => false, 'professional' => false, 'enterprise' => true],
                                ['name' => 'Custom Integrations', 'starter' => false, 'professional' => false, 'enterprise' => true],
                                ['name' => 'API Access', 'starter' => false, 'professional' => false, 'enterprise' => true],
                                ['name' => 'Dedicated Account Manager', 'starter' => false, 'professional' => false, 'enterprise' => true],
                            ];
                        @endphp

                        @foreach($comparisonFeatures as $feature)
                        <tr class="border-b border-gray-100">
                            <td class="py-4 px-6 text-gray-700">{{ $feature['name'] }}</td>
                            <td class="py-4 px-6 text-center">
                                @if(is_bool($feature['starter']))
                                    @if($feature['starter'])
                                        <svg class="w-5 h-5 text-emerald-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                @else
                                    <span class="text-gray-600">{{ $feature['starter'] }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center bg-emerald-50">
                                @if(is_bool($feature['professional']))
                                    @if($feature['professional'])
                                        <svg class="w-5 h-5 text-emerald-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                @else
                                    <span class="text-gray-600">{{ $feature['professional'] }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if(is_bool($feature['enterprise']))
                                    @if($feature['enterprise'])
                                        <svg class="w-5 h-5 text-emerald-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                @else
                                    <span class="text-gray-600">{{ $feature['enterprise'] }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-serif font-bold text-gray-900 mb-4">Pricing FAQs</h2>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">How does the free trial work?</h3>
                    <p class="text-gray-600">You get 14 days of full access to all features in your chosen plan. No credit card required. At the end of your trial, you can choose to subscribe or your account will be paused.</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Can I upgrade or downgrade my plan?</h3>
                    <p class="text-gray-600">Yes, you can change your plan at any time. Upgrades take effect immediately, and downgrades take effect at the start of your next billing cycle.</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">Is there a setup fee?</h3>
                    <p class="text-gray-600">No, there are no setup fees for Starter and Professional plans. Enterprise plans may include custom implementation services, which are quoted separately.</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-2">What payment methods do you accept?</h3>
                    <p class="text-gray-600">We accept all major credit cards, bank transfers, and NCB/Scotiabank direct payments for Jamaican businesses.</p>
                </div>
            </div>

            <div class="text-center mt-8">
                <p class="text-gray-600 mb-4">Have more questions?</p>
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
    <section class="py-20 bg-emerald-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-serif font-bold text-white mb-4">
                Ready to get started?
            </h2>
            <p class="text-xl text-emerald-200 mb-8">
                Join hundreds of Jamaican businesses already using JamHR.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-lg text-lg font-semibold text-emerald-900 bg-white hover:bg-gray-100 transition-colors">
                    Start Free Trial
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-lg text-lg font-semibold text-white border border-white/30 hover:bg-white/10 transition-colors">
                    Talk to Sales
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                        <span class="font-serif font-bold text-white">J</span>
                    </div>
                    <span class="font-serif font-bold text-lg">JamHR</span>
                </div>
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} JamHR. All rights reserved. Built for Jamaica.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
