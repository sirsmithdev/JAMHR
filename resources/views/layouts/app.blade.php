<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'JamHR') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|merriweather:400,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-background flex">
        <!-- Desktop Sidebar -->
        <aside class="hidden md:flex md:flex-col w-64 bg-sidebar border-r border-border/50 fixed inset-y-0 left-0 z-50 shadow-xl h-screen">
            @include('layouts.sidebar')
        </aside>

        <!-- Mobile Header -->
        <div class="md:hidden fixed top-0 left-0 right-0 h-16 bg-sidebar border-b border-border/50 flex items-center px-4 z-50 justify-between">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-secondary flex items-center justify-center">
                    <span class="font-serif font-bold text-secondary-foreground text-lg">J</span>
                </div>
                <span class="font-serif font-bold text-xl text-sidebar-foreground">JamHR</span>
            </div>
            <button type="button" id="mobile-menu-button" class="text-sidebar-foreground p-2 rounded-md hover:bg-sidebar-accent">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-sidebar-overlay" class="fixed inset-0 bg-black/50 z-50 hidden md:hidden">
            <aside class="w-72 bg-sidebar h-full">
                @include('layouts.sidebar')
            </aside>
        </div>

        <!-- Main Content -->
        <main class="flex-1 md:ml-64 pt-16 md:pt-0 min-h-screen transition-all duration-300">
            <div class="max-w-7xl mx-auto p-4 md:p-8">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');

        if (mobileMenuButton && mobileSidebarOverlay) {
            mobileMenuButton.addEventListener('click', () => {
                mobileSidebarOverlay.classList.toggle('hidden');
            });

            mobileSidebarOverlay.addEventListener('click', (e) => {
                if (e.target === mobileSidebarOverlay) {
                    mobileSidebarOverlay.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>
