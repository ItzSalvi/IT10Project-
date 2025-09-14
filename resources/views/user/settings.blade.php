@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">User Settings</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Customize your experience and accessibility preferences</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- User Profile Information -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden transition-colors duration-300 mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Profile Information</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name
                        </label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ Auth::user()->full_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address
                        </label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ Auth::user()->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Total Points
                        </label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ Auth::user()->total_points ?? 0 }} points</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Member Since
                        </label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accessibility Settings -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 transition-colors duration-300">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Accessibility Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Theme Toggle -->
                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Theme Mode</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Switch between light and dark themes</p>
                        </div>
                    </div>
                    <button id="user-theme-toggle" class="p-2 rounded-md text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out" title="Toggle Dark/Light Theme">
                        <svg id="user-theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="user-theme-toggle-light-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- Zoom Controls -->
                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Zoom Level</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Adjust text size for better readability</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button id="user-zoom-out" class="p-2 rounded-md text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out" title="Zoom Out">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path>
                            </svg>
                        </button>
                        <span id="user-zoom-level" class="text-sm text-gray-500 dark:text-gray-300 px-2 min-w-[3rem] text-center">100%</span>
                        <button id="user-zoom-in" class="p-2 rounded-md text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out" title="Zoom In">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Settings Accessibility JavaScript -->
<script>
    // User Theme Toggle Functionality
    const userThemeToggle = document.getElementById('user-theme-toggle');
    const userThemeToggleDarkIcon = document.getElementById('user-theme-toggle-dark-icon');
    const userThemeToggleLightIcon = document.getElementById('user-theme-toggle-light-icon');
    const body = document.getElementById('body');

    // Check for saved theme preference or default to 'light'
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Apply the saved theme
    if (currentTheme === 'dark') {
        body.classList.add('dark');
        userThemeToggleDarkIcon.classList.remove('hidden');
        userThemeToggleLightIcon.classList.add('hidden');
    } else {
        body.classList.remove('dark');
        userThemeToggleDarkIcon.classList.add('hidden');
        userThemeToggleLightIcon.classList.remove('hidden');
    }

    // User theme toggle event listener
    userThemeToggle.addEventListener('click', function() {
        // Toggle the theme
        if (body.classList.contains('dark')) {
            body.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            userThemeToggleDarkIcon.classList.add('hidden');
            userThemeToggleLightIcon.classList.remove('hidden');
        } else {
            body.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            userThemeToggleDarkIcon.classList.remove('hidden');
            userThemeToggleLightIcon.classList.add('hidden');
        }
    });

    // User Zoom Controls Functionality
    const userZoomIn = document.getElementById('user-zoom-in');
    const userZoomOut = document.getElementById('user-zoom-out');
    const userZoomLevel = document.getElementById('user-zoom-level');
    
    // Get saved zoom level or default to 100%
    let currentZoom = parseFloat(localStorage.getItem('zoom') || '100');
    
    // Apply saved zoom level
    document.documentElement.style.fontSize = currentZoom + '%';
    userZoomLevel.textContent = Math.round(currentZoom) + '%';

    // User zoom in event listener
    userZoomIn.addEventListener('click', function() {
        if (currentZoom < 200) {
            currentZoom += 10;
            document.documentElement.style.fontSize = currentZoom + '%';
            userZoomLevel.textContent = Math.round(currentZoom) + '%';
            localStorage.setItem('zoom', currentZoom.toString());
        }
    });

    // User zoom out event listener
    userZoomOut.addEventListener('click', function() {
        if (currentZoom > 50) {
            currentZoom -= 10;
            document.documentElement.style.fontSize = currentZoom + '%';
            userZoomLevel.textContent = Math.round(currentZoom) + '%';
            localStorage.setItem('zoom', currentZoom.toString());
        }
    });
</script>
@endsection
