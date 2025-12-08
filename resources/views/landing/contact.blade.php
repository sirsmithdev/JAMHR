<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact JamHR - Get in touch with our team for questions about HR management solutions for Jamaican businesses.">
    <title>Contact Us - JamHR</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&family=playfair-display:700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
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
                    <a href="{{ route('landing') }}#features" class="text-gray-600 hover:text-gray-900 transition-colors">Features</a>
                    <a href="{{ route('landing') }}#pricing" class="text-gray-600 hover:text-gray-900 transition-colors">Pricing</a>
                    <a href="{{ route('landing') }}#faq" class="text-gray-600 hover:text-gray-900 transition-colors">FAQ</a>
                    <a href="{{ route('contact') }}" class="text-emerald-600 font-medium">Contact</a>
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

    <!-- Contact Section -->
    <section class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-4xl sm:text-5xl font-serif font-bold text-gray-900 mb-4">Get in Touch</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Have questions about JamHR? Our team is here to help you find the perfect HR solution for your business.
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" id="name" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="John Brown">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" id="email" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="john@company.com">
                        </div>

                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                            <input type="text" name="company" id="company"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="Your Company Ltd.">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" id="phone"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="(876) 555-1234">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                            <textarea name="message" id="message" rows="4" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="Tell us about your HR needs..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full py-3 px-6 rounded-lg text-white font-semibold bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-600/20">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="space-y-8">
                    <div class="bg-gradient-to-br from-emerald-50 to-amber-50 rounded-2xl p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Contact Information</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Email</p>
                                    <a href="mailto:hello@jamhr.com" class="text-emerald-600 hover:text-emerald-700">hello@jamhr.com</a>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Phone</p>
                                    <a href="tel:+18765551234" class="text-emerald-600 hover:text-emerald-700">(876) 555-1234</a>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Office</p>
                                    <p class="text-gray-600">Kingston, Jamaica</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-900 rounded-2xl p-8 text-white">
                        <h3 class="text-xl font-bold mb-4">Request a Demo</h3>
                        <p class="text-gray-300 mb-6">
                            See JamHR in action with a personalized demo from our team. We'll show you how to streamline your HR operations.
                        </p>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 rounded-lg font-semibold bg-amber-500 hover:bg-amber-400 text-gray-900 transition-colors">
                            Schedule Demo
                            <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
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
