<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'JamHR') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|merriweather:400,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-foreground antialiased">
    <div class="min-h-screen w-full flex items-center justify-center bg-slate-50 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 z-0 opacity-[0.03] pointer-events-none">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor" />
            </svg>
        </div>

        <div class="w-full max-w-md mx-4 border-none shadow-2xl relative z-10 bg-white/90 backdrop-blur-sm rounded-lg overflow-hidden">
            <div class="flex flex-col items-center text-center pt-10 pb-6">
                <div class="h-12 w-12 rounded-xl bg-secondary flex items-center justify-center mb-4 shadow-lg" style="box-shadow: 0 4px 14px rgba(242, 201, 37, 0.3);">
                    <span class="font-serif font-bold text-secondary-foreground text-2xl">J</span>
                </div>
                <h1 class="text-2xl font-serif font-bold tracking-tight text-foreground">
                    Welcome back
                </h1>
                <p class="text-base text-muted-foreground mt-1">
                    Sign in to your JamHR account
                </p>
            </div>
            <div class="pb-10 px-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
