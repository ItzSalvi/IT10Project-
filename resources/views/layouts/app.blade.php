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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased transition-colors duration-300" id="body">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300" id="main-container">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-300">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Global Theme and Zoom Initialization -->
        <script>
            // Initialize theme and zoom from localStorage on page load
            document.addEventListener('DOMContentLoaded', function() {
                const body = document.getElementById('body');
                
                // Apply saved theme
                const currentTheme = localStorage.getItem('theme') || 'light';
                if (currentTheme === 'dark') {
                    body.classList.add('dark');
                } else {
                    body.classList.remove('dark');
                }
                
                // Apply saved zoom level
                const currentZoom = parseFloat(localStorage.getItem('zoom') || '100');
                document.documentElement.style.fontSize = currentZoom + '%';
            });
        </script>
    </body>
</html>
