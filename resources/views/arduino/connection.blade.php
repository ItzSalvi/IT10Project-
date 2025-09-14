@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Arduino Device Connection</h1>
            <p class="mt-2 text-sm text-gray-600">Connect your ESP8266 device to automatically detect bottles and earn points</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Connection Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">Setup Instructions</h2>
            <div class="space-y-4 text-sm text-blue-800">
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">1</span>
                    <div>
                        <p class="font-medium">Upload Arduino Code</p>
                        <p>Upload the provided Arduino code to your ESP8266 device with IR sensor</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">2</span>
                    <div>
                        <p class="font-medium">Configure WiFi</p>
                        <p>Update the WiFi credentials in the Arduino code (ssid and password)</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">3</span>
                    <div>
                        <p class="font-medium">Set User ID</p>
                        <p>Update the userId variable in Arduino code to your user ID: <strong>{{ Auth::user()->id }}</strong></p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">4</span>
                    <div>
                        <p class="font-medium">Connect Device</p>
                        <p>Enter your device ID below and click "Connect Device"</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Device Connection Form -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Connect New Device</h2>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('arduino.connect') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="device_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Device ID
                        </label>
                        <input type="text" 
                               id="device_id" 
                               name="device_id" 
                               value="ESP8266_001"
                               class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('device_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               placeholder="Enter your device ID (e.g., ESP8266_001)"
                               required>
                        <p class="mt-2 text-sm text-gray-500">
                            This should match the deviceId in your Arduino code
                        </p>
                        @error('device_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                            Connect Device
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Connected Devices -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Connected Devices</h2>
            </div>
            
            <div class="p-6">
                @if(count($activeSessions) > 0)
                    <div class="space-y-4">
                        @foreach($activeSessions as $session)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">{{ $session['device_id'] }}</p>
                                        <p class="text-sm text-gray-500">
                                            Connected: {{ \Carbon\Carbon::parse($session['authenticated_at'])->diffForHumans() }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Last activity: {{ \Carbon\Carbon::parse($session['last_activity'])->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Connected
                                    </span>
                                    <form method="POST" action="{{ route('arduino.disconnect') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="device_id" value="{{ $session['device_id'] }}">
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 text-sm font-medium"
                                                onclick="return confirm('Are you sure you want to disconnect this device?')">
                                            Disconnect
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No devices connected</h3>
                        <p class="mt-1 text-sm text-gray-500">Connect your Arduino device to start automatic bottle detection.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Arduino Code Download -->
        <div class="mt-8 bg-gray-50 border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Arduino Code</h3>
            <p class="text-sm text-gray-600 mb-4">
                Download the Arduino code and upload it to your ESP8266 device. Make sure to update the configuration variables.
            </p>
            <div class="bg-gray-800 text-green-400 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                <div class="mb-2">// Configuration Variables to Update:</div>
                <div class="mb-1">const char* ssid = "YOUR_WIFI_SSID";</div>
                <div class="mb-1">const char* password = "YOUR_WIFI_PASSWORD";</div>
                <div class="mb-1">const char* serverURL = "http://{{ request()->getHost() }}:8000";</div>
                <div class="mb-1">const char* deviceId = "ESP8266_001";</div>
                <div class="mb-1">int userId = {{ Auth::user()->id }};</div>
            </div>
            <div class="mt-4">
                <a href="#" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Arduino Code
                </a>
            </div>
        </div>
    </div>
</div>
@endsection



