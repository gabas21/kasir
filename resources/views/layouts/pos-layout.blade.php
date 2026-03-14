<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    {{-- Meta tags untuk PWA & Mobile Web App --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#755833"> {{-- Match Elite Cafe branding --}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icon-default.png">
    
    <title>Elite Cafe POS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
    
    <style>
        /* Mencegah highlight seleksi saat double tap di PWA */
        body {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            overscroll-behavior-y: none; /* Cegah pull-to-refresh bawaan browser */
        }
        /* Tap highlight transparan pada mobile */
        * {
            -webkit-tap-highlight-color: transparent;
        }
        /* Mengizinkan seleksi pada input field */
        input, textarea {
            -webkit-user-select: auto;
            -khtml-user-select: auto;
            -moz-user-select: auto;
            -ms-user-select: auto;
            user-select: auto;
        }
    </style>
</head>
<body class="font-sans antialiased bg-cream text-text-main overflow-hidden h-screen w-screen relative">
    
    {{-- Tempat injeksi komponen Livewire full-page --}}
    {{ $slot }}

    @livewireScripts
</body>
</html>
