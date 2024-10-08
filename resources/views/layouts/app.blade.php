<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased ">
    <x-banner />

    <div class="fixed top-0 left-0 w-full h-screen bg-cover bg-center z-index: -1; antialiased bg-hero-main"> </div>
    <div class="relative w-full h-screen overflow-y-auto px-4 bg-gray-900 bg-opacity-70 ">
        @livewire('navigation-menu')

        <main>
            {{ $slot }}
        </main>

    </div>
    @stack('modals')
    @livewireScripts
</body>

</html>