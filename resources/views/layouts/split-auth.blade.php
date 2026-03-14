<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-text-main antialiased bg-cream">
        <div class="flex h-screen w-full overflow-hidden">
            <!-- Left: High-res Café Image -->
            <div class="hidden lg:flex lg:w-1/2 relative">
                <img src="{{ asset('images/login-bg.png') }}" alt="Cafe Interior" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-sidebar/60 backdrop-blur-[2px]"></div>
                
                <div class="relative z-10 flex flex-col justify-between h-full p-20 text-white">
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl overflow-hidden shadow-lg">
                                <img src="/images/gemini-svg.svg" alt="Elite Cafe" class="w-full h-full object-cover">
                            </div>
                            <span class="text-2xl font-bold tracking-tighter">ELITE CAFE</span>
                        </div>
                    </div>

                    <div class="animate-fade-in-up">
                        <h1 class="text-5xl font-bold leading-tight">
                            Elevating Taste, <br>
                            <span class="text-gold">Simplify</span> Service.
                        </h1>
                        <p class="text-xl text-white/70 mt-6 max-w-md">
                            The most elegant Point of Sale system for modern cafés and restaurants. 
                            Manage your business with precision and style.
                        </p>
                    </div>

                    <div class="text-white/40 text-sm">
                        &copy; {{ date('Y') }} Elite Cafe POS. All rights reserved.
                    </div>
                </div>
            </div>

            <!-- Right: Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-cream relative">
                {{-- Decorative elements --}}
                <div class="absolute top-0 right-0 w-64 h-64 bg-gold/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-sidebar/5 rounded-full -ml-32 -mb-32 blur-3xl"></div>

                <div class="w-full max-w-md relative z-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
